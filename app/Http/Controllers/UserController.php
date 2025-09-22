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
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Add new employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addEmployee()
    {
        $roles = Role::all();
        return view('user.admin.employee.add-employee', compact('roles'));
    }

    /**
     * View all employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allEmployees()
    {
        $employees = Employee::latest()->get();
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
        $roles = Role::all();
        $user = $employee->user;
        $currentRoleId = $user->role; // Assuming you store role ID in users table

        return view('user.admin.employee.edit-employee', [
            'employee' => $employee,
            'roles' => $roles,
            'currentRoleId' => $currentRoleId,
        ]);
    }

    /**
     * Delete employee
     * @param $id
     */
    public function deleteEmployee(Request $request)
    {
       
        $user = User::findOrFail($request->id);
        $user_in_order = Order::where('user_id', $user->id)
            ->orWhere('served_by', $user->id)
            ->orWhere('kitchen_id', $user->id)
            ->first();
        $user_in_dish = Dish::where('user_id', $request->id)->first();
        $user_id_product = Product::where('user_id', $request->id)->first();
        $user_in_product_type = ProductType::where('user_id', $request->id)->first();
        $user_in_purses = Purse::where('user_id', $request->id)->first();
        $user_in_puirses_payment = PursesPayment::where('user_id', $request->id)->first();
        $user_in_recipe = Recipe::where('user_id', $request->id)->first();
        $user_in_stock = Stock::where('user_id', $request->id)->first();
        $user_in_supplier = Supplier::where('user_id', $request->id)->first();
        $user_in_tbale = Table::where('user_id', $request->id)->first();
        $user_in_unit = Unit::where('user_id', $request->id)->first();
        // $user_in_employee = Employee::where('user_id', $request->id)->first();
        

        if ($user_in_order || $user_in_dish || $user_id_product || $user_in_product_type || $user_in_purses
            || $user_in_puirses_payment || $user_in_recipe || $user_in_stock || $user_in_supplier || $user_in_tbale
            || $user_in_unit
        ) {
            return redirect()->back()->with('delete_error', 'You cannot delete this user');
        } else {
            if ($user->role != 1) {
                Employee::destroy($user->employee->id);
                User::destroy($request->id);
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
        // Remove $user->role if using Spatie roles
        if ($request->hasFile('thumbnail')) {
            $user->image = $request->file('thumbnail')
                ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
        }
        if ($user->save()) {
            // Assign role using Spatie (needs role name)
            $role = Role::find($request->get('role'));
            if ($role) {
                $user->assignRole($role->name);
                $user->syncPermissions($role->permissions);
            }

            $employee = new Employee();
            $employee->name = $user->name;
            $employee->rest_type = $request->rest_type;
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
                return redirect('/all-employee')->with('save_success', 'Employee added successfully.');
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
        $employee->rest_type = $request->get('rest_type');
        if ($employee->save()) {
            $user = User::find($employee->user->id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->active = $request->get('status') == 'on' ? 1 : 0;
            $user->role = $request->get('role');
            // $user->role = $request->get('role');
            if ($request->get('password') != "") {
                $user->password = Hash::make($request->get('password'));
            }
            if ($request->hasFile('thumbnail')) {
                $user->image = $request->file('thumbnail')
                    ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
            }
            if ($user->save()) {
                 $role = Role::find($request->get('role'));
                if ($role) {
                    $user->syncRoles([$role->name]);
                    $user->syncPermissions($role->permissions);
                }
                // Mail::to($user->email)->send(new EmployeRegister($user->email, $request->get('password')));
                return redirect('/all-employee')->with('save_success', 'Employee updated successfully.');
            }
        }
    }

    //assign roles to the user
    public function assignRoleToUser(Request $request, User $user)
    {
        // Validate input
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Remove all current roles & permissions (optional, depends on your logic)
        $user->syncRoles([$request->role]); // assigns the role and removes previous roles

        // Optional: permissions are automatically assigned through the role
        // But if you want to explicitly sync permissions of the role:
        $role = Role::findByName($request->role);
        $user->syncPermissions($role->permissions);

        return response()->json([
            'message' => "Role '{$request->role}' assigned to user '{$user->name}' successfully.",
            'user_roles' => $user->getRoleNames(),
            'user_permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
