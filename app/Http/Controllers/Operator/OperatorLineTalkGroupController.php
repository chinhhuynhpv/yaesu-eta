<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Auth;
use App\Models\MUserLineTalkGroup;
use App\Models\MUserTalkGroup;
use App\Models\MUserLine;
use App\Models\MUser;
use Illuminate\Http\Request;
use DB;

class OperatorLineTalkGroupController extends Controller
{

    public function edit(Request $request)
    {
        $idLineTalkGroup = $request->get('id');
        $lineTalkGroup = MUserLineTalkGroup::find($idLineTalkGroup);
        $userId = null;
        $userId = $request->get('user_id');
        $lineLists = MUserLine::all();
        
        $talkGroups = MUserTalkGroup::all();
        $lineInformation = MUserline::where('id', $lineTalkGroup->line_id)->first();
        
        $query = DB::table('m_user_line_talk_group')
                ->join('m_user_lines', 'm_user_line_talk_group.line_id', 'm_user_lines.id')
                ->join('m_user_talk_groups', 'm_user_line_talk_group.group_id', 'm_user_talk_groups.id')
                ->where('m_user_line_talk_group.deleted_at', null);

        if(!empty($lineTalkGroup)){
            $query = $query->where('m_user_line_talk_group.line_id', $lineTalkGroup->line_id);
        }

        $listTalkGroups = $query->orderBy('m_user_line_talk_group.line_id', 'asc')
                                ->orderBy( 'm_user_line_talk_group.number', 'asc')
                                ->select('m_user_line_talk_group.id as ilt', 'm_user_line_talk_group.*', 'm_user_lines.*', 'm_user_talk_groups.*')
                                ->get();
        
        $user = MUser::where('id', $userId)->first();

        return view('operator.line_talk_group.input', compact(  'lineTalkGroup', 'lineLists', 
                                                                'talkGroups', 'lineInformation', 
                                                                'listTalkGroups', 'userId', 'user'));
    }

    public function updateGroupOwner($data)
    {
       
        $groupOwner = MUserLineTalkGroup::withTrashed()
                                        ->where('line_id', $data['lineId'])
                                        ->where('number', 1)
                                        ->first();
        if(empty($groupOwner)) {
            $groupOwner = new MUserLineTalkGroup();
            $groupOwner->line_id = $data['lineId'];
            $groupOwner->user_id = $data['userId'];
            $groupOwner->shop_id = $data['shopId'];
            $groupOwner->number = 1;

        }
        $groupOwner->group_id = $data['groupMain'];
        $groupOwner->deleted_at = null;
        $groupOwner->save();
        
        return $groupOwner;
    }

    public function store(Request $request)
    {
       
        $validator = validator()->make(request()->all(), MUserLineTalkGroup::columnConstraints());
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->all();
        
        if(!empty($data['selectGroup'])) {

            $data['selectGroup'] = array_diff($data['selectGroup'], array($request->get('groupMain')));
            
            $data['selectGroup'] = array_values(array_filter($data['selectGroup']));
        }
       
        DB::beginTransaction();
        
        try {
            // update group main
            $lineTalkGroupMain = $this->updateGroupOwner($data);
           
            // update the group select
            $lineTalkGroupForUpdate = null;
    
            if(!empty($data['selectGroup'])) {

                $lineTalkGroupForUpdate = $this->getTheLineTalkGroupForUpdate($data['lineId']);
               
                $lineTalkGroupAddReqForDelete = array_values(array_diff($lineTalkGroupForUpdate, $data['selectGroup']));
               
                // delete Line Talk Group Add Req into database
                if(!empty( $lineTalkGroupAddReqForDelete))
                {
                    $this->deleteTheLineTalkGroupAddReq($lineTalkGroupAddReqForDelete, $data['lineId']);
                }

                // update and add Line Talk Group  into database
                $this->updateAndAddTheLineTalkGroup($lineTalkGroupMain, $data['selectGroup'], $data['lineId'], $data['requestId']);
               

            }else{
                $lineTalkGroupAddReqForDelete = $this->getTheLineTalkGroupForUpdate($data['lineId']);

                // delete Line Talk Group Add Req into database
                if(!empty( $lineTalkGroupAddReqForDelete))
                {
                    $this->deleteTheLineTalkGroupAddReq($lineTalkGroupAddReqForDelete, $data['lineId']);
                }
            }

            DB::commit();
            
            if(!empty($request->get('currentUserId'))) {
                $currentUserId = $request->get('currentUserId');
                return redirect()->route('admin.lineList',['id'=>$currentUserId])->with('success', __('success update line talk group'));
            }
            return redirect()->route('admin.lineList')->with('success', __('success update line talk group'));

        } catch (Exception $e) {

            DB::rollBack();
            
            throw new Exception($e->getMessage());

            return redirect()->back()->with('error','error happen');
        }
    }

    public function updateAndAddTheLineTalkGroup($lineTalkGroupMain, $arraySelectGroup, $voipLineId, $requestId)
    {
        
        foreach($arraySelectGroup as $key => $item)
        {
            $lineTalkGroup = MUserLineTalkGroup::withTrashed()
                                ->where('line_id',$voipLineId)
                                ->where('group_id', $item)
                                ->first();
            
            if(!empty($lineTalkGroup))
            {
                $lineTalkGroup->number =  $key + 2;
                if($lineTalkGroup->trashed()){
                    $lineTalkGroup->restore();
                }else{
                    $lineTalkGroup->save();
                }
                
            }else{
                $lineTalkGroup = new MUserLineTalkGroup();
                $lineTalkGroup->request_id = $requestId;
                $lineTalkGroup->line_id = $voipLineId;
                $lineTalkGroup->user_id = $lineTalkGroupMain->user_id;
                $lineTalkGroup->shop_id = $lineTalkGroupMain->shop_id;
                $lineTalkGroup->group_id = $item;
                $lineTalkGroup->number =  $key + 2;
                $lineTalkGroup->save();
            }

        }
    }

    public function deleteTheLineTalkGroupAddReq($lineTalkGroupForDelete, $voipLineId)
    {
        foreach($lineTalkGroupForDelete as $item)
        {
            
            $lineTalkGroup = MUserLineTalkGroup::where('line_id', $voipLineId)
                                                ->where('group_id', $item)
                                                ->first();
            
            if(!empty($lineTalkGroup))
            {
                $lineTalkGroup->delete();
            }
        }
    }

    public function getMainLineTalkGroupForUpdate($voipLineId)
    {
        $voipGroupId = MUserLineTalkGroup::withTrashed()
                                        ->where('line_id', $voipLineId)
                                        ->where('number', 1)
                                        ->select('group_id')
                                        ->first();
                                                                    
        $voipGroupIdMain = $voipGroupId->toArray();
        return $customeVoipGroupId;
    }

    public function getTheLineTalkGroupForUpdate($voipLineId)
    {
        $voipGroupId = MUserLineTalkGroup::withTrashed()
                                        ->where('line_id', $voipLineId)
                                        ->where('number','!=', 1)
                                        ->select('group_id')
                                        ->get();
        
        $voipGroupIdToArray = $voipGroupId->toArray();
        $customeVoipGroupId[] = null;

        foreach($voipGroupIdToArray as $item) {
            foreach($item as $valGroupId)
                array_push($customeVoipGroupId, $valGroupId);
        }

        $customeVoipGroupId = array_values(array_filter(array_unique($customeVoipGroupId)));
        return $customeVoipGroupId;
    }
}
