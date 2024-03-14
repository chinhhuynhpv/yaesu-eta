<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Interfaces\HandleAuthenInterface;
use App\Traits\HandleAuthen;

class UserAuthController extends Controller implements HandleAuthenInterface
{
    use HandleAuthen;

    public function login() {
        return view('user/authen/login');
    }

    public function _redirectTo()
    {
        return redirect()->route('user.top');
    }

    public function _guard() {
        return 'user';
    }
}
