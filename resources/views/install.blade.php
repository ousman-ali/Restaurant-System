@extends('layouts.app')

@section('title')
    Install
@endsection

@section('content')

    <<div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class=" card-box">
            <div class="panel-heading">
                <h3 class="text-center"> Setup Database (MySQL) {{config('restaurant.hasInstall')}} </h3>
                {{request()->getHttpHost()}}
            </div>


            <div class="panel-body">
                <form class="form-horizontal m-t-20" id="installForm" action="{{ url('/db-setup') }}" method="post" data-parsley-validate novalidate>
                    {{csrf_field()}}
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <label for="" class="form-control-label">Host:</label>
                            <input class="form-control" type="url" required parsley-type="url" placeholder="Database host. should be ip address" name="host" value="127.0.0.1">
                            @if ($errors->has('host'))
                                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('host') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="" class="form-control-label">Port :</label>
                            <input class="form-control" type="text" data-parsley-type="number" data-parsley-pattern="/^\S*$/" required name="port" placeholder="i.e : 3306" value="3306">
                            @if ($errors->has('port'))
                                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('port') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="" class="form-control-label">MySQL Database :</label>
                            <input class="form-control" type="text" required  data-parsley-pattern="/^\S*$/" name="mysql_db" placeholder="i.e : resturant_db" value="{{old('mysql_db')}}">
                            @if ($errors->has('mysql_db'))
                                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('mysql_db') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="" class="form-control-label">MySQL Username :</label>
                            <input class="form-control" type="text" required  data-parsley-pattern="/^\S*$/" name="mysql_user" placeholder="i.e : root" value="{{old('mysql_user')}}">
                            @if ($errors->has('mysql_user'))
                                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('mysql_user') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label for="" class="form-control-label">MySQL Password :</label>
                            <input class="form-control" type="text" required name="mysql_pass" placeholder="My SQL Password" value="{{old('mysql_pass')}}">
                            @if ($errors->has('mysql_pass'))
                                <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('mysql_pass') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group text-center m-t-40">
                        <div class="col-xs-12">
                            <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">Submit</button>
                        </div>
                    </div>


                </form>

            </div>
            @if($errors->any())
                <h4>{{$errors->first()}}</h4>
            @endif
        </div>

    </div>

@endsection
