<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\MUserTalkGroupRequest;
use App\Models\MUserTalkGroup;
use App\Models\MUserRequest;
use App\Models\MUser;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use Storage;
use DB;

class MUserTalkGroupRequestController extends Controller
{
    public function getList()
    {
        if ($request_id = request()->get('request_id'))
        {
            $userRequest = MUserRequest::find(request()->get('request_id'));
            if (!$userRequest) abort(404);
        }

        $customAddedGroups =[];
        $addedGroups = MUserTalkGroupRequest::where('request_id', $userRequest->id )
        ->select('voip_group_id')
        ->get();
        foreach ($addedGroups as $key => $value) {
            $customAddedGroups[] = $value->voip_group_id;
        }

        if (!empty($userRequest)) {
            $listTalkGroup = MUserTalkGroup::where('user_id', $userRequest->user_id)
                ->where('shop_id', $userRequest->shop_id)
                ->whereNotNull('voip_group_id')
                ->paginate();

            $listTalkGroup->appends(['request_id' => $request_id]);
        } else {
            $userRequest = null;
            $listTalkGroup = MUserTalkGroupRequest::paginate();
        }

        return view('shop.user.list', compact('listTalkGroup', 'userRequest', 'customAddedGroups'));
    }

    public function search(Request $request)
    {
        $talkGroup = MUserTalkGroupRequest::search();
        $keyword = $request->get('s');
        return view('shop.user.result_searching', compact('talkGroup', 'keyword'));
    }

    public function getDetail(Request $request)
    {

        $id = intVal($request->get('id'));
        $talkGroup = MUserTalkGroup::where('id', $id)->first();

        //
        $userId = $talkGroup->user_id;
        $user = MUser::find($userId);

        return view('shop.user.detail', compact('talkGroup', 'user'));
    }

    public function input(Request $request)
    {
        // get shop user
        $requestId = $request->get('request_id');
        $user = MUser::find($request->user_id);
        $userId = $request->get('user_id');
        $shopId = Auth::guard('shop')->user()->id;
        return view('shop.user.add', compact('requestId', 'userId', 'shopId', 'user'));
    }

    public function update($id)
    {
        
        $idTalkGroup = $id;

        $talkGroup = MUserTalkGroupRequest::where('id', $idTalkGroup)->first();
        
        // get shop user
        $userId = $talkGroup->user_id;
        $user = MUser::find($userId);

        return view('shop.user.add', compact('talkGroup', 'idTalkGroup', 'user'));
    }

    public function checkTalkGroupExist($talkGroupName, $userId, $id)
    {

        $query = MUserTalkGroupRequest::where('name', $talkGroupName);

        if(!empty($id)) {
            $query = $query->where('id', '!=', $id);
        }

        if(!empty ($userId)) {
            $query = $query->where('user_id', $userId);
        }

        $talkGroup = $query->first();
    
        return $talkGroup;
    }

    public function checkTalkGroupExistInAddExist($talkGroupName, $userId)
    {

        $query = MUserTalkGroup::where('name', $talkGroupName);

        if(!empty ($userId)) {
            $query = $query->where('user_id', $userId);
        }

        $talkGroup = $query->first();
        return $talkGroup;
    }

    public function store(Request $request)
    {
        
        $validator = validator()->make(request()->all(), MUserTalkGroupRequest::columnConstraints());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $idTalkGroup = null;

        if ($request->get('idTalkGroup')) {
            $idTalkGroup = $request->get('idTalkGroup');
        }

        $talkGroup = $request->all();

        $data = $request->all();

        // check talk group name exist in talk group request
        $checkTalkGroupInTalkGroupRequest = null;
        $checkTalkGroupInTalkGroupExist = null;

        if(!empty($data['idTalkGroup'])){
            $checkTalkGroupInTalkGroupRequest = $this->checkTalkGroupExist($data['groupName'], $data['userId'], $data['idTalkGroup']);
            $talkGroup = MUserTalkGroupRequest::where('id', $data['idTalkGroup'])->first();
        }
        else{
            $checkTalkGroupInTalkGroupRequest = $this->checkTalkGroupExist($data['groupName'], $data['userId'] , null);
            $talkGroup = new MUserTalkGroupRequest();
        }

        $checkTalkGroupInTalkGroupRequestInTalkGroupExist = $this->checkTalkGroupExistInAddExist($data['groupName'], $data['userId']);

        if(!empty($checkTalkGroupInTalkGroupRequest) || !empty($checkTalkGroupInTalkGroupExist)) {
            return redirect()->back()->with('error', __('Choose another group name'))->withInput();
        }
        
        $talkGroup->request_id = $data['requestId'];
        $talkGroup->user_id = $data['userId'];
        $talkGroup->shop_id = $data['shopId'];
        $talkGroup->name = $data['groupName'];
        $talkGroup->priority = $data['groupPriority'];

        if(!empty($data['groupMemberView'])){
            $talkGroup->member_view = $data['groupMemberView'];
        } else{
            $talkGroup->member_view = null;
        }
        
        if(!empty($data['rowNum'])){
            $talkGroup->row_num = $data['rowNum'];
        } else {
            $talkGroup->row_num = null;
        }

        $talkGroup->request_type = $data['requestType'];

        if(!empty($data['groupResponsiblePerson'])){
            $talkGroup->group_responsible_person = $data['groupResponsiblePerson'];
        } else {
            $talkGroup->group_responsible_person = null;
        }
        
        $talkGroup->save();
       
        if (!$talkGroup) {
            return redirect()->back()->with('error', __('error happen'));
        }

        if (empty($request->get('idTalkGroup'))) {

            return redirect()->route('shop.applicationDetail', ['id' => $talkGroup->request_id])->with('success', __('success add new req talk group'));
        }

        return redirect()->route('shop.applicationDetail', ['id' => $talkGroup->request_id])->with('success', __('success update req talk group'));
    }

    public function handleAddExistedGroup(Request $request)
    {

        $group = MUserTalkGroup::find($request->id);
        $this->checkValidItem($group);

        $userRequest = MUserRequest::find($request->request_id);
        $this->checkValidItem($userRequest);

        if($group->user_id != $userRequest->user_id) abort(403);

        $addedGroups = MUserTalkGroupRequest::where('request_id', $userRequest->id )
        ->select('voip_group_id')->where('voip_group_id', $group->voip_group_id)->count();
        if($addedGroups > 0) abort(403);

        $groupRequest = new MUserTalkGroupRequest();
        $groupRequest->created_at = Carbon::now();
        $groupRequest->request_id = $userRequest->id;
        $groupRequest->user_id = $userRequest->user_id;
        $groupRequest->shop_id  = $userRequest->shop_id;
        $groupRequest->voip_group_id  = $group->voip_group_id;
        $groupRequest->priority  = $group->getRawValue('priority');
        $groupRequest->member_view  = $group->getRawValue('member_view') ;
        $groupRequest->group_responsible_person  = $group->group_responsible_person;
        $groupRequest->status  = $group->status;
        $groupRequest->request_type  = 2;
        $groupRequest->name = $group->name;

        if ($groupRequest->save()) {
            return redirect()->route('shop.applicationDetail', ['id' => $userRequest->id])->with( 'success', __('Add group successfully'));
        }

        return redirect()->back()->with('error', __('Error! Please try again'));
    }

    public function handleDelete(Request $request)
    {
        $groupRequest = MUserTalkGroupRequest::find($request->id);
        $this->checkValidItem($groupRequest);

        if (in_array($groupRequest->id, $groupRequest->request->getGroupIdsAddedToLine())) {
            return redirect()->back()->with('error', __('note_cant_delete_talk_group'));
        }

        if ($groupRequest->delete()) {
            return redirect()->back()->with('success', __('deleted successful'));
        }

        return redirect()->back()->with('error', __('fail delete'));
    }

    private function checkValidItem($groupRequest)
    {
        if (!$groupRequest) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->id !== $groupRequest->shop_id) abort(403);
    }

    // public function addFeedback()
    // {
    //     $listUsers = DB::table('m_users')->where('id', 2)->get();
       
    //     foreach ($listUsers as $key => $value) {
    //         $data = ['content' => '', 'title' => 'Notice of confirmation of billing amount'];
           
    //         Mail::send('admin.mail_contractor', $data, function($message) use ($value) {
    //             $message->to($value->email)->subject('Notice of confirmation of billing amount');
    //             $message->attach(public_path('uploads/aa.pdf'), [
    //                 'as' => 'aa.pdf',
    //                 'mime' => 'application/pdf',
    //            ]);
    //             $message->from(config('mail.from.address'));
    //         });
    //     }
    // }
}
