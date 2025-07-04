<?php

namespace App\Http\Controllers;

use App\Events\CompleteCooking;
use App\Events\OrderCancel;
use App\Events\OrderServed;
use App\Events\OrderSubmit;
use App\Events\OrderUpdate;
use App\Events\StartCooking;
use App\Events\StartInhouseCooking;
use App\Events\CompleteInhouseCooking;
use App\Http\Requests\OrderRequest;
use App\Models\OrderDetails;
use App\Models\CookedProduct;
use App\Models\Dish;
use App\Models\DishPrice;
use App\Models\Order;
use App\Models\Table;
use App\Models\ReadyDish;
use App\Models\ProducedReadyDish;
use App\Models\PursesReadyDish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

use App\Models\InhouseOrder;
use App\Models\SupplierOrder;
class OrderController extends Controller
{
    /**
     * Show authenticate waiter order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newOrder()
    {
        $tables = Table::all();
        $dishes = Dish::all();
        $readyDishes = ReadyDish::all();
        return view('user.waiter.order.add-order', [
            'tables' => $tables,
            'dishes' => $dishes,
            'readyDishes' => $readyDishes,
        ]);
    }

    /**
     * Show all order (used in admin / shop manager)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allOrder()
    {
        $orders = Order::where('id', '!=', 0)->where('is_ready', false)
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy(function ($data) {
                return $data->created_at->format('M-Y');
            });

        return view('user.admin.order.all-order', [
            'orders' => $orders
        ]);
    }

    /**
     * Non paid order only view for admin and shop manager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nonPaidOrder()
    {
        $orders = Order::where('user_id', 0)
            ->orderBy('id', 'desc')
            ->get();
        return view('user.admin.order.non-paid-order', [
            'orders' => $orders
        ]);
    }

    /**
     * Create new order
     * @param Request $request
     * @return JsonResponse
     */
    public function saveOrder(OrderRequest $request)
    {
        
        
        try {
            DB::beginTransaction();
            

            $lastOrder = Order::latest('id')->first();
            $orderNo = $lastOrder ? $lastOrder->order_no + 1 : 1001;

            $order = new Order();
            $order->order_no = $orderNo;
            $order->table_id = $request->table_id ?? null;
            $order->served_by = auth()->user()->id;;
            $order->discount = $request->discount_amount;
            $order->payment = $request->payment;
            $order->vat = $request->vat;
            $order->bank_id = $request->bank_id;
            $order->change_amount = $request->change_amount;
            $order->save();
            foreach ($request->items as $item) {

                $orderDetail = new OrderDetails();
                if($item['dish_id']){
                    $dishType = DishPrice::findOrFail($item['dish_type_id']);
                }else{
                    $dishType = [];
                }
                $orderDetail->order_id = $order->id;
                $orderDetail->dish_id = $item['dish_id'] ?? null;
                $orderDetail->ready_dish_id = $item['ready_dish_id'] ?? null;
                $orderDetail->dish_type_id = $item['ready_dish_id'] ? null : $item['dish_type_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->net_price = $item['ready_dish_id'] ? $item['net_price'] : $dishType->price;
                $orderDetail->gross_price = $item['ready_dish_id'] ? $item['quantity'] *$item['net_price'] : $item['quantity'] * $dishType->price;
                $orderDetail->from_ready = $item['from_ready'];
                $orderDetail->additional_note = $item['additional_note'];
                $readyDish = ReadyDish::find($item['ready_dish_id']);

                if ($readyDish && $readyDish->source_type == 'supplier') {
                    $orderedQty = $orderDetail->quantity;
                    $totalAvailable = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                        ->where('ready_quantity', '>', 0)
                        ->sum('ready_quantity');
                    if ($totalAvailable < $orderedQty) {
                        $order->delete();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Not enough stock available for this dish.',
                        ], 422); 
                    }
                }

                if ($orderDetail->save()) {
                    if ($readyDish && $readyDish->source_type == 'supplier') {
                        $orderedQty = $orderDetail->quantity;
                        $stocks = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                            ->where('ready_quantity', '>', 0)
                            ->orderBy('created_at')
                            ->get();

                        foreach ($stocks as $stock) {
                            if ($orderedQty <= 0) break;

                            $deductQty = min($stock->ready_quantity, $orderedQty);
                            $stock->ready_quantity -= $deductQty;
                            $stock->save();

                            $orderedQty -= $deductQty;
                        }
                    }elseif($readyDish && $readyDish->source_type == 'inhouse'){
                        $orderedQty = $orderDetail->quantity;
                        $stocks = ProducedReadyDish::where('ready_dish_id', $readyDish->id)
                            ->where('ready_quantity', '>', 0)
                            ->orderBy('created_at')
                            ->get();

                        foreach ($stocks as $stock) {
                            if ($orderedQty <= 0) break;

                            $deductQty = min($stock->ready_quantity, $orderedQty);
                            $stock->ready_quantity -= $deductQty;
                            $stock->save();

                            $orderedQty -= $deductQty;
                        }
                    }
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

    /**
     * Edit order
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        $dishes = Dish::all();
        return view('user.waiter.order.edit-order', [
            'order' => $order,
            'dishes' => $dishes
        ]);
    }

    /**
     * View order details
     * @param $id
     * @return JsonResponse|mixed
     */
    public function getOrderDetails($id)
    {
        
        $order = Order::with('orderDetails.readyDish')->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Order of authenticate user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myOrder()
    {
        $orders = Order::where('served_by', auth()->user()->id)->get();
        return view('user.waiter.order.my-order', [
            'orders' => $orders
        ]);
    }

    /**
     * Update order
     * @param OrderRequest $request
     * @param $id
     * @return JsonResponse
     */
public function updateOrder(OrderRequest $request, $id)
{
    try {
        DB::beginTransaction();

        $order = Order::findOrFail($id);

        // 🔁 STEP 1: Restore stock for old supplier dishes before deleting
        $oldOrderDetails = OrderDetails::where('order_id', $order->id)->get();

        foreach ($oldOrderDetails as $detail) {
            $readyDish = ReadyDish::find($detail->ready_dish_id);

            if ($readyDish && $readyDish->source_type === 'supplier') {
                $remainingQty = $detail->quantity;

                $stocks = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                    ->orderByDesc('created_at') // refill into most recent stock first
                    ->get();

                foreach ($stocks as $stock) {
                    if ($remainingQty <= 0) break;

                    $maxRefill = ($stock->total_price / $stock->unit_price) - $stock->quantity;
                    $refill = min($remainingQty, $maxRefill);
                    $stock->quantity += $refill;
                    $stock->save();

                    $remainingQty -= $refill;
                }
            }
        }

        // 🧹 STEP 2: Clean old records
        OrderDetails::where('order_id', $order->id)->delete();
        CookedProduct::where('order_id', $order->id)->delete();

        // ✍️ STEP 3: Update order data
        $order->table_id = $request->table_id;
        $order->served_by = auth()->user()->id;
        $order->discount = $request->discount_amount;
        $order->payment = $request->payment;
        $order->vat = $request->vat;
        $order->change_amount = $request->change_amount;
        $order->bank_id = $request->bank_id;
        $order->save();

        // 🧾 STEP 4: Save new items & manage stock
        foreach ($request->items as $item) {
            $orderDetail = new OrderDetails();
            $dishType = !empty($item['dish_id']) ? DishPrice::findOrFail($item['dish_type_id']) : null;

            $orderDetail->order_id = $order->id;
            $orderDetail->dish_id = $item['dish_id'] ?? null;
            $orderDetail->ready_dish_id = $item['ready_dish_id'] ?? null;
            $orderDetail->dish_type_id = $item['dish_type_id'];
            $orderDetail->quantity = $item['quantity'];
            $orderDetail->from_ready = $item['from_ready'];
            $orderDetail->additional_note = $item['additional_note'];
            if (!empty($item['dish_id']) && $dishType) {
                $orderDetail->net_price = $dishType->price;
                $orderDetail->gross_price = $item['quantity'] * $dishType->price;
            } else {
                $orderDetail->net_price = $item['net_price'];
                $orderDetail->gross_price = $item['net_price'] * $item['quantity'];
            }

            $readyDish = ReadyDish::find($item['ready_dish_id']);

            if ($readyDish && $readyDish->source_type == 'supplier') {
                $orderedQty = $orderDetail->quantity;

                $totalAvailable = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                    ->where('ready_quantity', '>', 0)
                    ->sum('ready_quantity');

                if ($totalAvailable < $orderedQty) {
                    $order->delete();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not enough stock available for this dish.',
                    ], 422);
                }
            }

            if ($orderDetail->save()) {
                if ($readyDish && $readyDish->source_type == 'supplier') {
                    $orderedQty = $orderDetail->quantity;

                    $stocks = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                        ->where('ready_quantity', '>', 0)
                        ->orderBy('created_at')
                        ->get();

                    foreach ($stocks as $stock) {
                        if ($orderedQty <= 0) break;

                        $deductQty = min($stock->ready_quantity, $orderedQty);
                        $stock->ready_quantity -= $deductQty;
                        $stock->save();

                        $orderedQty -= $deductQty;
                    }
                }
            } else {
                break;
            }
        }

        DB::commit();

        try {
            broadcast(new OrderSubmit($order, 'update'));
        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }

        return response()->json($order, 200);

    } catch (\Exception $exception) {
        DB::rollBack();
        throw $exception;
    }
}


    /**
     * Print order if payment is complete
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('user.admin.order.print-order-new', [
            'order' => $order
        ]);
    }


    // app/Http/Controllers/OrderController.php
public function printMultipleOrders(Request $request)
{
    $ids = explode(',', $request->order_ids);
    $orders = Order::with(['servedBy', 'orderPrice.dish', 'orderPrice.dishType', 'orderPrice.readyDish'])
                   ->whereIn('id', $ids)
                   ->get();

    return view('user.admin.order.print-multiple-orders', compact('orders'));
}



    /**
     * Mark order (if order marked, no one can edit/delete this order)
     * @param $id
     * @return JsonResponse
     */
    public function markOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->user_id = auth()->user()->id;
        if ($order->save()) {
            return response()->json('Ok', 200);
        }

    }

    public function payOrder(Request $request, $id){
        $order = Order::find($id);
        $order->payment += $request->amount;
        $order->save();
        return response()->json('Ok', 200);
    }

    /**
     * Delete order
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
  

  

    public function deleteOrder(Request $request)
        {
            $order = Order::with('orderDetails.readyDish')->findOrFail($request->order_id);

            
            // Loop through each item in the order and restore stock
            foreach ($order->orderDetails as $detail) {
                $readyDish = $detail->readyDish;

                if ($readyDish && $readyDish->source_type === 'supplier') {
                    $remainingQty = $detail->quantity;

                    // Try to add quantity back to existing PursesReadyDish records by oldest first
                    $stocks = PursesReadyDish::where('ready_dish_id', $readyDish->id)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach ($stocks as $stock) {
                        if ($remainingQty <= 0) break;

                        // Just add back the quantity to this record
                        $stock->ready_quantity += $remainingQty;
                        $stock->save();

                        // Done restoring this detail
                        break;
                    }

                } elseif ($readyDish && $readyDish->source_type === 'inhouse') {
                    $remainingQty = $detail->quantity;

                    // Add quantity back to oldest ProducedReadyDish record
                    $stocks = ProducedReadyDish::where('ready_dish_id', $readyDish->id)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach ($stocks as $stock) {
                        if ($remainingQty <= 0) break;

                        $stock->ready_quantity += $remainingQty;
                        $stock->save();

                        break;
                    }
                }
            }

            // Delete order details and cooked products
            OrderDetails::where('order_id', $order->id)->delete();
            CookedProduct::where('order_id', $order->id)->delete();

            // Broadcast cancel event
            broadcast(new OrderCancel('orderCancel', $order))->toOthers();

            // Delete the order
            $order->delete();

            Session::flash('delete_success', 'The order has been deleted successfully');
            return back();
        }




    /**
     * Show order of authenticate kitchen
     * @return JsonResponse
     */
    public function kitchenOrderToJSON()
    {
        $orders = Order::where(function ($query) {
            $query->where('kitchen_id', 0)
                ->orWhere('kitchen_id', auth()->user()->id);
        })
        ->where('status', '!=', 2)
        ->where('status', '!=', 3)
        ->whereDoesntHave('orderDetails', function ($q) {
            $q->where(function ($inner) {
                $inner->where('from_ready', true)
                    ->orWhereHas('dish', function ($dishQuery) {
                        $dishQuery->where('order_to', '!=', 'kitchen');
                    });
            });
        })
        ->with([
            'orderDetails' => function ($q) {
                $q->where('from_ready', false)
                ->whereHas('dish', function ($dishQuery) {
                    $dishQuery->where('order_to', 'kitchen');
                })
                ->with('dish');
            },
            'servedBy',
            'table',
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($orders);
    }

    public function bakerOrderToJSON()
        {
            
            $orders = InhouseOrder::where(function ($query) {
                $query->where('baker_id', 0)
                        ->orWhere('baker_id', auth()->user()->id);
                })
                ->where('status', '!=', 2)
                ->where('status', '!=', 3)
                ->with([
                    'orderDetails.readyDish.unit',
                    'orderBy'
                ])
                ->latest()
                ->get();
            return response()->json($orders);
        }


    /**
     * Kitchen takes the dish to cook
     * @param $id
     * @return JsonResponse
     */
    public function kitchenStartCooking($id)
    {

        $order = Order::findOrfail($id);
        foreach($order->orderDetails as $orderD){
            $dish_type_id = $orderD->dish_type_id;
            $dishType = DishPrice::find($dish_type_id);
            foreach ($dishType->recipes as $recipe) {
                $cookedProduct = new CookedProduct();
                $cookedProduct->order_id = $order->id;
                $cookedProduct->product_id = $recipe->product_id;
                $cookedProduct->quantity = $recipe->unit_needed * $orderD->quantity;
                $cookedProduct->save();
            }
        }
        if ($order->status == 0) {
            $order->status = 1;
            $order->kitchen_id = auth()->user()->id;
            $order->cook_start_time = now();
            $order->save();
        }
        $orders = Order::where(function ($query) {
            $query->where('kitchen_id', 0)
                ->orWhere('kitchen_id', auth()->user()->id);
        })
        ->where('status', '!=', 3)
        ->where('status', '!=', 2)
        ->whereDoesntHave('orderDetails', function ($q) {
            $q->where(function ($inner) {
                $inner->where('from_ready', true)
                    ->orWhereHas('dish', function ($dishQuery) {
                        $dishQuery->where('order_to', '!=', 'kitchen');
                    });
            });
        })
        ->with([
            'orderDetails' => function ($q) {
                $q->where('from_ready', false)
                ->whereHas('dish', function ($dishQuery) {
                    $dishQuery->where('order_to', 'kitchen');
                })
                ->with('dish');
            },
            'servedBy',
            'table',
        ])
        ->orderBy('id', 'desc')
        ->get();

        try {
            broadcast(new StartCooking($order))->toOthers();
        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }
        return response()->json($orders);

    }

    public function bakerStartCooking($id)
    {
        $order = InhouseOrder::findOrfail($id);
        foreach($order->orderDetails as $orderD){
            $ready_dish_id = $orderD->ready_dish_id;
            $ready_dish = ReadyDish::find($ready_dish_id);
            foreach ($ready_dish->dishRecipes as $recipe) {
                $cookedProduct = new CookedProduct();
                $cookedProduct->inhouse_order_id = $order->id;
                $cookedProduct->product_id = $recipe->product_id;
                $cookedProduct->quantity = $recipe->unit_needed * $orderD->quantity;
                $cookedProduct->save();
            }
        }
        if ($order->status == 0) {
            $order->status = 1;
            $order->baker_id = auth()->user()->id;
            $order->cook_start_at = now();
            $order->save();
        }
        
        $orders = InhouseOrder::where('status', '!=', 2)
            ->where('status', '!=', 3)
            ->with('orderDetails.readyDish.unit')
            ->with('orderBy')
            ->latest()
            ->get();
        try {
            broadcast(new StartInhouseCooking($order))->toOthers();
        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }
        return response()->json($orders);

    }

    /**
     * Kitchen cooked the order
     * @param $id
     * @return JsonResponse
     */
    public function kitchenCompleteCooking($id)
    {
        $order = Order::findOrfail($id);
        $order->status = 2;
        $order->cook_complete_time = now();
        $order->save();
        $orders = Order::where(function ($query) {
            $query->where('kitchen_id', 0)
                ->orWhere('kitchen_id', auth()->user()->id);
        })
        ->where('status', '!=', 2)
        ->where('status', '!=', 3)
        ->whereDoesntHave('orderDetails', function ($q) {
            $q->where(function ($inner) {
                $inner->where('from_ready', true)
                    ->orWhereHas('dish', function ($dishQuery) {
                        $dishQuery->where('order_to', '!=', 'kitchen');
                    });
            });
        })
        ->with([
            'orderDetails' => function ($q) {
                $q->where('from_ready', false)
                ->whereHas('dish', function ($dishQuery) {
                    $dishQuery->where('order_to', 'kitchen');
                })
                ->with('dish');
            },
            'servedBy',
            'table',
        ])
        ->orderBy('id', 'desc')
        ->get();

        try {
            broadcast(new CompleteCooking($order))->toOthers();
        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }
        return response()->json($orders);
    }

    public function bakerCompleteCooking($id)
    {
        
        $order = InhouseOrder::with('orderDetails.readyDish')->findOrFail($id);
            foreach($order->orderDetails as $o){
                    $preparedDish = new ProducedReadyDish();
                    $preparedDish->ready_dish_id = $o->ready_dish_id;
                    $preparedDish->order_detail_id =$o->id;
                    $preparedDish->pending_quantity = $o->quantity;
                    $preparedDish->user_id = auth()->user()->id;
                    $preparedDish->save();
                }
            $order->status = 2;
            $order->cook_complete_at = now();
            $order->save();
        

        $orders = InhouseOrder::where('status', '!=', 2)
            ->where('status', '!=', 3)
            ->with('orderDetails.readyDish')
            ->with('orderBy')
            ->latest()
            ->get();

        try {
            broadcast(new CompleteInhouseCooking($order))->toOthers();
        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }
        return response()->json($orders);
    }

    /**
     * Waiter served the order
     * @param $id
     * @return JsonResponse
     */
    public function orderServed($id)
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


}
