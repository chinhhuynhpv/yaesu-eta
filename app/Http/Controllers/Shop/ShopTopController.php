<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

class ShopTopController extends Controller
{
    function index()
    {
        //return view('shop/top');
        return redirect('/user/list');
    }

}
