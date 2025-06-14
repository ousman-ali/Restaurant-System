<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    /**
     * show waiter statistic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function waiterStat()
    {
        $waiter = User::where('role', 4)->get();
        return view('user.admin.waiter.waiter-stat', [
            'waiter' => $waiter
        ]);
    }

    /**
     * Show waiter statistic using requested query
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postWaiterStat(Request $request)
    {
        $start_date = str_replace('/', '-', $request->get('start') != null ? $request->get('start') : "2017-09-01");
        $end_date = str_replace('/', '-', $request->get('end') != null ? $request->get('end') : Carbon::now()->format('Y-m-d'));
        $kitchen = $request->get('kitchen') == 0 ? 0 : $request->get('kitchen');
        return redirect()
            ->to('/waiter-stat/waiter=' . $kitchen . '/start=' . $start_date . '/end=' . $end_date);
    }

    /**
     * Show waiter statistic using url query
     * @param $id
     * @param $start_date
     * @param $end_date
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWaiterStat($id, $start_date, $end_date)
    {
        if ($id == 0) {
            $waiter = User::where('role', 4)->get();
            $orders = Order::whereBetween('created_at', array($start_date . " 00:00:00", $end_date . " 00:00:00"))
                ->get();
            return view('user.admin.waiter.waiter-stat-all', [
                'waiter' => $waiter,
                'orders' => $orders,
                'start' => $start_date,
                'end' => $end_date
            ]);
        } else {
            $waiter = User::where('role', 4)->get();
            $selected_waiter = User::findOrFail($id);
            $orders = Order::where('served_by', $selected_waiter->id)
                ->whereBetween('created_at', array($start_date . " 00:00:00", $end_date . " 00:00:00"))
                ->get();
            return view('user.admin.waiter.waiter-stat-selected',[
                'waiter' => $waiter,
                'selected_waiter'   =>  $selected_waiter,
                'orders' => $orders,
                'start' => $start_date,
                'end' => $end_date
            ]);
        }
    }
}
