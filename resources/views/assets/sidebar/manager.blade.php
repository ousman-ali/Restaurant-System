<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="{{url('/home')}}" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-chart"></i> <span> Reports </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/kitchen-stat')}}">Kitchen</a></li>
                        <li><a href="{{url('/waiter-stat')}}">Waiter</a></li>
                        <li><a href="{{url('/dish-stat')}}">Dish</a></li>
                    </ul>
                </li>


                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-fire"></i> <span> Kitchen </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/live-kitchen')}}">Live Kitchen</a></li>
                        {{--<li><a href="{{url('/kitchen-stat')}}">Kitchen Statistics</a></li>--}}
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i> <span> Orders </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/new-order')}}">New Order</a></li>
                        <li><a href="{{url('/all-order')}}">All Order</a></li>
                        <li><a href="{{url('/non-paid-order')}}">Non paid Order</a></li>
                    </ul>
                </li>



                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-truck"></i> <span> Supplier </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/add-supplier') }}">Add Supplier</a></li>
                        <li><a href="{{ url('/all-supplier') }}">All Supplier</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect {{isset($account_menu) ? 'active' : ''}}"><i class="icon icon-calculator"></i><span> Accounting </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Expense</span>  <span class="menu-arrow"></span></a>
                            <ul style="">
                                {{--<li><a href="{{url('/new-purses')}}"><span>New Purses</span></a></li>--}}
                                <li><a href="{{url('/add-expense')}}"><span>Add Expense</span></a></li>
                                <li><a href="{{url('/all-expanse')}}"><span>All Expense</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{url('/all-income')}}"><span>Income</span></a>
                        </li>

                    </ul>

                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-menu-alt"></i><span>Tables Management</span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/add-table')}}">Add Tables</a></li>
                        <li><a href="{{url('/all-table')}}">All Table</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i><span> Stock Management </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/new-purses')}}">New Purses</a></li>
                        <li><a href="{{url('/all-purses')}}">All Purses</a></li>
                        <li><a href="{{url('/add-item')}}">Add Item</a></li>
                        <li><a href="{{url('/all-item')}}">All Stock</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-cutlery"></i><span> Dish </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/add-dish')}}">Add Dish</a></li>
                        <li><a href="{{url('/all-dish')}}">All Dish</a></li>
                    </ul>
                </li>

                <li class="text-muted menu-title">More</li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-people"></i><span> Employee </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/add-employee')}}">Add Employee</a></li>
                        <li><a href="{{url('/all-employee')}}">All Employee</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="icon icon-settings"></i><span> Settings </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/all-product-type')}}">Product Type Setting</a></li>
                        <li><a href="{{url('/all-unit')}}">Unit Setting</a></li>
                    </ul>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
