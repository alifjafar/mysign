@extends('layouts.app')
@section('content')
    <div id="primary" class="p-t-b-100 height-full ">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mx-md-auto">
                    <div class="text-center">
                        <img src="{{ asset('img/icon/icon1.png') }}" class="img-fluid"
                             alt="Logo">
                        <h2>My Sign</h2>
                        <p class="pt-2 pb-2">Silahkan Login Untuk Melanjutkan</p>
                    </div>
                    <form action="{{ route('login') }}" method="POST">
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
                        <div class="form-group has-icon"><i class="icon-envelope-o"></i>
                            <input type="text" class="form-control form-control-lg"
                                   placeholder="Username / Email" name="email">
                        </div>
                        <div class="form-group has-icon"><i class="icon-user-secret"></i>
                            <input type="password" class="form-control form-control-lg"
                                   placeholder="Password" name="password">
                        </div>
                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Login">
                    </form>
                    <div class="modal-footer bg-transparent "><p class="text-sm">Belum punya akun? </p><a
                            href="{{ route('register') }}"><p class="text-sm">Daftar Sekarang</p></a></div>
                </div>
            </div>
        </div>
    </div>
@endsection
