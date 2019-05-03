@extends('layouts.app')
@section('title','My Files - ')
@section('content')
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card no-b my-3 shadow">
                    <div class="card-header white">
                        <h6>My Files</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 mb-4">
                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_file"><i
                                    class="icon icon-add"></i>Upload File</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" id="data-table">
                                <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Size</th>
                                    <th>Mime Type</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $item)
                                    <tr>
                                        <td>
                                            {{ $item['filename'] }}
                                        </td>
                                        <td>{{ $item['size'] }}</td>
                                        <td>{{ $item['mime'] }}</td>
                                        {{--<td>{{ $item['status'] }}</td>--}}
                                        <td>
                                            <a href="{{ route('files.show', $item['id']) }}"
                                               class="btn btn-xs btn-primary">
                                                <i class="icon icon-open_in_new"></i>Buka File
                                            </a>
                                            <a href="#" class="btn btn-xs btn-success" data-toggle="modal"
                                               data-target="#show_details" data-certificate="{{ $item }}">
                                                <i class="icon icon-eye"></i>Show Detail</a>
                                            <button onclick="deleteFile('{{ $item['id'] }}', '{{ $item['filename'] }}')"
                                                    class="btn btn-danger btn-xs"><i
                                                    class="icon icon-trash"></i>Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['selector' => 'add_file'])
        @slot('title')
            Upload File
        @endslot

        @slot('content')
            <form action="{{ route('files.store') }}" method="post" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="file">Pilih File Dokumen</label>
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="file"
                                   value="{{ old('file') }}">
                            <label class="custom-file-label" for="file">Pilih File</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        @endslot
    @endcomponent

    @component('components.modal', ['selector' => 'show_details', 'isLarge' => 'modal-lg'])
        @slot('title')
            Digital Signature
        @endslot

        @slot('content')
            <div class="modal-body">
                <div class="row" id="contentCert">
                    <div class="col-md-12">
                        <div class="card panel-default">
                            <div class="card-body">
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        @endslot
    @endcomponent
@endsection

@push('js')
    <script>
        $('#data-table').DataTable({
            "columnDefs": [{
                "targets": 3,
                "orderable": false
            }],
            "responsive": true,
            "pageLength": 10,
        });

        @if(session()->has('success'))
        swal("Berhasil !", '{{ Session::get('success') }}', "success");
        @endif
        @if(session()->has('showModal'))
        $('#show_details').modal('show');
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
        $('#show_details').on('show.bs.modal', function (e) {
            let button = $(e.relatedTarget);
            let raw = button.data('certificate');
            let data = raw['details'];
            console.log(data);
            $('#contentCert').empty();
            if (data['status'] == "ok") {
                $.each(data['result'], function (i, v) {
                    $('#contentCert').append(
                        '<div class="col-md-12 mb-3"> ' +
                        '<div class="card panel-default">' +
                        '<div class="card-body">' +
                        '<div class="row">' +
                        '<div class="col-md-6">' +
                        '<dl class="list-information cf">' +
                        '<dd>Integrity</dd>' +
                        '<dt><strong class="text-success">' + v['integrity']['messages'] +
                        '</strong></dt></dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Name</dd>' +
                        '<dt>' + v['name'] + '</dt>' + '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Reason</dd>' + '<dt>' + v['reason'] +
                        '</dt>' +
                        '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Date</dd>' + '<dt>' + v['date'] + '</dt>' + '</dl>' + '</div>' +
                        '<div class="col-md-6">' +
                        '<dl class="list-information cf">' +
                        '<dd>Validity</dd>' +
                        '<dt>' + v['detail']['validity'] + '</dt>' +
                        '</dl>' + '<dl class="list-information cf">' +
                        '<dd>Subject</dd>' +
                        '<dt>' + JSON.stringify(v['detail']['subject']) + '</dt>' + '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Issuer</dd>' +
                        '<dt>' + JSON.stringify(v['detail']['issuer']) + '</dt>' + '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Public Key</dd>' +
                        '<dt>' + v['detail']['public_key'] + '</dt>' +
                        '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>Algorithm</dd>' +
                        '<dt>' + v['detail']['algorithm'] + '</dt>' + '</dl>' +
                        '<dl class="list-information cf">' +
                        '<dd>SHA-1 Fingerprint</dd>' +
                        '<dt>' + v['detail']['fingerprints'] + '</dt>' +
                        '</dl>' +
                        '</div> </div> </div> </div> </div>'
                    );
                })
            } else {
                $('#contentCert').append(
                    '<div class="alert alert-danger col-md-12">' + data['messages'] + '</div>'
                )
            }
        });
    </script>

@endpush
