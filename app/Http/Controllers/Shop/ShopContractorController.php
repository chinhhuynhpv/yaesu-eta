<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\ContractorManagementController;
use App\Models\MUser;
use App\Models\MUserRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ShopContractorController extends ContractorManagementController
{
    protected $guard = 'shop';
    protected $rootDir = 'shop/contractor/';

    public function list()
    {
        $contract_id = request()->query('contractor_id');
        $contract_name = request()->query('contract_name');

        $currentUser = Auth::guard('shop')->user();
        $users = MUser::where('shop_id', $currentUser->shop_id)
            ->orderBy('contract_name_kana')
            ->orderBy('id', 'desc')
            ->where('contractor_id', 'like', "%$contract_id%" )
            ->where('contract_name', 'like', "%$contract_name%" )
            ->paginate();
        
        $users->appends(['contractor_id' => $contract_id, 'contract_name' => $contract_name]);

        return view('shop/contractor/list', compact('users'));
    }

    public function handleConfirm(Request $req)
    {
        
        $columnConstraints = $this->getRule();

        if ($req->id) {
            $user = MUser::find($req->id);
            $this->_checkHtmlStatus($user);
           
            // check email by shop 
            // $this->checkEmailExistByShop();

            $columnConstraints['password'] = 'confirmed|max:255';
        } else {
            $user = new MUser();
            $user->contractor_id = $user->generateContracId();
        }

        $validator = validator()->make(request()->all(), $columnConstraints);

        if ($validator->fails()) {
             return redirect()->back()->withErrors($validator);
        }

        $data = $req->all();

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        $currentUser = Auth::guard('shop')->user();
        $data['shop_id'] = $currentUser->shop_id;
        $data['status'] = 1;

        $user->setPrefectures($data);
        $res = $user->smartSave($data);
        
        if ($res === true)  return  redirect()->route('shop.userComplete')->with(['status' => 'completed', 'message' => $req->id ? __('Update user successfully') : __("Create user successfully")]);
        
        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    protected function _checkHtmlStatus($user)
    {
        if (!$user) abort(404);

        $currentUser = Auth::guard('shop')->user();
        if ($currentUser->shop_id != $user->shop_id) abort(403);
    }

    protected function getRule()
    {
        return MUser::columnConstraints(false);
    }
}
