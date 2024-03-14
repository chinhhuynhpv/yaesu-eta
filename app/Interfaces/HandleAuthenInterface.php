<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HandleAuthenInterface
{
    public function login();
    public function handleLogin(Request $req);
    public function _redirectTo();
    public function _guard();
    public function _credentialFields();
}
