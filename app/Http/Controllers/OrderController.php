<?php

namespace App\Http\Controllers;

use App\Events\CompleteCooking;
use App\Events\OrderCancel;
use App\Events\OrderServed;
use App\Events\OrderSubmit;
use App\Events\OrderUpdate;
use App\Events\StartCooking;
use App\Http\Requests\OrderRequest;
use App\Models\OrderDetails;
use App\Models\CookedProduct;
use App\Models\Dish;
use App\Models\DishPrice;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        return view('user.waiter.order.add-order', [
            'tables' => $tables,
            'dishes' => $dishes
        ]);
    }

    /**
     * Show all order (used in admin / shop manager)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allOrder()
    {
        $orders = Order::where('id', '!=', 0)
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
            $order->table_id = $request->table_id;
            $order->served_by = auth()->user()->id;;
            $order->discount = $request->discount_amount;
            $order->payment = $request->payment;
            $order->vat = $request->vat;
            $order->change_amount = $request->change_amount;
            $order->save();

            foreach ($request->items as $item) {
                $orderDetail = new OrderDetails();
                $dishType = DishPrice::findOrFail($item['dish_type_id']);
                $orderDetail->order_id = $order->id;
                $orderDetail->dish_id = $item['dish_id'];
                $orderDetail->dish_type_id = $item['dish_type_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->net_price = $dishType->price;
                $orderDetail->gross_price = $item['quantity'] * $dishType->price;
                if ($orderDetail->save()) {
                    foreach ($dishType->recipes as $recipe) {
                        $cookedProduct = new CookedProduct();
                        $cookedProduct->order_id = $order->id;
                        $cookedProduct->product_id = $recipe->product_id;
                        $cookedProduct->quantity = $recipe->unit_needed * $orderDetail->quantity;
                        $cookedProduct->save();
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
        $order = Order::with('orderDetails')->findOrFail($id);
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
            OrderDetails::where('order_id', $order->id)->delete();
            CookedProduct::where('order_id', $order->id)->delete();

            $order->table_id = $request->table_id;
            $order->served_by = auth()->user()->id;;
            $order->discount = $request->discount_amount;
            $order->payment = $request->payment;
            $order->vat = $request->vat;
            $order->change_amount = $request->change_amount;
            $order->save();

            foreach ($request->items as $item) {
                $orderDetail = new OrderDetails();
                $dishType = DishPrice::findOrFail($item['dish_type_id']);
                $orderDetail->order_id = $order->id;
                $orderDetail->dish_id = $item['dish_id'];
                $orderDetail->dish_type_id = $item['dish_type_id'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->net_price = $dishType->price;
                $orderDetail->gross_price = $item['quantity'] * $dishType->price;
                if ($orderDetail->save()) {
                    foreach ($dishType->recipes as $recipe) {
                        $cookedProduct = new CookedProduct();
                        $cookedProduct->order_id = $order->id;
                        $cookedProduct->product_id = $recipe->product_id;
                        $cookedProduct->quantity = $recipe->unit_needed * $orderDetail->quantity;
                        $cookedProduct->save();
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

    /**
     * Delete order
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        OrderDetails::where('order_id', $order->id)->delete();
        CookedProduct::where('order_id', $order->id)->delete();
        if ($order->delete()) {
            broadcast(new OrderCancel('orderCancel'))->toOthers();
            return redirect()->back()->with('delete_success', 'The order has been deleted successfully');
        }

    }

    /**
     * Show order of authenticate kitchen
     * @return JsonResponse
     */
    public function kitchenOrderToJSON()
    {
        $orders = Order::where('kitchen_id', 0)
            ->orWhere('kitchen_id', auth()->user()->id)
            ->where('status', '!=', 2)
            ->with('orderDetails')
            ->with('servedBy')
            ->orderBy('id', 'desc')
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
        if ($order->status == 0) {
            $order->status = 1;
            $order->kitchen_id = auth()->user()->id;
            $order->save();
        }
        $orders = Order::where('kitchen_id', 0)
            ->orWhere('kitchen_id', auth()->user()->id)
            ->where('status', '!=', 2)
            ->with('orderDetails')
            ->with('servedBy')
            ->orderBy('id', 'desc')
            ->get();
        try {
            broadcast(new StartCooking($order))->toOthers();
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
        $order->save();
        $orders = Order::where('kitchen_id', 0)
            ->orWhere('kitchen_id', auth()->user()->id)
            ->where('status', '!=', 2)
            ->with('orderDetails')
            ->with('servedBy')
            ->orderBy('id', 'desc')
            ->get();
        try {
            broadcast(new CompleteCooking("Complete"))->toOthers();
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
                broadcast(new OrderServed("success"))->toOthers();
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }
            return response()->json('Ok', 200);
        }
    }


}
