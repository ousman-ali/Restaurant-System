<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="">
                    <a href="{{url('/home')}}" class="waves-effect"><i class="ti-home"></i> <span> Dashboard </span> </a>
                </li>

                @php
                    
                        $dishes = \App\Models\Dish::where('order_to', 'barman')->with('dishRecipes.product')->get();
                        $productIds = $dishes->flatMap(function ($dish) {
                            return $dish->dishRecipes->pluck('product.id');
                        })->unique()->values();
                        $products = \App\Models\Product::whereIn('id', $productIds)
                            ->withSum('purses', 'quantity')
                            ->withSum('cookedProducts', 'quantity')
                            ->get();
                        $lowStockProducts = $products->filter(function ($product) {
                            $purchased = $product->purses_sum_quantity ?? 0;
                            $used = $product->cooked_products_sum_quantity ?? 0;
                            $stock = $purchased - $used;
                            return $stock <= $product->minimum_stock_threshold;
                        })->map(function ($product) {
                            $product->stock = ($product->purses_sum_quantity ?? 0) - ($product->cooked_products_sum_quantity ?? 0);
                            return $product;
                        });

                        $lowStockCount = $lowStockProducts->count();
                @endphp
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> Materials </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/barman/all-stock')}}">Stock Status
                         @if($lowStockCount > 0)
                                <span class="badge badge-danger ml-1">{{ $lowStockCount }}</span>
                            @endif
                        </a></li>
                        <li><a href="{{url('/barman/low-stock')}}">Request Material</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i> <span> Orders </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/new-barman-order')}}">New Order</a></li>
                        <li><a href="{{url('/my-barman-orders')}}">My Order</a></li>
                    </ul>
                </li>

                <li class="">
                    <a href="{{url('/baker-status')}}" class="waves-effect"><i class="icon icon-fire"></i> <span> Baker Status </span> </a>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>