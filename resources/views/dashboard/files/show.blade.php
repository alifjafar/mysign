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
                        <a href="#" class="btn btn-primary btn-sm"><i
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
                    <div class="card-body">
                        <dl>
                            <dd>Integrity</dd>
                            <dt>{{ $file['details']['integrity']['messages'] }}</dt>
                        </dl>
                        <dl>
                            <dd>Name</dd>
                            <dt>{{ $file['details']['name'] }}</dt>
                        </dl>
                        <dl>
                            <dd>Reason</dd>
                            <dt>{{ $file['details']['reason'] }}</dt>
                        </dl>
                        <dl>
                            <dd>Date</dd>
                            <dt>{{ $file['details']['date'] }}</dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

@endpush
