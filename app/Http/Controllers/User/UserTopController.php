<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserTopController extends Controller
{
    public function index() {
        return view('user.top');
    }
}
