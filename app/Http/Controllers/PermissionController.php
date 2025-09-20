<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Show all permissions
    public function index()
    {
        return response()->json(Permission::all());
    }
}
