@extends('layouts.app')
@section('title','My Files - ')
@section('content')
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card no-b my-3 shadow">
                    <div class="card-header white">
                        <h6>My Request</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 mb-4">
                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_file"><i
                                    class="icon icon-add"></i>Ajukan Tanda Tangan</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" id="data-table">
                                <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Owner</th>
                                    <th>Recipient</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
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
@endsection

@push('js')
    <script>
        $('#data-table').DataTable({
            "columnDefs": [{
                "targets": 5,
                "orderable": false
            }],
            "responsive": true,
            "pageLength": 10,
        });

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

@endpush
