<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Show all supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allSupplier()
    {
        $suppliers = Supplier::all();
        return view('user.admin.supplier.all-supplier',[
            'suppliers'         =>          $suppliers
        ]);
    }

    /**
     * Add new supplier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addSupplier()
    {
        return view('user.admin.supplier.add-supplier');
    }

    /**
     * Edit supplier info
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('user.admin.supplier.edit-supplier',[
            'supplier'          =>          $supplier
        ]);
    }

    public function deleteSupplier($id)
    {

    }

    /**
     * Save supplier
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->get('name');
        $supplier->email = $request->get('email');
        $supplier->phone = $request->get('phone');
        $supplier->address = $request->get('address');
        $supplier->status = 1;
        $supplier->user_id = auth()->user()->id;
        if($supplier->save()){
            return response()->json('Ok',200);
        }
    }

    /**
     * Update supplier
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSupplier(Request $request,$id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->get('name');
        $supplier->email = $request->get('email');
        $supplier->phone = $request->get('phone');
        $supplier->address = $request->get('address');
        $supplier->status = $request->get('status') == 'on' ? 1 : 0;
        $supplier->user_id = auth()->user()->id;
        if($supplier->save()){
            return response()->json('Ok',200);
        }
    }

    /**
     * View supplier view details
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('user.admin.supplier.view-supplier',[
            'supplier'      =>      $supplier
        ]);
    }
}
