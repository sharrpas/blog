<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::all());
    }
}
