<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bank;
class CashierController extends Controller
{
   public function myOrder()
    {
        $order = Order::latest()->get();
        $banks = Bank::where('status', 1)->get();

        $orders = $order->map(function ($order) {
            $order->bgColor = $order->code ? $this->stringToColorCode($order->code) : null;
            return $order;
        });
        $banks = Bank::where('status', 1)->get();
        return view('user.cashier.my-order', [
            'orders' => $orders,
            'banks' => $banks,
        ]);
    }

    function stringToColorCode($string)
    {
        return '#' . substr(md5($string), 0, 6); // Generates hex color from string
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
