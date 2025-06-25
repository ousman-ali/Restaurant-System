<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadyDish;
use App\Models\Table;
use App\Http\Requests\OrderRequest;
use App\Models\OrderDetails;
use App\Models\CookedProduct;
use Illuminate\Http\JsonResponse;
use App\Models\ProducedReadyDish;
use App\Models\Order;
use App\Events\OrderServed;
use App\Events\OrderSubmit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\OrderCancel;
use App\Models\PursesReadyDish;
use Session;
class ReadyDishOrderController extends Controller
{
    public function newOrder()
    {
        $tables = Table::all();
        // $dishes = ReadyDish::all();
        $dishes = ReadyDish::with(['dishImages', 'dishRecipes'])
    ->where(function ($query) {
        // In-house: no produced batches or total ready_quantity = 0
        $query->where('source_type', 'inhouse')
              ->where(function ($q) {
                  $q->doesntHave('producedBatches')
                    ->orWhereHas('producedBatches', function ($batch) {
                        $batch->where('ready_quantity', '>', 0);
                    }, '<', 1); // Means: fewer than 1 batch has > 0 quantity
              });
    })
    ->orWhere(function ($query) {
        // Supplier: no purchased batches or total quantity = 0
        $query->where('source_type', 'supplier')
              ->where(function ($q) {
                  $q->doesntHave('purchasedBatches')
                    ->orWhereHas('purchasedBatches', function ($batch) {
                        $batch->where('ready_quantity', '>', 0);
                    }, '<', 1); // Means: fewer than 1 batch has > 0 quantity
              });
    })
    ->get();

        return view('user.barman.order.add-order', [
            'tables' => $tables,
            'dishes' => $dishes 
        ]);
    }

     public function myOrder()
        {
            $orders = Order::where('served_by', auth()->user()->id)->where('is_ready', true)->get();
            return view('user.barman.order.my-order', [
                'orders' => $orders
            ]);
        }

    public function getOrderDetails($id)
    {
        $order = Order::with('orderDetails.readyDish')->findOrFail($id);
        return response()->json($order);
    }

    public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        $dishes = ReadyDish::all();
        return view('user.barman.order.edit-order', [
            'order' => $order,
            'dishes' => $dishes
        ]);
    }


    public function saveOrder(OrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $lastOrder = Order::latest('id')->first();
            $orderNo = $lastOrder ? $lastOrder->order_no + 1 : 1001;

            $order = new Order();
            $order->order_no = $orderNo;
            $order->served_by = auth()->user()->id;;
            $order->discount = $request->discount_amount;
            $order->payment = $request->payment;
            $order->vat = $request->vat;
            $order->is_ready = true;
            $order->change_amount = $request->change_amount;
            $order->save();

            foreach ($request->items as $item) {
                $orderDetail = new OrderDetails();
                $dish = ReadyDish::findOrFail($item['ready_dish_id']);
                $orderDetail->order_id = $order->id;
                $orderDetail->ready_dish_id = $item['ready_dish_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->net_price = $item['net_price'];
                $orderDetail->gross_price = $item['quantity'] * $item['net_price'];
                if ($orderDetail->save()) {
                    
                } else {
                    break;
                }
            }

            DB::commit();

            try {
                broadcast(new OrderSubmit($order));
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }

            return response()->json($order, 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateOrder(OrderRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            OrderDetails::where('order_id', $order->id)->delete();
            CookedProduct::where('order_id', $order->id)->delete();

            $order->served_by = auth()->user()->id;;
            $order->discount = $request->discount_amount;
            $order->payment = $request->payment;
            $order->vat = $request->vat;
            $order->change_amount = $request->change_amount;
            $order->save();

            foreach ($request->items as $item) {
                $orderDetail = new OrderDetails();
                $dish = ReadyDish::findOrFail($item['ready_dish_id']);
                $orderDetail->order_id = $order->id;
                $orderDetail->ready_dish_id = $item['ready_dish_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->net_price = $item['net_price'];
                $orderDetail->gross_price = $item['quantity'] * $item['net_price'];
                if ($orderDetail->save()) {
                    // if($dish->source_type == 'inhouse'){
                    //     foreach ($dishType->recipes as $recipe) {
                    //     $cookedProduct = new CookedProduct();
                    //     $cookedProduct->order_id = $order->id;
                    //     $cookedProduct->product_id = $recipe->product_id;
                    //     $cookedProduct->quantity = $recipe->unit_needed * $orderDetail->quantity;
                    //     $cookedProduct->save();
                    // }
                    // }
                    
                } else {
                    break;
                }
            }

            DB::commit();

            try {
                // broadcast(new OrderSubmit($order, 'update'));
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }

            return response()->json($order, 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

     public function orderServed($id)
        { 
            $order = Order::with('orderDetails')->findOrFail($id);
            $order->status = 3;
           
            foreach ($order->orderDetails as $o) {
            if ($o->readyDish->source_type == 'inhouse') {
                $remainingQty = $o->quantity;
                $producedDishes = ProducedReadyDish::where('ready_dish_id', $o->ready_dish_id)
                    ->where('order_detail_id', $o->id)
                    ->where('pending_quantity', '>', 0)
                    ->orderBy('created_at')
                    ->get();

                foreach ($producedDishes as $producedDish) {
                    if ($remainingQty <= 0) break;

                    $transferQty = min($producedDish->pending_quantity, $remainingQty);
                    $producedDish->pending_quantity -= $transferQty;
                    $producedDish->ready_quantity += $transferQty;
                    $producedDish->save();
                    $remainingQty -= $transferQty;
                }
                if ($remainingQty > 0) {
                    Log::warning("Not enough pending stock for dish ID {$o->ready_dish_id}");
                }
            }
        }

            
        if ($order->save()) {
            try {
                broadcast(new OrderServed("success", $order))->toOthers();
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }
            return response()->json('Ok', 200);
        }
        }

        public function orderConfirm($id)
        {
            $order = Order::with('orderDetails')->findOrFail($id);
            $order->status = 5;
           
            foreach ($order->orderDetails as $o) {
            if ($o->readyDish->source_type == 'supplier') {
                $remainingQty = $o->quantity;
                $purseDishes = PursesReadyDish::where('ready_dish_id', $o->ready_dish_id)
                    ->where('pending_quantity', '>', 0)
                    ->orderBy('created_at')
                    ->get(); 

                foreach ($purseDishes as $purseDish) {
                    if ($remainingQty <= 0) break;

                    $transferQty = min($purseDish->pending_quantity, $remainingQty);
                    $purseDish->pending_quantity -= $transferQty;
                    $purseDish->ready_quantity += $transferQty;
                    $purseDish->save();
                    $remainingQty -= $transferQty;
                }
                if ($remainingQty > 0) {
                    Log::warning("Not enough pending stock for dish ID {$o->ready_dish_id}");
                }
            }
        }

            
        if ($order->save()) {
            try {
                broadcast(new OrderServed("success", $order))->toOthers();
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }
            return response()->json('Ok', 200);
        }
        }


    public function printOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('user.admin.order.print-order-new', [
            'order' => $order
        ]);
    }

    public function deleteOrder(Request $request)
        {
            $order = Order::findOrFail($request->order_id);
            OrderDetails::where('order_id', $order->id)->delete();
            CookedProduct::where('order_id', $order->id)->delete();
            broadcast(new OrderCancel('orderCancel', $order))->toOthers();
            $order->delete();
            Session::flash('delete_success', 'The order has been deleted successfully');
            return back();
        }

    public function deleteSupplierOrder(Request $request)
        {
            $orderId = $request->order_id;
            $orderDetails = $request->orderD;
            $orderItem = $orderDetails[0];
            $order = Order::with('orderDetails')->findOrFail($orderId);
            $orderDetail = OrderDetails::findOrFail($orderItem['id']);
            if ($order->orderDetails->count() > 1) {
                $orderDetail->delete();
            } else {
                $orderDetail->delete();
                broadcast(new OrderCancel('orderCancel', $order))->toOthers();
                $order->delete();
            }

            return response()->json(['message' => 'Deleted successfully']);
        }

        public function deleteInhouseOrder(Request $request){
            $orderId = $request->order_id;
            $orderDetails = $request->orderD;
            $orderItem = $orderDetails[0];
            $order = Order::with('orderDetails')->findOrFail($orderId);
            $orderDetail = OrderDetails::findOrFail($orderItem['id']);
            if ($order->orderDetails->count() > 1) {
                $orderDetail->delete();
            } else {
                $orderDetail->delete();
                broadcast(new OrderCancel('orderCancel', $order))->toOthers();
                $order->delete();
            }

            return response()->json(['message' => 'Deleted successfully']); 
        }


}
