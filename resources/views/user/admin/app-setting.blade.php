@extends('layouts.app')

@section('title')
    Add Settings
@endsection

@section('content')

    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="#">Settings</a>
        </li>
        <li>
            <a href="{{url('/app-settings')}}">App Settings</a>
        </li>
    </ol>
    <div class="card-box">
        After setup your mail/pusher or app you must config your application cache. <a href="{{url('/cache-config')}}">Click Hear</a> To config your application cache. After cache config you might need to re login

    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-lg-12">


                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#mail" data-toggle="tab" aria-expanded="true">
                            <span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
                            <span class="hidden-xs">Mail Setup</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#pusher" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-paper-plane-o"></i></span>
                            <span class="hidden-xs">Pusher Setup</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#appSetup" data-toggle="tab" aria-expanded="false">
                            <span class="visible-xs"><i class="fa fa-file-pdf-o"></i></span>
                            <span class="hidden-xs">App Setup</span>
                        </a>
                    </li>


                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="mail">
                        <h3>SMTP Setup</h3>
                        <form class="form-horizontal" role="form" id="smtpMailSetting"  data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <div id="smtpDetails">
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">Host <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input id="hori-pass1" type="url" data-parsley-pattern="/^\S*$/" name="host" placeholder="Host" value="{{config('mail.host')}}" required parsley-type="url" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Port <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="text" required data-parsley-pattern="/^\S*$/" name="port" placeholder="Port" value="{{config('mail.port')}}" class="form-control" id="hori-pass2">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Mail Address <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="email" data-parsley-pattern="/^\S*$/" required name="mail_address" placeholder="Mail Address" value="{{config('mail.username')}}" class="form-control" id="hori-pass2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">Encryption <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input id="" type="text" data-parsley-pattern="/^\S*$/" name="encryption" placeholder="Encryption [lave empty if encryption is null]" value="{{config('mail.encryption')}}"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Password <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="password" required name="password" placeholder="Password"  class="form-control" id="pass">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Re-Password <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input data-parsley-equalto="#pass" type="password" required placeholder="Password" class="form-control" id="hori-pass2">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Mail Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" data-parsley-pattern="/^\S*$/" required name="mail_form" placeholder="Mail Name" value="{{config('mail.from.name')}}" class="form-control" id="hori-pass2">
                                    </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Save smtp mail
                                    </button>

                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane" id="pusher">
                        <h3>Pusher Setup</h3>
                        <form class="form-horizontal" role="form" id="pusherSettings" method="post"  data-parsley-validate novalidate>
                            {{csrf_field()}}
                            <div id="smtpDetails">
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">App ID <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="App ID" name="app_id" value="{{config('broadcasting.connections.pusher.app_id')}}" required  class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Key <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="text" required placeholder="Key" name="key" value="{{config('broadcasting.connections.pusher.key')}}" class="form-control" id="hori-pass2">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Secret <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="text" required placeholder="Pusher Secret" name="secret" value="{{config('broadcasting.connections.pusher.secret')}}" class="form-control" id="hori-pass2">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="hori-pass2"class="col-sm-2 control-label">Cluster <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input  type="text" required placeholder="cluster" name="cluster" value="{{config('broadcasting.connections.pusher.options.cluster')}}" class="form-control" id="pass">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Encrypted</label>
                                        <div class="col-sm-7">
                                            <select name="encrypted" id="" class="form-control">
                                                <option value="true">True</option>
                                                <option value="false">False</option>
                                            </select>
                                        </div>
                                    </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Save smtp mail
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="appSetup">
                        <form class="form-horizontal" role="form" id="timeZoneSettings"  data-parsley-validate novalidate>
                            {{csrf_field()}}
                            <div id="smtpDetails">
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">Restaurant Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input id="" name="app_name" type="text" placeholder="Restaurant" value="{{config('app.name')}}" required  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div id="smtpDetails">
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">TimeZone <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input id="" name="timezone" type="text" placeholder="TimeZone" value="{{config('app.timezone')}}" required data-parsley-pattern="/^\S*$/" class="form-control">
                                        <a href="http://php.net/manual/en/timezones.php" target="_blank" class="btn btn-link">Find your timezone</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Save Timezone
                                    </button>

                                </div>
                            </div>
                        </form>

                        <form class="form-horizontal" role="form" id="currencySettings"  data-parsley-validate novalidate>
                            {{csrf_field()}}
                            <div id="smtpDetails">
                                <div class="form-group">
                                    <label for="hori-pass1" class="col-sm-2 control-label">Currency Symbol <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <input id="" name="symbol" type="text" placeholder="Symbol" value="{{config('restaurant.currency.symbol')}}" required data-parsley-pattern="/^\S*$/" class="form-control">

                                    </div>
                                    <label for="hori-pass1" class="col-sm-2 control-label">Currency <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <input id="" name="currency" type="text" placeholder="Currency" value="{{config('restaurant.currency.currency')}}" required data-parsley-pattern="/^\S*$/" class="form-control">

                                    </div>
                                </div>
                                <div class="form-group">

                                    <label for="hori-pass1" class="col-sm-2 control-label">Vat Percentage <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <input id="" name="var_percentage" type="number" min="0" placeholder="Vat Percentage" value="{{config('restaurant.vat.vat_percentage')}}" required data-parsley-pattern="/^\S*$/" class="form-control">

                                    </div>
                                    <label for="hori-pass1" class="col-sm-2 control-label">Vat Number <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input id="" name="vat_number" type="text" placeholder="Vat Number" value="{{config('restaurant.vat.vat_number')}}" required data-parsley-pattern="/^\S*$/" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label for="hori-pass1" class="col-sm-2 control-label">Phone Number <span class="text-danger">*</span></label>
                                    <div class="col-sm-2">
                                        <input id="" name="phone" type="text" min="0" placeholder="Resturant Phone Number" value="{{config('restaurant.contact.phone')}}" required  class="form-control">

                                    </div>
                                    <label for="hori-pass1" class="col-sm-2 control-label">Contact Address <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input id="" name="address" type="text" placeholder="Resturant Address" value="{{config('restaurant.contact.address')}}" required  class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Save Currency
                                    </button>

                                </div>
                            </div>
                        </form>


                    </div>


                    <div class="tab-pane" id="language">
                        <p>Language ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
                        <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('extra-js')
    <script src="{{url('/app_js/SettingsController.js')}}"></script>
@endsection