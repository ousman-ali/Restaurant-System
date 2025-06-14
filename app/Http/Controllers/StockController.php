<?php

namespace App\Http\Controllers;

use App\Models\CookedProduct;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\PursesProduct;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    /**
     * Current stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allStock()
    {
        $items = Product::all();
        $product_type = ProductType::all();
        return view('user.admin.stock.all-item',[
            'items'     =>      $items,
            'product_types'  =>  $product_type
        ]);
    }

    /**
     * Add new stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addStock()
    {
        $unit = Unit::all();
        $product_type = ProductType::where('status',1)->get();
        return view('user.admin.stock.add-item',[
            'units'         =>      $unit,
            'product_type'  =>      $product_type
        ]);
    }

    /**
     * Edit stock
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editStock($id)
    {
        $item = Product::findOrFail($id);
        $unit = Unit::all();
        $product_type = ProductType::where('status',1)->get();
        return view('user.admin.stock.edit-item',[
            'item'          =>      $item,
            'units'         =>      $unit,
            'product_type'  =>      $product_type
        ]);
    }

    /**
     * View stock details
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewStock($id)
    {
        $item = Product::findOrFail($id);
        return view('user.admin.stock.view-item',[
            'item'      =>      $item
        ]);
    }

    /**
     * Delete stock if not use in dish recipe
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteStock($id)
    {
        $product = Product::findOrFail($id);
        $product_on_dish = Recipe::where('product_id',$id)->first();
        $product_on_purses = PursesProduct::where('product_id',$id)->first();
        $product_on_cooked = CookedProduct::where('product_id')->first();
        if(!$product_on_dish || !$product_on_purses || !$product_on_cooked){
            return redirect()->to('/cannot-delete-item/'.$id);
        }else{
            $product->delete();
            return redirect()->back();
        }

    }

    /**
     * show cannot delete product if the product has been used in dish recipe
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cannotDeleteStock($id)
    {
        $product = Product::findOrFail($id);
        return view('user.admin.stock.cannot-delete',[
           'product'    =>      $product
        ]);
    }

    /**
     * Add new product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStock(Request $request)
    {
        $request->validate([
            'product_name'       =>     'required|unique:products|max:255',
            'unit_id'            =>     'required|max:11',
            'product_type_id'    =>     'required|max:11'
        ]);

        $item = new Product();
        $item->product_name = $request->get('product_name');
        $item->unit_id = $request->get('unit_id');
        $item->product_type_id = $request->get('product_type_id');
        if($request->hasFile('thumbnail')){
            $item->thumbnail = $request->file('thumbnail')
                ->move('uploads/products/thumbnail',
                    rand(8000000,99999999).'.'.$request->thumbnail->extension());
        }
        $item->user_id = auth()->user()->id;
        if($item->save()){
            return response()->json('Ok',200);
        }
    }

    /**
     * Update product info
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request,$id)
    {
        $request->validate([
            'product_name'       =>     Rule::unique('products')->ignore($id, 'id'),
            'unit_id'            =>     'required|max:11',
            'product_type_id'    =>     'required|max:11'
        ]);

        $item = Product::findOrFail($id);
        $item->product_name = $request->get('product_name');
        $item->unit_id = $request->get('unit_id');
        $item->product_type_id = $request->get('product_type_id');
        if($request->hasFile('thumbnail')){
            $item->thumbnail = $request->file('thumbnail')
                ->move('uploads/products/thumbnail',
                    rand(8000000,99999999).'.'.$request->thumbnail->extension());
        }
        $item->user_id = auth()->user()->id;
        if($item->save()){
            return response()->json('Ok',200);
        }
    }





}
