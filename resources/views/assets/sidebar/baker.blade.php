<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>


                <li class="">
                    <a href="{{url('/home')}}" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> Materials </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/baker/all-stock')}}">Stock Status</a></li>
                        <li><a href="{{url('/baker/low-stock')}}">Request Material</a></li>
                    </ul>
                </li>

                <li class="">
                    <a href="{{url('/baker-cooking-history')}}" class="waves-effect"><i class="ti-home"></i> <span> History </span> </a>
                </li>


            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>