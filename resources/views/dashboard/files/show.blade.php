@extends('layouts.app')
@section('title','My Files - ')
@section('content')
    <header class="white b-b p-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <h3>
                        {{ $file['filename'] }}
                    </h3>
                </div>
                <div class="col-md-3">
                    <span class="float-right">
                        <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sign_request"><i
                                class="icon icon-signing"></i> Sign Request</a>
                    </span>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-9">
                <div id="pdfObject"></div>
            </div>
            <div class="col-md-3">
                <div class="card no-b">
                    <div class="card-header white">
                        <span class="card-title bold"><strong>Informasi</strong></span>
                    </div>
                    <div class="card-body scroll-box">
                        @if($file['details']['result'] ?? '')
                            @foreach($file['details']['result'] as $item)
                                <dl>
                                    <dd>Integrity</dd>
                                    <dt>{{ $item['integrity']['messages'] }}</dt>
                                </dl>
                                <dl>
                                    <dd>Name</dd>
                                    <dt>{{ $item['name'] }}</dt>
                                </dl>
                                <dl>
                                    <dd>Reason</dd>
                                    <dt>{{ $item['reason'] }}</dt>
                                </dl>
                                <dl>
                                    <dd>Date</dd>
                                    <dt>{{ $item['date'] }}</dt>
                                </dl>
                                <hr>
                            @endforeach
                        @else
                            <div class="alert alert-danger">{{ $file['details']['messages'] }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['selector' => 'sign_request'])
        @slot('title')
            Sign Request
        @endslot

        @slot('content')
            <form action="{{ route('my-request.store') }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="file_id" value="{{ $file['id'] }}">
                    <div class="form-group">
                        <label for="recipient">Recipient</label>
                        <select name="recipient_id" id="recipient" class="form-control"
                                required></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        @endslot
    @endcomponent

@endsection
@push('js')
    <script>
        @if(Session::has('success'))
        swal("Berhasil !", '{{ Session::get('success') }}', "success");
        @endif
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteFile(fileId, fileName) {
            swal({
                title: "Apa anda yakin?",
                text: "Anda Menghapus File " + fileName,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete => {
                if (willDelete) {
                    let theUrl = "{{ route('files.destroy', ':fileId') }}";
                    theUrl = theUrl.replace(":fileId", fileId);
                    $.ajax({
                        type: 'POST',
                        url: theUrl,
                        data: {_method: "delete"},
                        success: function (data) {
                            window.location.href = data;
                        },
                        error: function (data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        }
                    });
                }
            }));
        }
    </script>

    <script>
        $('<iframe src="' + `{{asset('js/pdfjs/web/viewer.html')}}?file={{ action('FileController@getDocument', $file['id']) }}`
            + '" style="width:100%; height:82vh;" frameborder="0" scrolling="no" id="displayPDF"></iframe>').appendTo('#pdfObject');
    </script>

    <script>
        $('#recipient').select2({
            placeholder: "Pilih Penerima",
            ajax: {
                url: '{{ route('users.ajax') }}',
                data: function (params) {
                    return {
                        q: params.term,
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name + " (" + item.email + ")"
                            }
                        })
                    }
                }
            },
            cache: true
        });
    </script>

@endpush
