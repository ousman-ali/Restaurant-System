<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box fadeInDown animated">
            <div class="bg-icon bg-icon-info pull-left">
                <i class="md md-attach-money text-info"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter">
                        {{count(\App\Models\Order::where('served_by',auth()->user()->id)->get())}}
                    </b></h3>
                <p class="text-muted">Total Order</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-pink pull-left">
                <i class="md md-add-shopping-cart text-pink"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b class="counter">
                        {{count(\App\Models\Order::where('served_by',auth()->user()->id)->where('created_at','like','%'.Carbon\Carbon::now()->format('Y-m-d').'%')->get())}}
                    </b></h3>
                <p class="text-muted">Order Today</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-purple pull-left">
                <i class="md md-equalizer text-purple"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b
                        class="counter">{{count(\App\Models\Order::where('served_by',auth()->user()->id)->where('status',0)->get())}}</b>
                </h3>
                <p class="text-muted">Pending Order to Kitchen</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-bg-color-icon card-box">
            <div class="bg-icon bg-icon-purple pull-left">
                <i class="md md-equalizer text-purple"></i>
            </div>
            <div class="text-right">
                <h3 class="text-dark"><b
                        class="counter">{{count(\App\Models\Order::where('served_by',auth()->user()->id)->where('status',2)->get())}}</b>
                </h3>
                <p class="text-muted">Pending Orde to Serve</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


