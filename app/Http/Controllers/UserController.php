<?php

namespace App\Http\Controllers;


use App\Http\Requests\Employee\SaveEmployee;
use App\Http\Requests\Employee\UpdateEmployee;
use App\Mail\EmployeRegister;
use App\Models\Dish;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Purse;
use App\Models\PursesPayment;
use App\Models\Recipe;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Table;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Add new employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addEmployee()
    {
        return view('user.admin.employee.add-employee');
    }

    /**
     * View all employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allEmployees()
    {
        $employees = Employee::all();
        return view('user.admin.employee.all-employees', [
            'employees' => $employees
        ]);
    }

    /**
     * Edit employee
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('user.admin.employee.edit-employee', [
            'employee' => $employee
        ]);
    }

    /**
     * Delete employee
     * @param $id
     */
    public function deleteEmployee($id)
    {
        $user = User::findOrFail($id);
        $user_in_order = Order::where('user_id', $user->id)
            ->orWhere('served_by', $user->id)
            ->orWhere('kitchen_id', $user->id)
            ->first();
        $user_in_dish = Dish::where('user_id', $id)->first();
        $user_id_product = Product::where('user_id', $id)->first();
        $user_in_product_type = ProductType::where('user_id', $id)->first();
        $user_in_purses = Purse::where('user_id', $id)->first();
        $user_in_puirses_payment = PursesPayment::where('user_id', $id)->first();
        $user_in_recipe = Recipe::where('user_id', $id)->first();
        $user_in_stock = Stock::where('user_id', $id)->first();
        $user_in_supplier = Supplier::where('user_id', $id)->first();
        $user_in_tbale = Table::where('user_id', $id)->first();
        $user_in_unit = Unit::where('user_id', $id)->first();
        $user_in_employee = Employee::where('user_id', $id)->first();

        if ($user_in_order || $user_in_dish || $user_id_product || $user_in_product_type || $user_in_purses
            || $user_in_puirses_payment || $user_in_recipe || $user_in_stock || $user_in_supplier || $user_in_tbale
            || $user_in_unit || $user_in_employee
        ) {
            return redirect()->back()->with('delete_error', 'You cannot delete this user');
        } else {
            if ($user->role != 1) {
                Employee::destroy($user->employee->id);
                User::destroy($id);
                return redirect()->back()->with('delete_success', 'Employee has been deleted successfully');
            }
        }

    }

    /**
     * Save employee
     * @param SaveEmployee $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveEmployee(SaveEmployee $request)
    {

        $user = new User();
        $user->name = $request->get('name');
        $user->password = Hash::make($request->get('password'));
        $user->email = $request->get('email');
        $user->role = $request->get('role');
        if ($request->hasFile('thumbnail')) {
            $user->image = $request->file('thumbnail')
                ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
        }
        if ($user->save()) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->phone = $request->get('phone');
            $employee->email = $user->email;
            $employee->address = $request->get('address');
            $employee->user_id = $user->id;
            if ($employee->save()) {
                try {
                    dispatch(Mail::to($user->email)->send(new EmployeRegister($user->email, $request->get('password'))));
                } catch (\Exception $exception) {
                    Log::error("Mail send error" . $exception->getMessage());
                }
                return response()->json('Ok', 200);
            }
        }
    }

    /**
     * Update employee
     * @param UpdateEmployee $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmployee(UpdateEmployee $request, $id)
    {
        $this->validate($request, [
            'email' => Rule::unique('users')->ignore($id, 'id'),
            'email' => Rule::unique('employees')->ignore($id, 'id'),
        ]);

        $employee = Employee::findOrFail($id);
        $employee->name = $request->get('name');
        $employee->phone = $request->get('phone');
        $employee->email = $request->get('email');
        $employee->address = $request->get('address');
        if ($employee->save()) {
            $user = User::find($employee->user->id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->active = $request->get('status') == 'on' ? 1 : 0;
            $user->role = $request->get('role');
            if ($request->get('password') != "") {
                $user->password = Hash::make($request->get('password'));
            }
            if ($request->hasFile('thumbnail')) {
                $user->image = $request->file('thumbnail')
                    ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
            }
            if ($user->save()) {
                Mail::to($user->email)->send(new EmployeRegister($user->email, $request->get('password')));
                return response()->json('Ok', 200);
            }
        }
    }
}
