<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\ReadyDish;
use App\Models\Dish;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
class BarmanController extends Controller
{
    public function allStock()
    {
        $dishes = Dish::where('order_to', 'barman')->with('dishRecipes.product')->get();
        $productIds = $dishes->flatMap(function ($dish) {
            return $dish->dishRecipes->pluck('product.id');
        })->unique()->values();
        $recipeProducts = Product::whereIn('id', $productIds)
            ->withSum('purses', 'quantity')
            ->withSum('cookedProducts', 'quantity')
            ->get();
        $data['products'] = $recipeProducts;
        return view('user.barman.materials.stock-status', $data);
    }

    public function lowStock(){
        $dishes = Dish::where('order_to', 'barman')->with('dishRecipes.product')->get();
        $productIds = $dishes->flatMap(function ($dish) {
            return $dish->dishRecipes->pluck('product.id');
        })->unique()->values();
        $products = Product::whereIn('id', $productIds)
            ->withSum('purses', 'quantity')
            ->withSum('cookedProducts', 'quantity')
            ->get();
        $lowStockProducts = $products->filter(function ($product) {
            $purchased = $product->purses_sum_quantity ?? 0;
            $used = $product->cooked_products_sum_quantity ?? 0;
            $stock = $purchased - $used;
            return $stock <= $product->minimum_stock_threshold;
        })->map(function ($product) {
            $product->stock = ($product->purses_sum_quantity ?? 0) - ($product->cooked_products_sum_quantity ?? 0);
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
