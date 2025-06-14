<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishCategory;
use App\Models\DishInfo;
use App\Models\DishPrice;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DishController extends Controller
{
    /**
     * It will show an form of add dish
     * @return Factory|View
     */
    public function addDish(): Factory|View
    {
        $categories = DishCategory::all();
        return view('user.admin.dish.add-dish', compact('categories'));
    }

    /**
     * User can see all available dish in the restaurant by this method
     * @return Factory|View
     */
    public function allDish(): Factory|View
    {
        $dishes = Dish::all();
        return view('user.admin.dish.all-dish', [
            'dishes' => $dishes
        ]);
    }

    /**
     * All Dish in rest api
     * @return JsonResponse
     */
    public function getDishes(): JsonResponse
    {
        $dishes = Dish::with(['dishPrices', 'dishImages'])->whereHas('dishRecipes')->get();
        return response()->json($dishes);
    }

    /**
     * User can able to edit selected dish by this method
     * @param $id
     * @return Factory|View
     */
    public function editDish($id): Factory|View
    {
        $dish = Dish::findOrFail($id);
        $categories = DishCategory::all();
        return view('user.admin.dish.edit-dish', [
            'dish' => $dish,
            'categories' => $categories
        ]);
    }

    /**
     * User can able to view the dish with price and images by this method
     * @param $id
     * @return Factory|View
     */
    public function viewDish($id): Factory|View
    {
        $dish = Dish::findOrFail($id);
        return view('user.admin.dish.view-dish', [
            'dish' => $dish
        ]);
    }

    /**
     * User can delete dish (only if there is order on this dish) by this method
     * @param $id
     * @return RedirectResponse
     */
    public function deleteDish($id): RedirectResponse
    {
        $dish = Dish::findOrFail($id);
        $dish_on_order = OrderDetails::where('dish_id', $id)->first();
        if (!$dish_on_order) {
            DishPrice::where('dish_id', $dish->id)->delete();
            DishInfo::where('dish_id', $dish->id)->delete();
            $dish->delete();
            return redirect()->back()->with('delete_success', 'Dish has been delete successfully ..');
        } else {
            return redirect()
                ->back()
                ->with('delete_error',
                    'Dish cannot delete ! This dish has been used in order. If you dont want to show this dish anymore you can simply de-active this dish');
        }
    }

    /**
     * User can able to add new dish by this method
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveDish(Request $request)
    {
        $this->validate($request, [
            'dish' => 'required|string',
            'category_id' => 'required|numeric|exists:dish_categories,id',
        ]);
        $dish = new Dish();
        $dish->dish = $request->get('dish');
        if ($request->hasFile('thumbnail')) {
            $dish->thumbnail = $request->file('thumbnail')
                ->move('uploads/dish/thumbnail',
                    rand(8000000, 99999999) . '.' . $request->thumbnail->extension());
        }
        $dish->user_id = auth()->user()->id;
        if ($dish->save()) {
            return redirect()->to('/dish-price/' . $dish->id);
        }
    }

    /**
     * User can able to update dish by this method
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateDish(Request $request, $id)
    {
        $this->validate($request, [
            'dish' => 'required|string',
            'category_id' => 'required|numeric|exists:dish_categories,id',
        ]);

        $dish = Dish::findOrFail($id);
        $dish->dish = $request->get('dish');
        if ($request->hasFile('thumbnail')) {
            $dish->thumbnail = $request->file('thumbnail')
                ->move('uploads/dish/thumbnail',
                    rand(8000000, 99999999) . '.' . $request->thumbnail->extension());
        }
        $dish->user_id = auth()->user()->id;
        $dish->available = $request->get('available') == 'on' ? 1 : 0;
        if ($dish->save()) {
            return response()->json('Ok', 200);
        }
    }

    /**
     * This method will return a view there user can add dish prices by types
     * @param $dish_id
     * @return Factory|View
     */
    public function addDishPrice($dish_id)
    {
        $dish = Dish::findOrFail($dish_id);
        return view('user.admin.dish.dish-price.add-dish-price', [
            'dish' => $dish
        ]);
    }

    /**
     * User can save dish prices by this method
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveDishPrice(Request $request)
    {
        $dish_price = new DishPrice();
        $dish_price->dish_id = $request->get('dish_id');
        $dish_price->dish_type = $request->get('dish_type');
        $dish_price->price = $request->get('price');
        $dish_price->user_id = auth()->user()->id;
        if ($dish_price->save()) {
            return redirect()->back();
        }

    }

    /**
     * This method will return a view there user can update dish prices by types
     * @param $id
     * @return Factory|View
     */
    public function editDishPrice($id)
    {
        $dish_price = DishPrice::findOrFail($id);
        return view('user.admin.dish.dish-price.edit-dish-price', [
            'dish_price' => $dish_price
        ]);
    }

    /**
     * User can update dish prices by this method
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateDishPrice($id, Request $request)
    {
        $dish_price = DishPrice::findOrFail($id);
        $dish_price->dish_id = $request->get('dish_id');
        $dish_price->dish_type = $request->get('dish_type');
        $dish_price->price = $request->get('price');
        $dish_price->user_id = auth()->user()->id;
        if ($dish_price->save()) {
            return redirect()->to('/dish-price/' . $dish_price->dish->id);
        }
    }

    /**
     * This method will return a view there user can save dish images
     * @param $dish_id
     * @return Factory|View
     */
    public function addDishImage($dish_id)
    {
        $dish = Dish::findOrFail($dish_id);
        return view('user.admin.dish.dish-image.add-dish-image', [
            'dish' => $dish
        ]);
    }

    /**
     * User can add dish images by this method
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveDishImage(Request $request)
    {
        $dish_image = new DishInfo();
        $dish_image->title = $request->get('title');
        $dish_image->dish_id = $request->get('dish_id');
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

    /**
     * This method will used to delete dish image
     * @param $id
     * @return RedirectResponse
     */
    public function deleteDishImage($id)
    {
        $dish_image = DishInfo::findOrFail($id);
        if ($dish_image->delete()) {
            return redirect()->back()->with('delete_success', 'Dish Image has been delete successfully....');
        }
    }

    /**
     * This method will shoe the dish statistic page
     * @return Factory|View
     */
    public function dishStat()
    {
        $dishes = Dish::all();
        return view('user.admin.dish.stat.dish-stat', [
            'dishes' => $dishes
        ]);
    }

    /**
     * This method will redirect to the dish statistic url by requested query
     * @param Request $request
     * @return RedirectResponse
     */
    public function postDishStat(Request $request)
    {
        $start_date = str_replace('/', '-', $request->get('start') != null ? $request->get('start') : "2017-09-01");
        $end_date = str_replace('/', '-', $request->get('end') != null ? $request->get('end') : Carbon::now()->format('Y-m-d'));
        $dish = $request->get('kitchen') == 0 ? 0 : $request->get('kitchen');
        return redirect()
            ->to('/dish-stat/dish=' . $dish . '/start=' . $start_date . '/end=' . $end_date);
    }

    /**
     * This method will show the statistic using the url query
     * @param $id
     * @param $start_date
     * @param $end_date
     * @return Factory|View
     */
    public function showDishStat($id, $start_date, $end_date): Factory|View
    {
        $dishes = Dish::all();
        if ($id == 0) {
            $dish_query = Dish::whereBetween('created_at', array($start_date . " 00:00:00", $end_date . " 00:00:00"))
                ->get();
            return view('user.admin.dish.stat.dish-stat-all', [
                'dishes' => $dishes,
                'dish_query' => $dish_query,
                'start' => $start_date,
                'end' => $end_date
            ]);
        } else {
            $selected_dish = Dish::findOrFail($id);
            $dish_query = Dish::where('id', $id)
                ->whereBetween('created_at', array($start_date . " 00:00:00", $end_date . " 00:00:00"))
                ->get();
            return view('user.admin.dish.stat.dish-stat-selected', [
                'dishes' => $dishes,
                'selected_dish' => $selected_dish,
                'dish_query' => $dish_query,
                'start' => $start_date,
                'end' => $end_date
            ]);
        }
    }


}
