<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MUserTalkGroupRequest;
use App\Models\MUser;
use App\Models\MUserLineTalkGroupRequest;
use App\Models\MUserLineTalkGroupAdditionalRequests;
use App\Models\MUserLineRequest;
use Auth;

class ShopLineTalkGroupRequestController extends Controller
{
    
    public function input(Request $request)
    {
        
        $requestId = $request->get('request_id');
        
        $userId = $request->get('user_id');
        $shopId = Auth::guard('shop')->user()->id;
        $currentUser = MUser::where('id', $userId)->first();
        $this->checkValidItem($currentUser);
        
        $lineLists = MUserLineRequest::where('request_id', $requestId)
                                    ->where('shop_id', $shopId)
                                    ->where('user_id', $userId)
                                    ->get();
        
        $listTalkGroups = MUserTalkGroupRequest::where('request_id', $requestId)->get();
        
        if(count($lineLists) == 0)
        {
            return redirect()->back()->with('error', __('Line ID is empty ! Add line ID first, please !'));
        }

        if(count($listTalkGroups) < 1)
        {
            return redirect()->back()->with('error', __('Talk Group is empty ! Add Talk Group first, please !'));
        }
       
        return view('shop.line_talk_group.input', compact('requestId', 'userId', 'shopId', 'currentUser', 'lineLists', 'listTalkGroups'));
    }

    private function getVoipGroupIdOfTalkGroup($talkGroupId)
    {
        $talkGroup = MUserTalkGroupRequest::where('id', $talkGroupId)->first();
       
        return $talkGroup->name;
    }

    public function store(Request $request)
    {
        $validator = validator()->make(request()->all(),  MUserLineTalkGroupRequest::columnConstraints());
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        $data = $request->all();
        
        if(!empty($data['selectGroup'])) {
            $data['selectGroup'] = array_diff($data['selectGroup'], array($request->get('groupMain')));
            $data['selectGroup'] = array_values(array_filter(array_unique($data['selectGroup'])));
        }
        
        DB::beginTransaction();
        try {
           
            $lineTalkGroup = new MUserLineTalkGroupRequest();
           
            // get line request 
            $lineId = MUserLineRequest::where('id', $data['lineId'])->first();
            $lineTalkGroup->request_id = $data['requestId'];
            $lineTalkGroup->shop_id = $data['shopId'];
            $lineTalkGroup->user_id = $data['userId'];
            $lineTalkGroup->row_num = $lineId->row_num;
            $lineTalkGroup->line_num = $data['lineNum'];
            $lineTalkGroup->request_type = $data['requestType'];
            $lineTalkGroup->line_id = $data['lineId'];
            $lineTalkGroup->name = $data['voipIdName'];
            $lineTalkGroup->group_id= $data['groupMain'];
            $lineTalkGroup->group_name = $this->getVoipGroupIdOfTalkGroup($data['groupMain']);
            $lineTalkGroup->save();
            
            if(!empty($data['selectGroup'])) {
                $this->addNewTheLineTalkGroupAddReq($data['selectGroup'], $lineTalkGroup);
            }

            DB::commit();

            if(empty($request->get('id'))) {

                return redirect()->route('shop.applicationDetail', $data['requestId'])->with('success', __('success add new line talk group request'));
            }
    
            return redirect()->route('shop.applicationDetail', $data['requestId'])->with('success', __('success update line talk group request'));

        } catch (Exception $e) {

            DB::rollBack();
            
            throw new Exception($e->getMessage());

            return redirect()->back()->with('error', __('error happen'));
        }

    }

    public function confirmUpdate(Request $request)
    {
       
        $validator = validator()->make(request()->all(), MUserLineTalkGroupRequest::columnConstraints());
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->all();

        if(!empty($data['selectGroup'])) {

            $data['selectGroup'] = array_diff($data['selectGroup'], array($request->get('groupMain')));
            
            $data['selectGroup'] = array_values(array_filter(array_unique($data['selectGroup'])));
        }
        DB::beginTransaction();
        try {

            $lineTalkGroup = MUserLineTalkGroupRequest::where('id', $data['id'])->first();
            // get line request 
            $lineId = MUserLineRequest::where('id', $data['lineId'])->first();
            $lineTalkGroup->request_id = $data['requestId'];
            $lineTalkGroup->shop_id = $data['shopId'];
            $lineTalkGroup->user_id = $data['userId'];
            $lineTalkGroup->line_num = $data['lineNum'];
            $lineTalkGroup->request_type = $data['requestType'];
            $lineTalkGroup->line_id = $data['lineId'];
            $lineTalkGroup->name = $data['voipIdName'];
            $lineTalkGroup->group_id = $data['groupMain'];
            $lineTalkGroup->group_name = $this->getVoipGroupIdOfTalkGroup($data['groupMain']);

            if(!empty($lineId ))
            {
                $lineTalkGroup->row_num = $lineId->row_num;
            }

            $lineTalkGroup->save();

            if(!empty($data['selectGroup'])) {
                // update save in add req
                $lineTalkGroupAddReqForUpdate = $this->getTheLineTalkGroupAddReqForUpdate($lineTalkGroup->id);
                
                $newLineTalkGroupAddReq = array_values(array_diff($data['selectGroup'], $lineTalkGroupAddReqForUpdate));
                $lineTalkGroupAddReqForDelete = array_values(array_diff($lineTalkGroupAddReqForUpdate, $data['selectGroup']));
                $generalLineTalkGroupAddReq = array_values(array_intersect($lineTalkGroupAddReqForUpdate, $data['selectGroup']));
               
                // add new Line Talk Group Add Req into database
                if(!empty($newLineTalkGroupAddReq)) {

                    $this->addNewTheLineTalkGroupAddReq($newLineTalkGroupAddReq, $lineTalkGroup);
                }

                // delete Line Talk Group Add Req into database
                if(!empty( $lineTalkGroupAddReqForDelete))
                {
                    $this->deleteTheLineTalkGroupAddReq($lineTalkGroupAddReqForDelete, $lineTalkGroup->id);
                }

                // update Line Talk Group Add Req exist into database
                if(!empty( $generalLineTalkGroupAddReq))
                {
                    $this->updateTheLineTalkGroupAddReqExist($generalLineTalkGroupAddReq, $lineTalkGroup->id);
                }

            }else{
                $lineTalkGroupAddReqForDelete = $this->getTheLineTalkGroupAddReqForUpdate($lineTalkGroup->id);
                
                // delete Line Talk Group Add Req into database
                if(!empty( $lineTalkGroupAddReqForDelete))
                {
                    $this->deleteTheLineTalkGroupAddReq($lineTalkGroupAddReqForDelete, $lineTalkGroup->id);
                }
            }

            DB::commit();

            if(empty($request->get('id'))) {

                return redirect()->route('shop.applicationDetail', $data['requestId'])->with('success', __('success add new line talk group request'));
            }
    
            return redirect()->route('shop.applicationDetail', $data['requestId'])->with('success', __('success update line talk group request'));

        } catch (Exception $e) {

            DB::rollBack();
            
            throw new Exception($e->getMessage());

            return redirect()->back()->with('error','error happen');
        }

    }

    public function updateTheLineTalkGroupAddReqExist($arrayExistLineTalkGroupAddReqs, $idLineTalkGroup)
    {
        foreach($arrayExistLineTalkGroupAddReqs as $item)
        {
            MUserLineTalkGroupAdditionalRequests::onlyTrashed()
                                                ->where('line_group_req_id',$idLineTalkGroup)
                                                ->where('group_id', $item)
                                                ->restore();
        }
    }

    public function deleteTheLineTalkGroupAddReq($arrayLineTalkGroupAddReqsForDelete, $idLineTalkGroup)
    {  
        foreach($arrayLineTalkGroupAddReqsForDelete as $item)
        {
            $lineTalkGroupAddReq = MUserLineTalkGroupAdditionalRequests::where('line_group_req_id', $idLineTalkGroup)
                                                                        ->where('group_id', $item)
                                                                        ->first();
            
            if(!empty($lineTalkGroupAddReq))
            {
                $lineTalkGroupAddReq->delete();
            }
        }
    }

    public function addNewTheLineTalkGroupAddReq($arrayNewLineTalkGroupAddReqs, $lineTalkGroup)
    {
        
        foreach($arrayNewLineTalkGroupAddReqs as $item)
        {
            $lineTalkGroupAddReq = new MUserLineTalkGroupAdditionalRequests();
            $lineTalkGroupAddReq->line_group_req_id = $lineTalkGroup->id;
            $lineTalkGroupAddReq->shop_id = $lineTalkGroup->shop_id;
            $lineTalkGroupAddReq->user_id = $lineTalkGroup->user_id;
            $lineTalkGroupAddReq->group_id = $item;
            $lineTalkGroupAddReq->group_name = $this->getVoipGroupIdOfTalkGroup($item);
            $lineTalkGroupAddReq->save();
        }
    }

    public function getTheLineTalkGroupAddReqForUpdate($idLineTalkGroup)
    {
        $lineTalkGroupAddReqs = MUserLineTalkGroupAdditionalRequests::withTrashed()
                                                                    ->where('line_group_req_id', $idLineTalkGroup)
                                                                    ->select('group_id')
                                                                    ->get();
                                                                    
        $lineTalkGroupAddReqsToArray = $lineTalkGroupAddReqs->toArray();
        $customeAddReqArray[] = null;

        foreach($lineTalkGroupAddReqsToArray as $item) {
            foreach($item as $valGroupName)
                array_push($customeAddReqArray, $valGroupName);
        }

        $customeAddReqArray = array_values(array_filter(array_unique($customeAddReqArray)));
        
        return $customeAddReqArray;
    }

    public function update($id)
    {

        $lineTalkGroup = MUserLineTalkGroupRequest::where('id', $id)->first();
        $currentUser = MUser::where('id', $lineTalkGroup->user_id)->first();
        $lineLists = MUserLineRequest::where('request_id', $lineTalkGroup->request_id)
                                    ->where('shop_id', $currentUser->shop_id)
                                    ->where('user_id', $currentUser->id)
                                    ->get();
        
        $lineTalkGroupAddReq = MUserLineTalkGroupAdditionalRequests::where('line_group_req_id', $lineTalkGroup->id)->get();
        
        // dd($lineLists);
        $listTalkGroups = MUserTalkGroupRequest::where('request_id', $lineTalkGroup->request_id)->get();
        $getOwnerOfTalkGroup = null;

        if (!empty($lineTalkGroup->main_voip_group_name)){
            $getOwnerOfTalkGroup = MUserTalkGroupRequest::where('name', $lineTalkGroup->main_voip_group_name)->first();
        }
        
        return view('shop.line_talk_group.input', compact(  'lineTalkGroup', 
                                                            'currentUser', 
                                                            'listTalkGroups', 
                                                            'lineLists', 
                                                            'lineTalkGroupAddReq',
                                                            'getOwnerOfTalkGroup'));
    }

    private function checkValidItem($idLine)
    {
        if (!$idLine) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->id !== $idLine->shop_id) abort(403);
    }
}