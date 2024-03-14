<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\HandleAuthenInterface;
use App\Traits\HandleAuthen;

class AdminAuthController extends Controller implements HandleAuthenInterface
{
    use HandleAuthen;

    public function login()
    {
        return view('admin/authen/login');
    }

    public function _redirectTo()
    {
        return redirect()->route('admin.top');
    }

    public function _guard()
    {
        return 'admin';
    }
}
