<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purse;
use App\Models\PursesProduct;
use App\Models\PursesPayment;
use App\Models\ReadyDish;
use App\Models\PursesReadyDish;
use Session;
use Illuminate\Support\Facades\Log;
class MaterialRequestController extends Controller
{
    public function kitchenRequest(){
        $data['requests'] = MaterialRequest::where('requested_by', 4)->where('type', 'recipe_product')->get();
        $data['products'] = Product::orderBy('product_name')->get();
        $data['suppliers'] = Supplier::where('status',1)->get();
        return view('user.admin.materials.kitchen-request', $data);
    }

    public function bakerRequest(){
        $data['requests'] = MaterialRequest::where('requested_by', 6)->where('type', 'recipe_product')->get();
        $data['products'] = Product::orderBy('product_name')->get();
        $data['suppliers'] = Supplier::where('status',1)->get();
        return view('user.admin.materials.baker-request', $data);
    }

    public function approveKitchenRequest(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'gross_price' => 'required|numeric',
            'payment_amount' => 'nullable|numeric|max:' . $request->gross_price,
        ]);
        $purses = new Purse();
        $purses->purses_id = rand(1000,5000).auth()->user()->id;
        $purses->supplier_id = $request->get('supplier_id');
        $purses->purses_value = 10;
        $purses->is_payed = 0;
        $purses->user_id = auth()->user()->id;
        if($purses->save()){
            $pursesProduct = new PursesProduct();
            $pursesProduct->purse_id = $purses->id;
            $pursesProduct->product_id = $request->product_id;
            $pursesProduct->quantity = $request->quantity;
            $pursesProduct->unit_price = $request->unit_price;
            $pursesProduct->child_unit_price = $request->child_unit_price;
            $pursesProduct->gross_price = $request->gross_price;
            $pursesProduct->save();
            if(!$pursesProduct){
                PursesProduct::where('purses_id',$purses->id)->delete();
                Purse::destroy($purse->id);
                return response()->json('Internal Serer Error',500);
            }

            if($request->get('payment') != 0){
                $pursesPayment = new PursesPayment();
                $pursesPayment->payment_amount = $request->get('payment');
                $pursesPayment->supplier_id = $purses->supplier_id;
                $pursesPayment->purse_id = $purses->id;
                $pursesPayment->user_id = auth()->user()->id;
                $pursesPayment->save();
            }

            $kReq = MaterialRequest::find($request->reference_id);
            $kReq->status = 'approved';
            $kReq->save();

            try {
            $product = Product::find($request->product_id);
            event(new \App\Events\MaterialRequestEvent([
                'type' => 'recipe_product',
                'user' =>auth()->user()->name,
                'requested' => $request->quantity,
                'name' => $product->product_name ?? 'Unknown',
                'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
                'time' => now(),
                'notify' => $kReq->requested_by,
                'not_type' => 'approve',
            ]));

            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }
            Session::flash('success', 'The request approved successfully');
            return back();

        }else{
             return response()->json('Internal Server Error',419);
        }
    }


    public function rejectKitchenRequest(Request $request)
    {
        
        $request->validate([
            'reference_id' => 'required|exists:material_requests,id',
        ]);

        $materialRequest = MaterialRequest::find($request->reference_id);

        if($materialRequest->status == 'approved')
        {
            $productId = $materialRequest->reference_id;
            $requestedQty = $materialRequest->requested_quantity;
            $purseProducts = PursesProduct::where('product_id', $productId)
                ->orderBy('created_at', 'asc')
                ->get();

            $remainingToRemove = $requestedQty;

            foreach ($purseProducts as $purseProduct) {
                if ($remainingToRemove <= 0) break;

                if ($purseProduct->quantity <= $remainingToRemove) {
                    $remainingToRemove -= $purseProduct->quantity;
                    $purseProduct->delete();
                } else {
                    $purseProduct->quantity -= $remainingToRemove;
                    $purseProduct->save();
                    $remainingToRemove = 0;
                }
            }

            if ($remainingToRemove > 0) {
                return back()->with('error', 'Not enough stock available to reject this request.');
            }
        }
        $materialRequest->status = 'rejected';
        $materialRequest->save();

        try {
            $product = Product::find($request->reference_id);
            event(new \App\Events\MaterialRequestEvent([
                'type' => 'recipe_product',
                'user' =>auth()->user()->name,
                'requested' => $materialRequest->requested_quantity,
                'name' => $product->product_name ?? 'Unknown',
                'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
                'time' => now(),
                'notify' => $materialRequest->requested_by,
                'not_type' => 'reject',
            ]));

            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }

        return back()->with('success', 'Request rejected and stock quantities updated.');
    }


    // baker

        public function barmanRequest(){
            $data['requests'] = MaterialRequest::where('requested_by', 5)->get();
            $data['products'] = ReadyDish::where('source_type', 'supplier')->orderBy('name')->get();
            $data['suppliers'] = Supplier::where('status', 1)->get();
            return view('user.admin.materials.barman-request', $data);
        }

    public function approveBarmanRequest(
        Request $request){
        $request->validate([
            'product_id' => 'required|exists:ready_dishes,id',
            'supplier_id' => 'required',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'gross_price' => 'required|numeric',
            'payment_amount' => 'nullable|numeric|max:' . $request->gross_price,
        ]);
        $purses = new Purse();
        $purses->purses_id = rand(1000,5000).auth()->user()->id;
        $purses->supplier_id = $request->get('supplier_id');
        $purses->purses_value = 10;
        $purses->is_payed = 0;
        $purses->user_id = auth()->user()->id;
        if($purses->save()){
            $pursesProduct = new PursesReadyDish();
            $pursesProduct->purse_id = $purses->id;
            $pursesProduct->ready_dish_id = $request->product_id;
            $pursesProduct->ready_quantity = $request->quantity;
            $pursesProduct->unit_price = $request->unit_price;
            $pursesProduct->total_price = $request->gross_price;
            $pursesProduct->save();
            if(!$pursesProduct){
                PursesReadyDish::where('purses_id',$purses->id)->delete();
                Purse::destroy($purse->id);
                return response()->json('Internal Serer Error',500);
            }

            if($request->get('payment') != 0){
                $pursesPayment = new PursesPayment();
                $pursesPayment->payment_amount = $request->get('payment');
                $pursesPayment->supplier_id = $purses->supplier_id;
                $pursesPayment->purse_id = $purses->id;
                $pursesPayment->user_id = auth()->user()->id;
                $pursesPayment->save();
            }

            $kReq = MaterialRequest::find($request->reference_id);
            $kReq->status = 'approved';
            $kReq->save();

            try {
                $product = ReadyDish::find($request->product_id);
                event(new \App\Events\MaterialRequestEvent([
                    'type' => $request->type,
                    'user' =>auth()->user()->name,
                    'requested' => $request->requested_quantity,
                    'name' => $product->name ?? 'Unknown',
                    'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
                    'time' => now(),
                    'notify' => $kReq->requested_by,
                    'not_type' => 'approve',
                ]));

                } catch (\Exception $exception) {
                    Log::error("Broadcasting failed: " . $exception->getMessage());
                }
            Session::flash('success', 'The request approved successfully');
            return back();

        }else{
             return response()->json('Internal Server Error',419);
        }
    }


    public function rejectBarmanRequest(Request $request)
    {
        
        $request->validate([
            'reference_id' => 'required|exists:material_requests,id',
        ]);

        $materialRequest = MaterialRequest::find($request->reference_id);
        if($materialRequest->status == 'approved')
        {
            $productId = $materialRequest->reference_id;
            $requestedQty = $materialRequest->requested_quantity;
            $purseProducts = PursesReadyDish::all();
            $remainingToRemove = $requestedQty;
            foreach ($purseProducts as $purseProduct) {
                if ($remainingToRemove <= 0) break;
                

                if ($purseProduct->ready_quantity <= $remainingToRemove) {
                    $remainingToRemove -= $purseProduct->ready_quantity;
                    $purseProduct->delete();
                } else {
                    $purseProduct->ready_quantity -= $remainingToRemove;
                    $purseProduct->save();
                    $remainingToRemove = 0;
                }
            }

            if ($remainingToRemove > 0) {
                return back()->with('error', 'Not enough stock available to reject this request.');
            }
        }
        $materialRequest->status = 'rejected';
        $materialRequest->save();

        try {
            $product = ReadyDish::find($request->reference_id);
            event(new \App\Events\MaterialRequestEvent([
                'type' => 'ready_dish',
                'user' =>auth()->user()->name,
                'requested' => $materialRequest->requested_quantity,
                'name' => $product->name ?? 'Unknown',
                'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
                'time' => now(),
                'notify' => $materialRequest->requested_by,
                'not_type' => 'reject',
            ]));

            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }

        return back()->with('success', 'Request rejected and stock quantities updated.');
    }

}
