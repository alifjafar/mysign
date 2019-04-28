@extends('layouts.app')
@section('content')
    <!-- Content Start -->
    <div class="container-fluid my-3">
        <div class="row">
            <div class="col-md-12">

                <!--Style Start 3-->
                <div class="row text-white no-gutters no-m shadow">
                    <div class="col-lg-3">
                        <div class="green counter-box p-40">
                            <div class="float-right">
                                <span class="icon icon-box-filled2 s-48"></span>
                            </div>
                            <div class="sc-counter s-36">0</div>
                            <h6 class="counter-title">Request Saya</h6>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="blue1 counter-box p-40">
                            <div class="float-right">
                                <span class="icon icon-subject s-48"></span>
                            </div>
                            <div class="sc-counter s-36">0</div>
                            <h6 class="counter-title">Butuh Tindakan</h6>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="sunfollower counter-box p-40">
                            <div class="float-right">
                                <span class="icon icon-user s-48"></span>
                            </div>
                            <div class="sc-counter s-36">0</div>
                            <h6 class="counter-title">Jumlah File</h6>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="strawberry counter-box p-40">
                            <div class="float-right">
                                <span class="icon icon-user s-48"></span>
                            </div>
                            <div class="sc-counter s-36">0</div>
                            <h6 class="counter-title">Jumlah User</h6>
                        </div>
                    </div>
                </div>
                <!--Style End 3-->
            </div>
            <div class="col-md-12">
                <div class="card my-3 shadow no-b">
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->
@endsection