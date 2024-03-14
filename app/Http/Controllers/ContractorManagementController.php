<?php

namespace App\Http\Controllers;

use App\Helper\Prefectures;
use App\Models\MUser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class ContractorManagementController extends Controller
{
    protected $guard;
    protected $rootDir;
    protected $ignoredUpdatedFields = [];

    public function list()
    {
        $users = MUser::orderBy('id', 'desc')->search();

        return view($this->rootDir . 'list', compact('users'));
    }

    public function detail($id)
    {
        $user = MUser::find($id);
        $this->_checkHtmlStatus($user);
        return view($this->rootDir . 'detail', compact('user'));
    }

    public function input(Request $req)
    {
        
        $user = new MUser();
        $user->contractor_id = MUser::generateContracId();
        $user->mergeInput($req->old());
        
        $prefectures = new Prefectures();
        
        return view($this->rootDir . 'input', compact('user', 'prefectures'));
    }

    public function checkEmailExistByShop($email, $shopId, $id)  {
       $query = MUser::where('email', $email)->where('shop_id', $shopId);

       if($id != null) {
            $query= $query->where('id', '!=', $id);
       }

       $check = $query->first();

        return $check;
    }

    public function handleInput(Request $req)
    {   
        $shop = Auth::guard('shop')->user();
        $checkEmail = $this->checkEmailExistByShop($req->get('email'), $shop->id, null);
        
        if(!empty($checkEmail)) {
            return redirect()->back()->with('error', __('Email is in use. Please choose another email!'))->withInput();
        }

        $req->validate($this->getRule());
        return redirect()->route("{$this->guard}.userConfirm")->withInput();
    }

    public function edit(Request $req, $id)
    {
        $user = MUser::find($id);
        $this->_checkHtmlStatus($user);

        $user->mergeInput($req->old());
        
        $prefectures = new Prefectures();

        return view($this->rootDir . 'input', compact('user', 'prefectures'));
    }

    public function handleEdit(Request $req)
    {
        
        $columnConstraints = $this->getRule();
        $this->_updatedColumnConstraints($columnConstraints);

        if ($req->id) {
            $shop = Auth::guard('shop')->user();
            $checkEmail = $this->checkEmailExistByShop($req->get('email'), $shop->id, $req->get('id'));

            if(!empty($checkEmail)) {
                return redirect()->back()->with('error', __('Email is in use. Please choose another email!'))->withInput();
            }
            
            $columnConstraints['password'] = 'confirmed|max:255';
        }

        $req->validate($columnConstraints);

        return redirect()->route("{$this->guard}.userConfirm")->withInput();
    }

    public function handleDelete(Request $req)
    {
        $user = MUser::find($req->id);
        $this->_checkHtmlStatus($user);

        return redirect()->route("{$this->guard}.userConfirm")->with(['action' => 'delete', 'id' => $req->id]);
    }

    public function confirm(Request $req)
    {
        //Confirm delete
        if (session()->has('action') && session('action') == 'delete') {
            $user = MUser::find(session('id'));

            if ($user) {
                return view($this->rootDir . 'confirm-delete', compact('user'));
            }

            return redirect()->route("{$this->guard}.userDetail")->with('error', __('Session expired! Please input again'));
        }
        
        $input = $req->old();
        $columnConstraints = $this->getRule();
        
        if (isset($input['id'])) {
            $user = MUser::find($input['id']);
            $this->_updatedColumnConstraints($columnConstraints);

            if (array_key_exists('password', $columnConstraints)) {
                $columnConstraints['password'] = 'confirmed|max:255';
            }

        } else {
            $user = new MUser();
            $user->contractor_id = MUser::generateContracId();
        }

        unset($columnConstraints['shipping_municipalities']);
        unset($columnConstraints['billing_municipalities']);

        $validator = validator()->make($input, $columnConstraints);
        $user->mergeInput($input);
       
        if ($validator->fails()) {
            return redirect()->route("{$this->guard}.userList")->with('error', __('Session expired! Please input again'));
        }
        
        $prefectures = new Prefectures();

        return view($this->rootDir . 'confirm', compact('user', 'prefectures'));
    }

    abstract public function handleConfirm(Request $req);

    public function handleConfirmDelete(Request $req)
    {
        $user = MUser::find($req->id);
        $this->_checkHtmlStatus($user);
        $res = $user->delete();

        if ($res === true)  return  redirect()->route("{$this->guard}.userComplete")->with(['status' => 'completed', 'message' => __('Delete user successfully')]);

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    public function complete()
    {
        if (session()->has('status') && session('status') == 'completed') return view($this->rootDir . 'complete');

        return redirect()->route("{$this->guard}.userList")->with('error', __('Session expired! Please input again'));
    }

    protected function _updatedColumnConstraints(&$columnConstraints) {
        foreach ($this->ignoredUpdatedFields as $field) {
            unset($columnConstraints[$field]);
        }
    }

    abstract protected function _checkHtmlStatus($user);

    abstract protected function getRule();
}
