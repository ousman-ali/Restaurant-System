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
use App\Events\SupplierOrderPurchased;
use App\Events\InhouseOrderServed;
use App\Events\SupplierOrderSubmit;
use App\Events\InhouseOrderSubmit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\OrderCancel;
use App\Models\PursesReadyDish;
use App\Models\InhouseOrder;
use App\Models\SupplierOrder;
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
         $supplierOrders = \App\Models\SupplierOrder::where('order_by', auth()->user()->id)
            ->get()
            ->map(function ($order) {
                $order->order_type = 'supplier';
                return $order;
            });

        $inhouseOrders = \App\Models\InhouseOrder::where('order_by', auth()->user()->id)
            ->get()
            ->map(function ($order) {
                $order->order_type = 'inhouse';
                return $order;
            });

        $orders = $supplierOrders->merge($inhouseOrders)->sortByDesc('created_at');
            return view('user.barman.order.my-order', [
                'orders' => $orders
            ]);
        }

        public function getOrderDetails($type, $id)
        {
            switch ($type) {
                case 'inhouse':
                    $order = \App\Models\InhouseOrder::with('orderDetails.readyDish.unit')->findOrFail($id);
                    break;

                case 'supplier':
                    $order = \App\Models\SupplierOrder::with('orderDetails.readyDish.unit')->findOrFail($id);
                    break;

                default:
                    return response()->json(['error' => 'Invalid order type'], 400);
            }

            return response()->json($order);
        }


    public function editOrder($type, $id)
    {
        switch ($type) {
            case 'supplier':
                $order = \App\Models\SupplierOrder::findOrFail($id);
                break;
            case 'inhouse':
                $order = \App\Models\InhouseOrder::findOrFail($id);
                break;
            default:
                abort(404);
        }

        $dishes = ReadyDish::all(); // If shared among types

        return view('user.barman.order.edit-order', [
            'order' => $order,
            'dishes' => $dishes,
            'type' => $type,
        ]);
    }



   

    public function saveOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $lastSOrder = SupplierOrder::latest('id')->first();
            $lastIOrder = InhouseOrder::latest('id')->first();
            $orderSNo = $lastSOrder ? $lastSOrder->order_no + 1 : 1001;
            $orderINo = $lastIOrder ? $lastIOrder->order_no + 1 : 1001;

            $userId = auth()->user()->id;

            $supplierOrder = null;
            if (!empty($request->supplier_items)) {
                $supplierOrder = new SupplierOrder();
                $supplierOrder->order_no = $orderSNo;
                $supplierOrder->order_by = $userId;
                $supplierOrder->status = 0;
                $supplierOrder->save();

                foreach ($request->supplier_items as $item) {
                    OrderDetails::create([
                        'supplier_order_id' => $supplierOrder->id,
                        'ready_dish_id' => $item['ready_dish_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }
                try {
                    broadcast(new SupplierOrderSubmit($supplierOrder, 'new'));
                } catch (\Exception $exception) {
                    Log::error("Broadcasting failed: " . $exception->getMessage());
                }
            }
            $inhouseOrder = null;
            if (!empty($request->inhouse_items)) {
                $inhouseOrder = new InhouseOrder();
                $inhouseOrder->order_no = $orderINo;
                $inhouseOrder->order_by = $userId;
                $inhouseOrder->baker_id =0;
                $inhouseOrder->status = 0;
                $inhouseOrder->save();
                foreach ($request->inhouse_items as $item) {
                    OrderDetails::create([
                        'inhouse_order_id' => $inhouseOrder->id,
                        'ready_dish_id' => $item['ready_dish_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }

                try {
                    broadcast(new InhouseOrderSubmit($inhouseOrder, 'new'));
                } catch (\Exception $exception) {
                    Log::error("Broadcasting failed: " . $exception->getMessage());
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Order saved successfully!',
                'redirect' => route('my-barman.order')
            ], 200);


        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Order save failed: " . $exception->getMessage());
            return response()->json(['error' => 'Order could not be saved.'], 500);
        }
    }


    public function updateOrder(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $userId = auth()->user()->id;

            // Check which type of order we are updating
            if (!empty($request->inhouse_items)) {
                $order = InhouseOrder::findOrFail($id);
                $order->order_by = $userId;
                $order->save();

                // Delete existing details
                OrderDetails::where('inhouse_order_id', $order->id)->delete();

                foreach ($request->inhouse_items as $item) {
                    OrderDetails::create([
                        'inhouse_order_id' => $order->id,
                        'ready_dish_id' => $item['ready_dish_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }

                try {
                    broadcast(new InhouseOrderSubmit($order, 'update'));
                } catch (\Exception $e) {
                    Log::error("Inhouse broadcasting failed: " . $e->getMessage());
                }

            } elseif (!empty($request->supplier_items)) {
                $order = SupplierOrder::findOrFail($id);
                $order->order_by = $userId;
                $order->save();

                // Delete existing details
                OrderDetails::where('supplier_order_id', $order->id)->delete();

                foreach ($request->supplier_items as $item) {
                    OrderDetails::create([
                        'supplier_order_id' => $order->id,
                        'ready_dish_id' => $item['ready_dish_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }

                try {
                    broadcast(new SupplierOrderSubmit($order, 'update'));
                } catch (\Exception $e) {
                    Log::error("Supplier broadcasting failed: " . $e->getMessage());
                }
            } else {
                return response()->json(['error' => 'No items to update'], 400);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Order updated successfully!',
                'redirect' => route('my-barman.order')
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Order update failed: " . $exception->getMessage());
            return response()->json(['error' => 'Order could not be updated.'], 500);
        }
    }


     public function orderServed($id)
        { 
            $order = InhouseOrder::with('orderDetails')->findOrFail($id);
            $order->status = 3;
           
            foreach ($order->orderDetails as $o) {
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
        if ($order->save()) {
            try {
                broadcast(new InhouseOrderServed("success", $order))->toOthers();
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }
            return response()->json('Ok', 200);
        }
        }


    public function readyOrderServed($id)
    { 
        $order = Order::findOrFail($id);
        $order->status = 3;
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
            $order = SupplierOrder::with('orderDetails')->findOrFail($id);
            $order->status = 2;
           
            foreach ($order->orderDetails as $o) {
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

            
        if ($order->save()) {
            try {
                broadcast(new SupplierOrderPurchased("success", $order))->toOthers();
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
            $type = $request->order_type;

            switch ($type) {
                case 'supplier':
                    $order = \App\Models\SupplierOrder::findOrFail($request->order_id);
                    break;
                case 'inhouse':
                    $order = \App\Models\InhouseOrder::findOrFail($request->order_id);
                    break;
                default:
                    abort(404);
            }

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
