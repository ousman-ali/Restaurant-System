<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Show authenticate kitchen cooking history
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myCookingHistory()
    {
        $orders = Order::where('kitchen_id', auth()->user()->id)->get();
        return view('user.kitchen.my-cooking-history', [
            'orders' => $orders
        ]);
    }

    /**
     * Live kitchen using live data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function liveKitchen()
    {
        return view('user.admin.kitchen.live-kitchen');
    }

    /**
     * Live kitchen data for admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLiveKitchenJSON()
    {
        $orders = Order::where('status', '!=', 3)
            ->with('orderDetails')
            ->with('servedBy')
            ->with('kitchen')
            ->orderBy('id','desc')
            ->get();
        return response()->json($orders);
    }

    /**
     * Live kitchen view for waiter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function waiterLiveKitchen()
    {
        return view('user.waiter.live-kitchen');
    }

    /**
     * Waiter live kitchen data
     * @return \Illuminate\Http\JsonResponse
     */
    public function waiterLiveKitchenJSON()
    {
        $orders = Order::where('status', '!=', 3)
            ->where('served_by', auth()->user()->id)
            ->with('orderDetails')
            ->with('servedBy')
            ->with('kitchen')
            ->orderBy('id','desc')
            ->get();
        return response()->json($orders);
    }

    /**
     * View kitchen statistic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function kitchenStat()
    {
        $kitchen = User::where('role',3)->get();
        return view('user.admin.kitchen.kitchen-stat',[
            'kitchen'       =>      $kitchen
        ]);
    }

    /**
     * Redirect to the url via requested query
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function postKitchenStat(Request $request)
    {
        $start_date = str_replace('/','-',$request->get('start') != null ? $request->get('start') : "2017-09-01");
        $end_date = str_replace('/','-',$request->get('end') != null ? $request->get('end') : Carbon::now()->format('Y-m-d'));
        $kitchen = $request->get('kitchen') == 0 ? 0 : $request->get('kitchen');
        return redirect()
            ->to('/kitchen-stat/kitchen='.$kitchen.'/start='.$start_date.'/end='.$end_date);
    }

    /**
     * Show kitchen stat via url data
     * @param $id
     * @param $start_date
     * @param $end_date
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shoeKitchenStat($id,$start_date,$end_date)
    {
        if($id != 0){
            $selected_kitchen = User::findOrFail($id);
            $kitchen = User::where('role',3)->get();
            $orders = Order::where('kitchen_id',$id)
                ->whereBetween('created_at',array($start_date." 00:00:00",$end_date." 00:00:00"))
                ->get();
            return view('user.admin.kitchen.selected-kitchen-stat',[
                'orders'            =>      $orders,
                'selected_kitchen'  =>      $selected_kitchen,
                'kitchen'           =>      $kitchen,
                'start'             =>      $start_date,
                'end'               =>      $end_date
            ]);
        }else{
            $kitchen = User::where('role',3)->get();
            $orders = Order::whereBetween('created_at',array($start_date." 00:00:00",$end_date." 00:00:00"))
                ->get();
            return view('user.admin.kitchen.all-kitchen-stat',[
                'orders'        =>      $orders,
                'kitchen'       =>      $kitchen,
                'start'         =>      $start_date,
                'end'           =>      $end_date
            ]);
        }
    }
}
