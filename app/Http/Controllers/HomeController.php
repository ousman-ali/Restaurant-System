<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * Show the dashboard /home
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        return view('home');
    }


    /**
     * Change user password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        if (Hash::check($request->get('current_password'), auth()->user()->password)) {
            $user = User::find(auth()->user()->id);
            $user->password = Hash::make($request->get('new_password'));
            if ($user->save()) {
                return response()->json('Ok', 200);
            }
        } else {
            return response()->json('Ok', 500);
        }
    }

    /**
     * View user profile
     * @return Factory|View
     */
    public function profileInfo()
    {
        return view('user.profile.profile');
    }

    /**
     * View user information edit page
     * @return Factory|View
     */
    public function profileEdit()
    {
        return view('user.profile.edit-profile');
    }

    /**
     * Update user profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        if ($request->hasFile('thumbnail')) {
            $user->image = $request->file('thumbnail')
                ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
        }
        if ($user->save()) {
            $employee = Employee::where('user_id', $user->id)->first();
            $employee->name = $user->name;
            $employee->phone = $request->get('phone');
            $employee->email = $user->email;
            $employee->address = $request->get('address');
            if ($employee->save()) {
                return response()->json('Ok', 200);
            }

        }
    }

    /**
     * Update admin profile info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminProfileUpdate(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        if ($request->hasFile('thumbnail')) {
            $user->image = $request->file('thumbnail')
                ->move('uploads/employee', rand(100000, 900000) . '.' . $request->thumbnail->extension());
        }
        if ($user->save()) {
            return response()->json('Ok', 200);
        }
    }


    /**
     * View account disable page if user account is disable
     * @return Factory|View
     */
    public function accountDisable()
    {
        return view('other.disable-account');
    }

    /**
     * Show install success when install is success
     * @return View
     */
    public function installSuccess(Request $request): View
    {
        return view('install-success');
    }


}
