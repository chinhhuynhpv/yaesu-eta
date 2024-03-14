<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\MUserTalkGroup;
use App\Models\MUserTalkGroupRequest;
use App\Models\MUser;

class OperatorTalkGroupController extends Controller
{
    public function detail(Request $request)
    {
        $idTalkGroup  = $request->get('id');

        $talkGroup = MUserTalkGroup::find($idTalkGroup);
       
        return view('operator.talk_group.detail', compact('talkGroup'));
    }

    public function edit(Request $request)
    {
        $idTalkGroup = $request->get('id');
        $userId = $request->get('user_id');
        $talkGroup = MUserTalkGroup::find($idTalkGroup);

        $user = MUser::where('id', $userId)->first();
       
        return view('operator.talk_group.input', compact('talkGroup', 'userId', 'user'));
    }

    private function checkGroupIdExist($voipGroupId, $idTalkGroup)
    {
        $talkGroup = MUserTalkGroup::where('voip_group_id', $voipGroupId)->where('id', '!=', $idTalkGroup)->first();
        return $talkGroup;
    }

    public function checkGoupIdStartWithSpecified6($voipGroupId) {
        if($voipGroupId[0] == '6') {
            return true;
        } 
        return false;
    }

    public function checkGoupNameExistByUserId($groupName, $userId, $idTalkGroup) {
        $talkGroup = MUserTalkGroup::where('name', $groupName)->where('user_id', $userId)->first();
        
        $query = MUserTalkGroup::where('name', $groupName)->where('user_id', $userId);

        if(!empty($idTalkGroup)) {
            $query = $query->where('id', '!=', $idTalkGroup);
        }

        $talkGroup = $query->first();
        
        return $talkGroup;
    }

    public function store(Request $request)
    {

        $validator = validator()->make(request()->all(), MUserTalkGroup::columnConstraints());
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $idTalkGroup = $request->get('idTalkGroup');
        $groupName = $request->get('groupName');
        $userId =  $request->get('userId');

        // check if group Id exist
        $voipGroupId = $request->get('groupId');

        // check if group id start with charater 6
        $checkVoipGroupId = $this->checkGoupIdStartWithSpecified6($voipGroupId); 
        if($checkVoipGroupId == false) {
            return redirect()->back()->with('error', 'Group Id needs to start with character 6 !')->withInput();
        }
        // Check if the Talk Group exists
        $checkExistTalkGroup = $this->checkGroupIdExist($voipGroupId, $idTalkGroup);

        if(!empty($checkExistTalkGroup)) {
            return redirect()->back()->with('error', __('Group Id has exist !'))->withInput();
        }
        // Check if the Talk Group name exists
        $checkExistTalkGroupName = $this->checkGoupNameExistByUserId($groupName, $userId, $idTalkGroup);

        if(!empty($checkExistTalkGroupName)) {
            return redirect()->back()->with('error', __('Group Name has exist !'))->withInput();
        }
        $data = $request->all();
        
        $talkGroup = MUserTalkGroup::where('id', $data['idTalkGroup'])->first();
        $talkGroup->voip_group_id = $data['groupId'];
        $talkGroup->user_id = $data['userId'];
        $talkGroup->shop_id = $data['shopId'];
        $talkGroup->name = $data['groupName'];
        $talkGroup->priority = $data['groupPriority'];

        if(!empty( $data['groupMemberView']))
            $talkGroup->member_view = $data['groupMemberView'];
            
        $talkGroup->group_responsible_person = $data['groupResponsiblePerson'];
        
        if(!empty( $data['groupStatus']))
            $talkGroup->status = $data['groupStatus'];
        
        $talkGroup->save();
        
        if (!$talkGroup) {
            return redirect()->back()->with('error', 'error happen');
        }
        if(!empty($request->get('currentUserId'))) {
            $currentUserId = $request->get('currentUserId');
            return redirect()->route('admin.lineList',['id'=>$currentUserId])->with('success', 'success update talk group');
        }
        return redirect()->route('admin.lineList')->with('success', 'success update talk group');
    }
}
