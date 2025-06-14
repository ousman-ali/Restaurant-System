@extends('layouts.app')

@section('title')
    Summary
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box widget-inline">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="text-primary md md-add-shopping-cart"></i> <b data-plugin="counterup">8954</b>
                            </h3>
                            <h4 class="text-muted">Lifetime total sales</h4>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="text-custom md md-attach-money"></i> <b data-plugin="counterup">7841</b></h3>
                            <h4 class="text-muted">Income amounts</h4>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center">
                            <h3><i class="text-pink md md-account-child"></i> <b data-plugin="counterup">6521</b></h3>
                            <h4 class="text-muted">Total users</h4>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6">
                        <div class="widget-inline-box text-center b-0">
                            <h3><i class="text-purple md md-visibility"></i> <b data-plugin="counterup">325</b></h3>
                            <h4 class="text-muted">Total visits</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection