<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DishCategory;
use App\Models\ReadyDish;
use App\Models\DishInfo;
use App\Models\Product;
use App\Models\Recipe; 
use App\Models\Unit;
use App\Models\OrderDetails;
class ReadyDishController extends Controller
{

    public function all()
    {
        $dishes = ReadyDish::with(['producedBatches', 'purchasedBatches'])->get();
        return view('user.admin.ready-dish.all-ready-dish', [
            'dishes' => $dishes
        ]);
    }
     public function add()
    {
        $categories = DishCategory::all();
        $units = Unit::where('usage_type', 'ready_dish')->get();
        return view('user.admin.ready-dish.add-ready-dish', compact('categories', 'units'));
    }

    public function save(Request $request)
    {
    
        $this->validate($request, [
            'dish' => 'required|string',
            'source_type' =>'required|string',
            'price' =>'required|numeric',
            'category_id' => 'required|numeric|exists:dish_categories,id',
            'unit_id' => 'required|numeric|exists:units,id',
        ]);
        $dish = new ReadyDish();
        $dish->name = $request->get('dish');
        $dish->source_type = $request->source_type;
        $dish->price = $request->price;

        if ($request->hasFile('thumbnail')) {
            $filename = rand(8000000, 99999999) . '.' . $request->thumbnail->extension();
            $request->file('thumbnail')->move('uploads/dish/thumbnail', $filename);
            $dish->thumbnail = 'uploads/dish/thumbnail/' . $filename; 
        }
        $dish->user_id = auth()->user()->id;
        $dish->unit_id = $request->unit_id;
        $dish->category_id = $request->category_id;
        $dish->minimum_stock_threshold = $request->minimum_stock_threshold;
        if ($dish->save()) {
            return redirect()->to('/ready-dish-image/' . $dish->id);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'dish' => 'required|string',
            'source_type' =>'required|string',
            'price' =>'required|numeric',
            'category_id' => 'required|numeric|exists:dish_categories,id',
        ]);

        $dish = ReadyDish::findOrFail($id);
        $dish->name = $request->get('dish');
        $dish->source_type = $request->source_type;
        $dish->price = $request->price;

        if ($request->hasFile('thumbnail')) {
            $filename = rand(8000000, 99999999) . '.' . $request->thumbnail->extension();
            $request->file('thumbnail')->move('uploads/dish/thumbnail', $filename);
            $dish->thumbnail = 'uploads/dish/thumbnail/' . $filename; // use forward slashes
        }
        $dish->user_id = auth()->user()->id;
        $dish->unit_id = $request->unit_id;
        $dish->category_id = $request->category_id;
        $dish->status = $request->get('available') == 'on' ? 1 : 0;
        $dish->minimum_stock_threshold = $request->minimum_stock_threshold;
        if ($dish->save()) {
            return response()->json('Ok', 200);
        }
    }

    public function edit($id)
    {
        $dish = ReadyDish::findOrFail($id);
        $categories = DishCategory::all();
        $unit = Unit::where('usage_type', 'ready_dish')->get();
        return view('user.admin.ready-dish.edit-ready-dish', [
            'dish' => $dish,
            'units' => $unit,
            'categories' => $categories
        ]);
    }

    public function addDishImage($dish_id)
    {
        $dish = ReadyDish::findOrFail($dish_id);
        return view('user.admin.ready-dish.dish-image.add-dish-image', [
            'dish' => $dish
        ]);
    }

    public function saveDishImage(Request $request)
    {
        $dish_image = new DishInfo();
        $dish_image->title = $request->get('title');
        $dish_image->ready_dish_id = $request->get('ready_dish_id');
        $dish_image->user_id = auth()->user()->id;
        if ($request->hasFile('image')) {
            $dish_image->image = $request->file('image')
                ->move('uploads/dish/images',
                    rand(8000000, 99999999) . '.' . $request->image->extension());
        }
        if ($dish_image->save()) {
            return redirect()->back();
        }
    }

    public function deleteDishImage(Request $request)
    {
        $dish_image = DishInfo::find($request->id);
        if ($dish_image->delete()) {
            return redirect()->back()->with('delete_success', 'Dish Image has been delete successfully....');
        }
    }


    public function addRecipe($dish_id)
    {
        $dish = ReadyDish::findOrFail($dish_id);
        $products = Product::orderBy('product_name', 'desc')->get();
        return view('user.admin.ready-dish.dish-recipe.add-dish-recipe', [
            'dish' => $dish,
            'products' => $products
        ]);
    }

    public function saveRecipe(Request $request, $dish_id)
    {

        
        $existRecipe = Recipe::where('ready_dish_id',$dish_id)
            ->where('product_id',$request->get('product_id'))
            ->first();
        if(!$existRecipe){
            $recipe = new Recipe();
            $recipe->ready_dish_id = $dish_id;
            $recipe->product_id = $request->get('product_id');
            $recipe->unit_needed = $request->get('unit');
            $recipe->child_unit_needed = $request->get('child_unit');
            $recipe->user_id = auth()->id();
            if ($recipe->save()) {
                return redirect()->back();
            }
        }else{
            return redirect()->back()->with(['error','Already Exist']);
        }


    }


    public function view($id)
    {
        $dish = ReadyDish::findOrFail($id);
        return view('user.admin.ready-dish.view-ready-dish', [
            'dish' => $dish
        ]);
    }

     public function delete(Request $request)
    {
        $dish = ReadyDish::findOrFail($request->dish_id);
        $dish_on_order = OrderDetails::where('ready_dish_id', $request->dish_id)->first();
        if (!$dish_on_order) {
            DishInfo::where('ready_dish_id', $dish->id)->delete();
            $dish->delete();
            return redirect()->back()->with('delete_success', 'Dish has been delete successfully ..');
        } else {
            return redirect()
                ->back()
                ->with('delete_error',
                    'Dish cannot delete ! This dish has been used in order. If you dont want to show this dish anymore you can simply de-active this dish');
        }
    }
}
