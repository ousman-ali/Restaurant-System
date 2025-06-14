<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Add unit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addUnit()
    {
        return view('user.admin.unit-settings.add-unit');
    }

    /**
     * Show all unit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allUnit()
    {
        $unit = Unit::all();
        return view('user.admin.unit-settings.all-unit',[
            'units'     =>      $unit
        ]);
    }

    /**
     * Edit unit by id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('user.admin.unit-settings.edit-unit',[
            'unit'      =>      $unit
        ]);
    }

    /**
     * delete unit by id (only if unit is not use in product)
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $product = Product::where('unit_id',$id)->first();
        if($product){
            return redirect()->to('/cannot-delete-unit/'.$unit->id);
        }else {
            $unit->delete();
            return redirect()->back()->with('delete_success','Unit has been deleted successfully');
        }
    }

    /**
     * view cannot delete unit
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cannotDeleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('user.admin.unit-settings.cannot-delete',[
            'unit'      =>      $unit
        ]);
    }

    /**
     * Save an unit
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUnit(Request $request)
    {
        $unit = new Unit();
        $unit->unit = $request->get('unit');
        $unit->child_unit = $request->get('child_unit');
        $unit->convert_rate = $request->get('convert_rate');
        $unit->status = 1;
        $unit->user_id = auth()->user()->id;
        if($unit->save()){
            return response()->json('Ok', 200);
        }
    }

    /**
     * Update an unit by id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUnit($id,Request $request)
    {
        $unit = Unit::findOrFail($id);
        $unit->unit = $request->get('unit');
        $unit->child_unit = $request->get('child_unit');
        $unit->convert_rate = $request->get('convert_rate');
        $unit->status = 1;
        if($unit->save()){
            return response()->json('Ok', 200);
        }
    }
}
