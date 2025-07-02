<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\MaterialRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            ->with('table')
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


    public function allStock()
    {
        $stockProducts = Product::withSum('purses', 'quantity')->where('dish_type', 'normal')->get();
        $data['products'] = $stockProducts;
        return view('user.kitchen.materials.stock-status', $data);
    }

    public function lowStock(){
        $products = Product::withSum('purses', 'quantity')->where('dish_type', 'normal')->get();
        $lowStockProducts = $products->filter(function ($product) {
            $stock = $product->purses_sum_quantity ?? 0;
            return $stock <= $product->minimum_stock_threshold;
        })->map(function ($product) {
            $product->stock = $product->purses_sum_quantity ?? 0;
            return $product;
        });
        $data['products'] = $lowStockProducts;
        return view('user.kitchen.materials.material-request', $data);
    }

    public function requestStock(Request $request){
        $request->validate([
            'reference_id' => 'required|integer',
            'requested_quantity' => 'required|numeric|min:1',
            'type' => 'required|in:recipe_product,ready_dish',
        ]);

        MaterialRequest::create([
            'reference_id' => $request->reference_id,
            'type' => $request->type,
            'requested_by' => auth()->user()->role(),
            'requested_quantity' => $request->requested_quantity,
            'status' => 'pending',
        ]);
        try {
        $product = Product::find($request->reference_id);
        event(new \App\Events\LowStockAlertEvent([
            'type' => $request->type,
            'user' =>auth()->user()->name,
            'requested' => $request->requested_quantity,
            'name' => $product->product_name ?? 'Unknown',
            'current_stock' => $product->purses_sum_quantity ?? 0,
            'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
            'time' => now(),
        ]));

        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }

        return back()->with('success', 'Request submitted successfully.');
    }


    public function getUnitOfProduct($id)
    {
        $product = Product::where('id',$id)->with('unit')->first();
        return response()->json($product);
    }
}
