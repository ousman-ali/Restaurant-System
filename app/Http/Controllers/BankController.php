<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
class BankController extends Controller
{
    public function addBank()
    {
        return view('user.admin.bank.add-bank');
    }

    /**
     * Show all unit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allBank()
    {
        $bank = Bank::all();
        return view('user.admin.bank.all-bank',[
            'banks'     =>      $bank
        ]);
    }

    /**
     * Edit unit by id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editBank($id)
    {
        $bank = Bank::findOrFail($id);
        return view('user.admin.bank.edit-bank',[
            'bank'      =>      $bank
        ]);
    }

    /**
     * delete unit by id (only if unit is not use in product)
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBank(Request $request)
    {
            $bank = Bank::findOrFail($request->id);
            $bank->delete();
            return redirect()->back()->with('delete_success','Bank has been deleted successfully');
        
    }

    /**
     * view cannot delete unit
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   

    /**
     * Save an unit
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveBank(Request $request)
    {
        $bank = new Bank();
        $bank->name = $request->get('name');
        $bank->account_number = $request->get('account_number');
        $bank->status = 1;
        $bank->user_id = auth()->user()->id;
        if($bank->save()){
            return response()->json('Ok', 200);
        }
    }

    /**
     * Update an unit by id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBank($id,Request $request)
    {
        $bank = Bank::findOrFail($id);
        $bank->name = $request->get('name');
        $bank->account_number = $request->get('account_number');
        $bank->status = 1;
        if($bank->save()){
            return response()->json('Ok', 200);
        }
    }
}
