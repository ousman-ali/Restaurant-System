<?php

namespace App\Http\Controllers;

use App\Models\OfficeExpanse;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountantController extends Controller
{
    /**
     * Show expanse form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addExpanse()
    {
        return view('user.admin.accountant.new-expanse',[
            'account_menu'  =>  true
        ]);
    }

    /**
     * User can see the account summary
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function summary()
    {
        return view('user.admin.accountant.summary');
    }

    /**
     * User can save the expanse amount
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveExpanse(Request $request)
    {
        $expanse = new OfficeExpanse();
        $expanse->title = $request->get('title');
        $expanse->date = Carbon::parse($request->get('date'))->format('Y-m-d');
        $expanse->expanse = $request->get('expanse');
        $expanse->user_id = auth()->user()->id;
        if($expanse->save()){
            return response()->json('Ok',200);
        }
    }

    /**
     * User can see the restaurant all expanse by month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allExpanse()
    {
        $office_expanse = OfficeExpanse::all();
        $expanses = OfficeExpanse::where('id','!=',0)
            ->get()
            ->groupBy(function ($data){
                return $data->created_at->format('M-Y');
            });
        return view('user.admin.accountant.all-expanse',[
            'office_expanse'    =>  $office_expanse,
            'account_menu'  =>  true,
            'expenses'      =>      $expanses
        ]);
    }

    /**
     * User can see the restaurant all income by month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allIncome()
    {
        $total_earn = Order::all();
        $orders = Order::where('id','!=',0)
            ->orderBy('id','desc')
            ->get()
            ->groupBy(function ($data){
                return $data->created_at->format('M-Y');
            });
        return view('user.admin.accountant.all-income',[
            'orders'        =>  $orders,
            'total_earn'    =>  $total_earn
        ]);
    }

    public function editExpanse($id)
    {
        $expanse = OfficeExpanse::findOrFail($id);
        return view('user.admin.accountant.edit-expanse',[
            'expanse'       =>      $expanse
        ]);
    }

    public function updateExpanse(Request $request,$id)
    {
        $expanse =  OfficeExpanse::findOrFail($id);
        $expanse->title = $request->get('title');
        $expanse->date = Carbon::parse($request->get('date'))->format('Y-m-d');
        $expanse->expanse = $request->get('expanse');
        $expanse->user_id = auth()->user()->id;
        if($expanse->save()){
            return response()->json('Ok',200);
        }
    }

    public function deleteExpanse($id)
    {
        $expanse =  OfficeExpanse::findOrFail($id);
        if($expanse){
            $expanse->delete();
            return redirect()->back()->with('delete_success','Expanse has been deleted');
        }
    }
}
