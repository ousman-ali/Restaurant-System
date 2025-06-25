<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReadyDish;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\PursesReadyDish;
use App\Events\OrderServed;
use App\Models\Purse;
use App\Models\PursesPayment;
use Illuminate\Support\Facades\Log;
class BakerController extends Controller
{
   

    public function barmanLiveBaker()
    {
        return view('user.barman.live-bakery');
    }

    // public function barmanLiveBakerJSON()
    // {
    //     $orders = Order::where('status', '!=', 3)
    //         ->where('served_by', auth()->user()->id)
    //         ->with('orderDetails.readyDish')
    //         ->with('servedBy')
    //         ->with('kitchen')
    //         ->where('is_ready', true)
    //         ->orderBy('id','desc')
    //         ->get();
    //     return response()->json($orders);
    // }

    public function barmanLiveBakerJSON()
{
    // 1. Inhouse orders for the barman (baker)
    $inhouseOrders = Order::where('status', '!=', 3)
        ->where('served_by', auth()->user()->id)
        ->where('is_ready', true)
        ->whereHas('orderDetails.readyDish', fn($q) =>
            $q->where('source_type', 'inhouse')
        )
        ->with([
            'orderDetails' => fn($q) =>
                $q->whereHas('readyDish', fn($q2) =>
                    $q2->where('source_type', 'inhouse')
                ),
            'orderDetails.readyDish',
            'servedBy',
            'baker',
        ])
        ->orderBy('id','desc')
        ->get();

    // 2. Supplier orders for admin
    $supplierOrders = Order::where('status', '!=', 3)
    ->where('status', '!=', 5)
        ->where('served_by', auth()->user()->id)
        ->where('is_ready', true)
        ->whereHas('orderDetails.readyDish', fn($q) =>
            $q->where('source_type', 'supplier')
        )
        ->with([
            'orderDetails' => fn($q) =>
                $q->whereHas('readyDish', fn($q2) =>
                    $q2->where('source_type', 'supplier')
                ),
            'orderDetails.readyDish',
            'servedBy',
            'kitchen',
        ])
        ->orderBy('id','desc')
        ->get();

    return response()->json([
        'orders' => $inhouseOrders,         // Inhouse
        'supplierOrders' => $supplierOrders // Supplier
    ]);
}


    public function myCookingHistory()
    {
        $orders = Order::where('baker_id', auth()->user()->id)->where('is_ready', true)->get();
        return view('user.baker.my-cooking-history', [
            'orders' => $orders
        ]);
    }

    public function liveBarman()
    {
        
        return view('user.admin.barman.live-barman');
    }

    /**
     * Live kitchen data for admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLiveBarmanJSON()
    {
        $orders = Order::where('status', '!=', 4)
            ->where('status', '!=', 5)
            ->with('orderDetails.readyDish')
            ->with('servedBy')
            ->where('is_ready', true)
            ->whereHas('orderDetails.readyDish', fn($q) => 
        $q->where('source_type', 'supplier')
            )
            // eager-load only the in-house orderDetails
            ->with([
                'orderDetails' => function($q) {
                    $q->whereHas('readyDish', fn($q2) =>
                        $q2->where('source_type', 'supplier')
                    );
                },
                'orderDetails.readyDish',
                'servedBy'
            ])
            ->orderBy('id', 'desc')
            ->get();
            
        return response()->json($orders);
    }

    // Controller
    public function getRecipeForm($id)
    {
        
        $st = request()->get('st');
        $orderId = request()->get('order_id'); 
        $dish = ReadyDish::find($id);
        $dishs = ReadyDish::all();
        $suppliers = Supplier::where('status',1)->get();

        return view('user.admin.barman._ready_recipe_form', compact('dishs', 'suppliers', 'dish', 'st', 'orderId'))->render();
    }

    public function savePurses(Request $request)
    {
        
       
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'order_id' => 'required|exists:orders,id',
        'items' => 'required|array',
        'items.*.dishId' => 'required|integer',
        'items.*.quantity' => 'required|numeric',
        'items.*.unitPrice' => 'required|numeric',
        'items.*.gross' => 'required|numeric',

    ]);

   $items = $request->items;
    


    if (empty($items)) {
        return response()->json('No items to save', 422);
    }

    $purses = new Purse();
    $purses->purses_id = rand(1000, 5000) . auth()->user()->id;
    $purses->supplier_id = $request->supplier_id;
    $purses->purses_value = 10; 
    $purses->is_payed = 0;
    $purses->user_id = auth()->user()->id;

    if ($purses->save()) {
        try {
            foreach ($items as $item) {
                // Validate required item fields
                
                if (!isset($item['dishId'], $item['quantity'], $item['unitPrice'], $item['gross'])) {
                    
                    continue;
                }

                $pursesProduct = new PursesReadyDish();
                $pursesProduct->purse_id = $purses->id;
                $pursesProduct->ready_dish_id = $item['dishId'];
                $pursesProduct->pending_quantity = $item['quantity'];
                $pursesProduct->unit_price = $item['unitPrice'];
                $pursesProduct->total_price = $item['gross'];
                $pursesProduct->save();
            }

            // Mark the order as complete
            $order = Order::findOrFail($request->order_id);
            $order->status = 4;
            $order->purchase_time = now();
            $order->save();

            broadcast(new OrderServed("success", $order))->toOthers();

            // Handle payment if any
            if ($request->filled('payment') && $request->payment > 0) {
                $pursesPayment = new PursesPayment();
                $pursesPayment->payment_amount = $request->payment;
                $pursesPayment->supplier_id = $purses->supplier_id;
                $pursesPayment->purse_id = $purses->id;
                $pursesPayment->user_id = auth()->user()->id;
                $pursesPayment->save();
            }

            return back();

        } catch (\Exception $e) {
            // Clean up if anything failed
            PursesReadyDish::where('purse_id', $purses->id)->delete();
            $purses->delete();

            Log::error("Save purses failed: " . $e->getMessage());
            return response()->json('Internal Server Error', 500);
        }

    } else {
        return response()->json('Internal Server Error', 419);
    }
    }

public function getRecipeFormAll($orderId)
{
    $order = Order::with([
        'orderDetails' => function ($q) {
            $q->whereHas('readyDish', function ($q2) {
                $q2->where('source_type', 'supplier');
            });
        },
        'orderDetails.readyDish'
    ])->findOrFail($orderId);

    $dishs = ReadyDish::where('source_type', 'supplier')->get();
    $suppliers = Supplier::where('status', 1)->get();

    return view('user.admin.barman._ready_recipe_form', compact('order', 'dishs', 'suppliers'))->render();
}



}
