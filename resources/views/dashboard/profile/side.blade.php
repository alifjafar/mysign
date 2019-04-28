<div class="col-md-3">

    <!-- Profile Image -->
    <div class="card no-b my-3 shadow">
        <div class="card-header bg-white">
            Foto Profil
        </div>
        <div class="card-body">
            <div align="center">
                <img class="avatar" src="{{ $user->avatar }}" style="width: 120px; height: 120px;">

                <p class="mt-3 s-18">{{ $user->name }}</p>
            </div>
        </div>

        <div class="card-footer">
            <smal>Foto Profile Menggunakan Gravatar.com</smal>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
