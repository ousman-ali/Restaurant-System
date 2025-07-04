<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>


                <li class="">
                    <a href="{{url('/home')}}" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                @php
                    $lowStockCount = \App\Models\Product::withSum('purses', 'quantity')
                        ->withSum('cookedProducts', 'quantity')
                        ->where('dish_type', 'ready')
                        ->get()
                        ->filter(function ($product) {
                            $availableStock = ($product->purses_sum_quantity ?? 0) - ($product->cooked_products_sum_quantity ?? 0);
                            return $availableStock < $product->minimum_stock_threshold;
                        })
                        ->count();
                @endphp

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> Materials </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/baker/all-stock')}}">Stock Status
                            @if($lowStockCount > 0)
                                <span class="badge badge-danger ml-1">{{ $lowStockCount }}</span>
                            @endif
                        </a></li>
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