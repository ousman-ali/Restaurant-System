<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Show All recipe
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allRecipe()
    {
        $dishes = Dish::all();
        $recipes = Recipe::all();
        return view('user.admin.recipe.all-recipe', [
            'dishes' => $dishes,
            'recipes' => $recipes
        ]);
    }

    /**
     * New recipe
     * @param $dish_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRecipe($dish_id)
    {
        $dish = Dish::findOrFail($dish_id);
        $products = Product::orderBy('product_name', 'desc')->get();
        return view('user.admin.dish.dish-recipe.add-dish-recipe', [
            'dish' => $dish,
            'products' => $products
        ]);
    }

    /**
     * Edit recipe
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRecipe($id)
    {
        return view('user.admin.recipe.edit-recipe');
    }

    /**
     * Delete recipe
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        if($recipe->delete()){
            return redirect()->back()->with('delete_success','Recipe has been deleted successfully');
        }
    }

    /**
     * Save dish recipe
     * @param Request $request
     * @param $dish_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveRecipe(Request $request, $dish_id)
    {

        $existRecipe = Recipe::where('dish_type_id',$request->get('dish_type_id'))
            ->where('product_id',$request->get('product_id'))
            ->first();
        if(!$existRecipe){
            $recipe = new Recipe();
            $recipe->dish_id = $dish_id;
            $recipe->dish_type_id = $request->get('dish_type_id');
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

    /**
     *
     * @param Request $request
     * @param $id
     */
    public function updateRecipe(Request $request, $id)
    {

    }

    /**
     * Get dish prices and types
     * @param $dish_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTypesOfDish($dish_id)
    {
        $dish = Dish::findOrFail($dish_id);
        return response()->json($dish->dishPrices);
    }
}
