<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait HandleAuthen
{
    public function handleLogin(Request $req) {
        if(Auth::guard($this->_guard())->attempt($req->only($this->_credentialFields()))) {
            return $this->_redirectTo();
        }

        return redirect()
            ->back()
            ->with('error', __('Invalid Credentials'));
    }

    public function logout() {
        Auth::guard($this->_guard())->logout();
        return redirect()->route("{$this->_guard()}.login");
    }

    public function _credentialFields() {
        return ['email', 'password'];
    }
}
