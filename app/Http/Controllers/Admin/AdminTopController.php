<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminTopController extends Controller
{
    function index()
    {
        return view('admin/top');
    }
}
