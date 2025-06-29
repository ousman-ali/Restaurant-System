<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\ReadyDish;
use Illuminate\Support\Facades\Log;
class BarmanController extends Controller
{
        public function allStock()
    {
        $stockProducts = ReadyDish::where('source_type', 'supplier')->withSum('purchasedBatches', 'ready_quantity')->get();
        $data['products'] = $stockProducts;
        return view('user.barman.materials.stock-status', $data);
    }

    public function lowStock(){
        $products = ReadyDish::where('source_type', 'supplier')->withSum('purchasedBatches', 'ready_quantity')->get();
        $lowStockProducts = $products->filter(function ($product) {
            $stock = $product->purchased_batches_sum_ready_quantity ?? 0;
            return $stock <= $product->minimum_stock_threshold;
        })->map(function ($product) {
            $product->stock = $product->purchased_batches_sum_ready_quantity ?? 0;
            return $product;
        });
        $data['products'] = $lowStockProducts;
        return view('user.barman.materials.material-request', $data);
    }

    public function requestStock(Request $request){
        $request->validate([
            'reference_id' => 'required|integer',
            'requested_quantity' => 'required|numeric|min:1',
            'type' => 'required|in:recipe_product,ready_dish',
        ]);

        MaterialRequest::create([
            'reference_id' => $request->reference_id,
            'type' => $request->type,
            'requested_by' => auth()->user()->role(),
            'requested_quantity' => $request->requested_quantity,
            'status' => 'pending',
        ]);

        try{
        $product = ReadyDish::find($request->reference_id);
        event(new \App\Events\LowStockAlertEvent([
            'type' => $request->type,
            'user' =>auth()->user()->name,
            'requested' => $request->requested_quantity,
            'name' => $product->name ?? 'Unknown',
            'current_stock' => $product->purchased_batches_sum_ready_quantity ?? 0,
            'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
            'time' => now(),
        ]));

        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }

        return back()->with('success', 'Request submitted successfully.');
    }
}
