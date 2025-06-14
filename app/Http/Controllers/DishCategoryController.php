<?php

namespace App\Http\Controllers;

use App\Models\DishPrice;
use App\Models\DishCategory;
use App\Models\OrderDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DishCategoryController extends Controller
{
    /**
     * Add Dish type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addDishType()
    {
        return view('user.admin.dish-category.add-dish-type');
    }

    /**
     * Show all dish type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allDishType()
    {
        $dish_types = DishCategory::all();
        return view('user.admin.dish-category.all-dish-type', [
            'dish_types' => $dish_types
        ]);
    }

    /**
     * Show edit page of dish type
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDishType($id)
    {
        $dish_type = DishCategory::findOrFail($id);
        return view('user.admin.dish-category.edit-dish-type', [
            'dish_type' => $dish_type
        ]);
    }

    /**
     * Delete dish type
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDishType($id)
    {
        $dishType = DishPrice::findOrFail($id);
        $dish_type_on_order = OrderDetails::where('dish_type_id', $id)->first();
        if (!$dish_type_on_order) {
            $dishType->delete();
            return redirect()->back()->with('delete_success', 'Dish type has been deleted successfully');
        } else {
            return redirect()->back()->with('delete_error', 'You cannot delete this type! this type has been used in dish order');
        }
    }

    /**
     * Save dish type
     * @param Request $request
     * @return JsonResponse
     */
    public function saveDishType(Request $request)
    {
        $dish_type = new DishCategory();
        $dish_type->name = $request->get('name');
        $dish_type->user_id = auth()->user()->id;
        $dish_type->save();
        return response()->json($dish_type, 200);
    }

    /**
     * Update dish type
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateDishType(Request $request, $id)
    {
        $dish_type = DishCategory::findOrFail($id);
        $dish_type->name = $request->get('name');
        $dish_type->status = $request->get('status') == 'on' ? 1 : 0;
        $dish_type->user_id = auth()->user()->id;
        $dish_type->save();
        return response()->json($dish_type, 200);

    }

    /**
     * Return all dish categories
     * @return JsonResponse
     */
    public function getDishCategories(): JsonResponse
    {
        return response()->json(DishCategory::all());
    }
}
