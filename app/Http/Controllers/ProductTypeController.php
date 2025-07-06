<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    public function allProductType()
    {
        $product_type = ProductType::latest()->get();
        return view('user.admin.product-type.all-product-type',[
            'product_types' =>  $product_type
        ]);
    }


    public function addProductType()
    {
        return view('user.admin.product-type.add-product-type');
    }

    public function editProductType($id)
    {
        $product_type = ProductType::findOrFail($id);
        return view('user.admin.product-type.edit-product-type',[
            'product_type'  =>  $product_type
        ]);
    }

    public function deleteProductType(Request $request)
    {
        $product_type = ProductType::findOrFail($request->id);
        $product_type_in_product = Product::where('product_type_id',$product_type->id)->first();
        if(!$product_type_in_product){
            $product_type->delete();
            return redirect()->back()->with('delete_success','Product type has been delete successfully');
        }else{
            return redirect()->to('/cannot-delete-product-type/'.$request->id);
        }
    }

    public function cannotDeleteProductType($id)
    {
        $product_type = ProductType::findOrFail($id);
        return view('user.admin.product-type.cannot-delete-product-type',[
            'product_type'  =>  $product_type
        ]);
    }

    public function saveProductType(Request $request)
    {
        $request->validate([
            'product_type'  =>  'required|unique:product_types|max:255'
        ]);

        $product_type = new ProductType();
        $product_type->product_type = $request->get('product_type');
        $product_type->user_id = auth()->user()->id;
        $product_type->status = 1;
        if($product_type->save()){
            return redirect('/all-product-type')->with('save_success', 'Product type added successfully.');
        }else{
            return response()->json('Error',500);
        }
    }

    public function updateProductType(Request $request,$id)
    {
       $product_type = ProductType::findOrFail($id);

       $this->validate($request,[
           'product_type'   =>   Rule::unique('product_types')->ignore($id, 'id')
       ]);

        $product_type->product_type = $request->get('product_type');
        $product_type->user_id = auth()->user()->id;
        $product_type->status = $request->get('status') == 'on' ? 1 : 0;
        if($product_type->save()){
            return redirect('/all-product-type')->with('save_success', 'Product type updated successfully.');
        }else{
            return response()->json('Error',500);
        }
    }


}
