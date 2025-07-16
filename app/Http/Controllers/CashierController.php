<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bank;
class CashierController extends Controller
{
   public function myOrder()
    {
        $orders = Order::all();
        $banks = Bank::where('status', 1)->get();
        return view('user.cashier.my-order', [
            'orders' => $orders,
            'banks' => $banks,
        ]);
    }


    public function payOrder(Request $request, $id){
        $order = Order::find($id);
        $order->payment += $request->amount;
        $order->bank_id = $request->payment_method;
        $order->save();
        return response()->json('Ok', 200);
    }

    public function printOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('user.admin.order.print-order-new', [
            'order' => $order
        ]);
    }


    public function printMultipleOrders(Request $request)
    {
        $ids = explode(',', $request->order_ids);
        $orders = Order::with(['servedBy', 'orderPrice.dish', 'orderPrice.dishType', 'orderPrice.readyDish'])
                    ->whereIn('id', $ids)
                    ->get();

        return view('user.admin.order.print-multiple-orders', compact('orders'));
    }
}
