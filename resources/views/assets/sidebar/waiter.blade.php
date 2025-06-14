<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="">
                    <a href="{{url('/home')}}" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i> <span> Orders </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/new-order')}}">New Order</a></li>
                        <li><a href="{{url('/my-orders')}}">My Order</a></li>
                    </ul>
                </li>

                <li class="">
                    <a href="{{url('/kitchen-status')}}" class="waves-effect"><i class="icon icon-fire"></i> <span> Kitchen Status </span> </a>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>