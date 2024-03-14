<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Interfaces\HandleAuthenInterface;
use App\Traits\HandleAuthen;

class ShopAuthController extends Controller implements HandleAuthenInterface
{
    use HandleAuthen;

    public function login()
    {
        return view('shop/authen/login');
    }

    public function _redirectTo()
    {
        return redirect()->route('shop.top');
    }

    public function _guard() {
        return 'shop';
    }

    public function _credentialFields()
    {
        return ['shop_code', 'password'];
    }
}
