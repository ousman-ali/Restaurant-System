<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReadyDish;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\PursesReadyDish;
use App\Events\OrderServed;
use App\Events\SupplierOrderPurchased;
use App\Models\Purse;
use App\Models\PursesPayment;
use App\Models\MaterialRequest;
use App\Models\InhouseOrder;
use App\Models\SupplierOrder;
use Illuminate\Support\Facades\Log;
class BakerController extends Controller
{
   

    public function barmanLiveBaker()
    {
        return view('user.barman.live-bakery');
    }

    public function barmanLiveWaiter(){
        return view('user.barman.live-waiter');
    }

    public function barmanLiveAdmin(){
        return view('user.barman.live-admin');
    }

  

    public function barmanLiveBakerJSON()
    {
        $inhouseOrders = InhouseOrder::where('status', '!=', 3)
        ->where('order_by', auth()->user()->id)
        ->with([
            'orderDetails.readyDish.unit',
            'orderBy',
            'baker'
        ])
        ->latest()
        ->get();

        $supplierOrders = SupplierOrder::where('status', '!=', '2')
        ->where('order_by', auth()->user()->id)
        ->with([
            'orderDetails.readyDish.unit',
            'orderBy',
            'admin'
        ])
        ->latest()
        ->get();

        $type = auth()->user()->employee->rest_type;

        $waiterOrder = Order::whereNotIn('status', [1, 2, 3])
            ->whereHas('orderDetails', function ($q) {
                $q->where('from_ready', true)
                ->orWhere(function ($sub) {
                    $sub->where('from_ready', false)
                        ->whereHas('dish', function ($dishQuery) {
                            $dishQuery->where('order_to', 'barman');
                        });
                });
            })
            ->with([
                'orderDetails' => function ($query) {
                    $query->where(function ($q) {
                        $q->where('from_ready', true)
                        ->orWhere(function ($sub) {
                            $sub->where('from_ready', false)
                                ->whereHas('dish', function ($dishQuery) {
                                    $dishQuery->where('order_to', 'barman');
                                });
                        });
                    })->with(['readyDish.unit', 'dish']);
                },
                'servedBy',
            ])
            ->where('order_to_cafe', $type)
            ->latest()
            ->get();

        return response()->json([
            'orders' => $inhouseOrders,
            'supplierOrders' => $supplierOrders,
            'waiterOrders' => $waiterOrder,
        ]);
        
    }


    public function myCookingHistory()
    {
        $type = auth()->user()->employee->rest_type;
        $orders = Order::where('baker_id', auth()->user()->id)->where('order_to_cafe', $type)->where('is_ready', true)->get();
        return view('user.baker.my-cooking-history', [
            'orders' => $orders
        ]);
    }

    public function liveBarman()
    {
        
        return view('user.admin.barman.live-barman');
    }

    /**
     * Live kitchen data for admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLiveBarmanJSON()
    {
        $orders = SupplierOrder::where('status', '!=', 1)
            ->where('status', '!=', 2)
            ->with('orderDetails.readyDish')
            ->with('orderDetails.unit')
            ->with('orderBy')
            ->with('admin')
            ->latest()
            ->get();
        return response()->json($orders);
    }

    // Controller
    public function getRecipeForm($id)
    {
        
        $st = request()->get('st');
        $orderId = request()->get('order_id'); 
        $dish = ReadyDish::find($id);
        $dishs = ReadyDish::all();
        $suppliers = Supplier::where('status',1)->get();

        return view('user.admin.barman._ready_recipe_form', compact('dishs', 'suppliers', 'dish', 'st', 'orderId'))->render();
    }

    public function savePurses(Request $request)
    {
        
       
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'order_id' => 'required|exists:supplier_orders,id',
        'items' => 'required|array',
        'items.*.dishId' => 'required|integer',
        'items.*.quantity' => 'required|numeric',
        'items.*.unitPrice' => 'required|numeric',
        'items.*.gross' => 'required|numeric',

    ]);

   $items = $request->items;
    


    if (empty($items)) {
        return response()->json('No items to save', 422);
    }

    $purses = new Purse();
    $purses->purses_id = rand(1000, 5000) . auth()->user()->id;
    $purses->supplier_id = $request->supplier_id;
    $purses->purses_value = 10; 
    $purses->is_payed = 0;
    $purses->user_id = auth()->user()->id;

    if ($purses->save()) {
        try {
            foreach ($items as $item) {
                // Validate required item fields
                
                if (!isset($item['dishId'], $item['quantity'], $item['unitPrice'], $item['gross'])) {
                    
                    continue;
                }

                $pursesProduct = new PursesReadyDish();
                $pursesProduct->purse_id = $purses->id;
                $pursesProduct->ready_dish_id = $item['dishId'];
                $pursesProduct->pending_quantity = $item['quantity'];
                $pursesProduct->unit_price = $item['unitPrice'];
                $pursesProduct->total_price = $item['gross'];
                $pursesProduct->save();
            }

            // Mark the order as complete
            $order = SupplierOrder::findOrFail($request->order_id);
            $order->status = 1;
            $order->purchased_at = now();
            $order->save();

            try {
                broadcast(new SupplierOrderPurchased("success", $order))->toOthers();
            } catch (\Exception $exception) {
                Log::error("Broadcasting failed: " . $exception->getMessage());
            }

            // Handle payment if any
            if ($request->filled('payment') && $request->payment > 0) {
                $pursesPayment = new PursesPayment();
                $pursesPayment->payment_amount = $request->payment;
                $pursesPayment->supplier_id = $purses->supplier_id;
                $pursesPayment->purse_id = $purses->id;
                $pursesPayment->user_id = auth()->user()->id;
                $pursesPayment->save();
            }

            return back();

        } catch (\Exception $e) {
            // Clean up if anything failed
            PursesReadyDish::where('purse_id', $purses->id)->delete();
            $purses->delete();

            Log::error("Save purses failed: " . $e->getMessage());
            return response()->json('Internal Server Error', 500);
        }

    } else {
        return response()->json('Internal Server Error', 419);
    }
    }

public function getRecipeFormAll($orderId)
{
    $order = SupplierOrder::with('orderDetails.readyDish')->findOrFail($orderId);

    $dishs = ReadyDish::where('source_type', 'supplier')->get();
    $suppliers = Supplier::where('status', 1)->get();

    return view('user.admin.barman._ready_recipe_form', compact('order', 'dishs', 'suppliers'))->render();
}

  public function allStock()
    {
        $stockProducts = Product::withSum('purses', 'quantity')->withSum('cookedProducts', 'quantity')->where('dish_type', 'ready')->get();
        $data['products'] = $stockProducts;
        return view('user.baker.materials.stock-status', $data);
    }

    public function lowStock(){
        $products = Product::withSum('purses', 'quantity')->withSum('cookedProducts', 'quantity')->where('dish_type', 'ready')->get();
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
        return view('user.baker.materials.material-request', $data);
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
        try {
        $product = Product::find($request->reference_id);
        event(new \App\Events\LowStockAlertEvent([
            'type' => $request->type,
            'user' =>auth()->user()->name,
            'requested' => $request->requested_quantity,
            'name' => $product->product_name ?? 'Unknown',
            'current_stock' => $product->purses_sum_quantity ?? 0,
            'minimum_stock_threshold' => $product->minimum_stock_threshold ?? 0,
            'time' => now(),
        ]));

        } catch (\Exception $exception) {
            Log::error("Broadcasting failed: " . $exception->getMessage());
        }

        return back()->with('success', 'Request submitted successfully.');
    }

    public function getUnitOfProduct($id)
    {
        $product = Product::where('id',$id)->with('unit')->first();
        return response()->json($product);
    }


}
