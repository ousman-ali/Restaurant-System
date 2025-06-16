<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="{{url('/')}}" class="logo">
                <i class="icon-c-logo my-logo">R</i>
                <span class="my-logo">Restulator</span>
            </a>
            <!-- Image Logo here -->
            {{--<a href="{{url('/')}}" class="logo">--}}
            {{--<i class="icon-c-logo"> <img src="http://via.placeholder.com/350x150" height="42"/> </i>--}}
            {{--<span><img src="http://via.placeholder.com/350x150" height="20"/></span>--}}
            {{--</a>--}}
        </div>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>
                @guest
                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li><a href="{{ route('login') }}" class="waves-effect waves-light">Login</a></li>
                            @if(\App\Models\User::first())

                            @else
                                <li><a href="{{ route('register') }}" class="waves-effect waves-light">Join</a></li>
                            @endif
                        </ul>
                    
                @else

                    @admin
                    <ul class="nav navbar-nav hidden-xs">
                        <li><a href="{{url('/new-order')}}" class="waves-effect waves-light">New Order</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Shortcut menu <span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{url('/live-kitchen')}}">Live Kitchen</a></li>
                                <li><a href="{{url('/kitchen-stat')}}">Kitchen report</a></li>
                                <li><a href="{{url('/waiter-stat')}}">Waiter report</a></li>
                                <li><a href="{{url('/dish-stat')}}">Dish report</a></li>
                            </ul>
                        </li>
                    </ul>
                    @endadmin

                    @manager
                    <ul class="nav navbar-nav hidden-xs">
                        <li><a href="{{url('/new-order')}}" class="waves-effect waves-light">New Order</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Shortcut menu <span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{url('/live-kitchen')}}">Live Kitchen</a></li>
                                <li><a href="{{url('/kitchen-stat')}}">Kitchen report</a></li>
                                <li><a href="{{url('/waiter-stat')}}">Waiter report</a></li>
                                <li><a href="{{url('/dish-stat')}}">Dish report</a></li>
                            </ul>
                        </li>
                    </ul>
                    @endmanager
                    
                    
                    <!-- Notification Dropdown -->
                    <ul class="nav navbar-nav navbar-right pull-right">
                        <li class="dropdown top-menu-item-xs notificationDropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell"></i>
                                <span class="badge badge-danger notif-count"style="display: none;">0</span>
                            </a>
                            <ul class="dropdown-menu notif-menu"
                                style="width: 300px; max-height: 400px; overflow-y: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
                                <li class="text-center"><strong>Notifications</strong></li>
                                <li class="divider"></li>
                                <!-- Dynamic notifications will appear here -->
                            </ul>
                        </li>
                    </ul>
                    <script>
                        window.userRole = {{ auth()->user()->role }};
                        const isAdmin = @json(auth()->check() && auth()->user()->role == 1);
                        const isWaiter = @json(auth()->check() && auth()->user()->role == 3);
                    </script>

                    <ul class="nav navbar-nav navbar-right pull-right">
                        <li class="dropdown top-menu-item-xs">
                            <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                               aria-expanded="true"><img
                                    src="{{auth()->user()->image ? auth()->user()->image : url('/img_assets/default-thumbnail.jpg')}}"
                                    alt="user-img"
                                    class="img-circle"> </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{url('/profile')}}"><i class="ti-user m-r-10 text-custom"></i> Profile</a>
                                </li>
                                <li><a href="{{url('/profile-edit')}}"><i class="ti-settings m-r-10 text-custom"></i>
                                        Settings</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="ti-power-off m-r-10 text-danger"></i> Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endguest
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- Top Bar End -->
