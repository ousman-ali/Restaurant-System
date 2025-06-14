<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purse;
use App\Models\PursesPayment;
use App\Models\PursesProduct;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

class PursesController extends Controller
{

    /**
     * Show new purses form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addPurses()
    {
        $products = Product::orderBy('product_name')->get();
        $suppliers = Supplier::where('status',1)->get();
        $unit = Unit::where('status',1)->get();
        return view('user.admin.stock.purses.new-purses',[
            'products'          =>      $products,
            'units'             =>      $unit,
            'suppliers'         =>      $suppliers
        ]);
    }


    /**
     * All purses
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allPurses()
    {
        $purses = Purse::all();
        return view('user.admin.stock.purses.all-purses',[
            'purses'            =>      $purses
        ]);
    }

    /**
     * Eidt purses
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPurses($id)
    {
        $purses = Purse::findOrFail($id);
        $products = Product::orderBy('product_name')->get();
        $suppliers = Supplier::where('status',1)->get();
        $unit = Unit::where('status',1)->get();

        return view('user.admin.stock.purses.edit-purses',[
            'products'          =>      $products,
            'units'             =>      $unit,
            'suppliers'         =>      $suppliers,
            'purses'            =>      $purses
        ]);
    }


    /**
     * New purses store into database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePurses(Request $request)
    {

        $purses = new Purse();
        $purses->purses_id = rand(1000,5000).auth()->user()->id;
        $purses->supplier_id = $request->get('supplier_id');
        $purses->purses_value = 10;
        $purses->is_payed = 0;
        $purses->user_id = auth()->user()->id;
        if($purses->save()){
            foreach ($request->get('purses') as $purse){
                $product = $purse['product'];
                $unit = $purse['unit'];
                $pursesProduct = new PursesProduct();
                $pursesProduct->purse_id = $purses->id;
                $pursesProduct->product_id = $product['productId'];
                $pursesProduct->quantity = $purse['quantity'];
                $pursesProduct->unit_price = $unit['unitPrice'];
                $pursesProduct->child_unit_price = $unit['childUnit'];
                $pursesProduct->gross_price = $pursesProduct->quantity * $pursesProduct->unit_price;
                if($pursesProduct->save()){
                    continue;
                }else{
                    PursesProduct::where('purses_id',$purses->id)->delete();
                    Purse::destroy($purse->id);
                    return response()->json('Internal Serer Error',500);
                }
            }
            if($request->get('payment') != 0){
                $pursesPayment = new PursesPayment();
                $pursesPayment->payment_amount = $request->get('payment');
                $pursesPayment->supplier_id = $purses->supplier_id;
                $pursesPayment->purse_id = $purses->id;
                $pursesPayment->user_id = auth()->user()->id;
                $pursesPayment->save();
            }
            return response()->json('Ok',200);
        }else{
            return response()->json('Internal Server Error',419);
        }
    }


    /**
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function updatePurses(Request $request,$id)
    {
        return $request->all();
    }

    /**
     * Get products unit by id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitOfProduct($id)
    {
        $product = Product::where('id',$id)->with('unit')->first();
        return response()->json($product);
    }

    /**
     * View purses details page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPursesDetails($id)
    {
        $purses = Purse::with('pursesProducts')->with('pursesPayments')->findOrFail($id);
        return response()->json($purses);
    }

    /**
     * show purses payment view
     * @param $purses_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pursesPayment($purses_id)
    {
        $purses = Purse::findOrFail($purses_id);
        return view('user.admin.stock.purses.purses-payment',[
            'purses'        =>      $purses
        ]);
    }

    /**
     * Purses payment store
     * @param Request $request
     * @param $purses_id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function savePursesPayment(Request $request,$purses_id)
    {
        $purses = Purse::findOrFail($purses_id);
        if($request->get('payment') > $purses->pursesProducts->sum('gross_price') - $purses->pursesPayments->sum('payment_amount')){
            return redirect()->back()->withErrors(['msg'=>'You cannot make payment more then the due']);
        }else{
            $pursesPayment = new PursesPayment();
            $pursesPayment->payment_amount = $request->get('payment');
            $pursesPayment->supplier_id = $purses->supplier_id;
            $pursesPayment->purse_id = $purses->id;
            $pursesPayment->user_id = auth()->user()->id;
            if($pursesPayment->save()){
                return redirect()->back();
            }
        }
    }

    /**
     * Delete purses product
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePursesProduct($id)
    {
        PursesProduct::destroy($id);
        return redirect()->back()->with('delete_success','Purses product has been deleted successfully');
    }

    /**
     * Save purses product
     * @param Request $request
     * @param $purses_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePursesProduct(Request $request,$purses_id)
    {

        $pursesProduct = new PursesProduct();
        $pursesProduct->purse_id = $purses_id;
        $pursesProduct->product_id = $request->get('product_id');
        $pursesProduct->quantity = $request->get('quantity');
        $pursesProduct->unit_price = $request->get('unit_price');
        $pursesProduct->child_unit_price = $request->get('child_unit_price');
        $pursesProduct->gross_price = $pursesProduct->quantity * $pursesProduct->unit_price;
        if($pursesProduct->save()){
            return redirect()->back();
        }
    }

    /**
     * Delete purses
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePurses($id)
    {
        $purses = Purse::findOrFail($id);
        PursesProduct::where('purse_id',$purses->id)->delete();
        PursesPayment::where('purse_id',$purses->id)->delete();
        if($purses->delete()){
            return redirect()->back()->with('delete_success','Purses has been deleted successfully');
        }else{
            return redirect()->back()->with('delete_error','Purses cannot delete');
        }
    }



}
