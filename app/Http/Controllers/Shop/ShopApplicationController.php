<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\MUser;
use App\Models\MUserRequest;
use App\Traits\HandleSearchedItem;
use Auth;
use Illuminate\Http\Request;

class ShopApplicationController extends Controller
{
    use HandleSearchedItem;

    public function list(Request $req)
    {
        $possibleStatuses = [__('temporary'), __('application'), __('decline'), __('configured')];

        $status = $this->checkSearchedItem($req->query('status'), $possibleStatuses);

        if ($status === false) {
            abort(404);
        }

        $user = MUser::find($req->get('user_id'));
        $this->checkValidItem($user);

        $shop = Auth::guard('shop')->user();
        $query = MUserRequest::join('m_users', 'user_id', 'm_users.id')
            ->where('m_user_requests.shop_id', $shop->shop_id)
            ->where('m_user_requests.user_id', $user->id);

        $appendParams = ['user_id' => $user->id];

        if ($status !== null) {
            $query = $query->where('m_user_requests.status', $status + 1);
            $appendParams['status'] = $possibleStatuses[$status];
        }

        $userRequests = $query->orderBy('status')
            ->orderBy('request_number', 'desc')
            ->orderBy('id', 'desc')
            ->select('m_user_requests.*')
            ->search();

        $userRequests->appends($appendParams);
        $countApplicationsInProccessing = $this->countApplicationsInProccessing($user->id);

        return view('shop/application/list', compact('userRequests', 'user', 'possibleStatuses', 'countApplicationsInProccessing'));
    }

    public function detail($id)
    {
        $userRequest = MUserRequest::find($id);
        $this->checkValidItem($userRequest);
        $userRequest->mergeInput(request()->old());

        return view('shop/application/detail', compact('userRequest'));
    }

    public function input(Request $req)
    {
        $user = MUser::find($req->get('user_id'));
        $this->checkValidItem($user);

        if ($this->countApplicationsInProccessing($user->id)) {
            abort(403);
        }

        $userRequest = new MUserRequest();
        $userRequest->user = $user;
        $userRequest->request_number = MUserRequest::generateRequestNumber();
        $userRequest->mergeInput($req->old());

        return view('shop/application/input', compact('userRequest'));
    }

    public function edit(Request $req)
    {
        $userRequest = MUserRequest::find($req->id);
        $this->checkValidItem($userRequest);
        $userRequest->mergeInput(request()->old());

        return view('shop/application/input', compact('userRequest'));
    }

    public function handleInput(Request $req)
    {
        $user = MUser::find($req->user_id);
        $this->checkValidItem($user);

        if ($this->countApplicationsInProccessing($user->id)) {
            abort(403);
        }

        $columnConstraints = MUserRequest::columnConstraints();
        $columnConstraints['user_id'] = 'required|exists:m_users,id';
        $req->validate($columnConstraints);

        $shop = Auth::guard('shop')->user();

        $data = $req->all();
        $data['shop_id'] = $shop->id;
        $data['status'] = 1;
        $data['request_number'] = MUserRequest::generateRequestNumber();

        $userRequest = new MUserRequest();
        $res = $userRequest->smartSave($data);

        if ($res) return redirect()->route('shop.applicationList', ['user_id' => $user->id])->with('success', __('Create the application successfully'));

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    public function handleEdit(Request $req)
    {
        $userRequest = MUserRequest::find($req->id);
        $this->checkValidItem($userRequest);

        $status = $userRequest->getRawValue('status');

        if ($status == "2") {
            return redirect()->back()->withInput()->with('error', __('You are in status 申請中'));
        }

        if ( !empty($req->submit['app'])
            && $userRequest->talk_group_requests->count() == 0
            && $userRequest->line_requests->count() == 0
            && $userRequest->line_talk_group_requests->count() == 0
        ) {
            return redirect()->back()->withInput()->with('error', __('You need to input talk group, line ID or line talk group'));
        }

        if (!empty($req->submit['temp']) && $status == '3') {
            return redirect()->back()->withInput()->with('error', __('You can`t change status from decline to temporary'));
        }

        if (isset($req->submit) && $this->countApplicationsInProccessing($userRequest->user_id, $userRequest->id)) {
            return redirect()->back()->withInput()->with('error', __('This user has another request in temporary or application status'));
        }

        $columnConstraints = MUserRequest::columnConstraints();
        $req->validate($columnConstraints);

        $data = $req->all();

        if (!isset($req->submit)) {
            $data['add_flg'] = $data['add_flg'] ?? 0;
            $data['modify_flg'] = $data['modify_flg'] ?? 0;
            $data['pause_restart_flg'] = $data['pause_restart_flg'] ?? 0;
            $data['discontinued_flg'] = $data['discontinued_flg'] ?? 0;
            unset($data['status']);
        }
        else {
            if (isset($req->submit['app'])) {
                $data['status'] = 2;
            }
            else if ($req->submit['temp']) {
                $data['status'] = 1;
            }
        }

        $res = $userRequest->smartSave($data);

        if ($res) return redirect()->route('shop.applicationList', ['user_id' => $userRequest->user->id])->with('success', __('Update successfully'));

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    private function checkValidItem($user) {
        if (!$user) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->shop_id != $user->shop_id) abort(403);
    }

    private function countApplicationsInProccessing($userId, $userRequestId = 0 ) {
        return MUserRequest::where('user_id', $userId)->where('id', '!=', $userRequestId)->where('status', '!=' , '4')->count();
    }
}
