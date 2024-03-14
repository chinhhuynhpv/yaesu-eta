<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\MUserLine;
use App\Models\MUserLineTalkGroup;
use App\Models\MUserTalkGroup;
use App\Models\MSim;
use App\Models\MModel;
use App\Models\TUserLinePlan;
use App\Models\MPlan;
use App\Models\MShop;
use App\Models\MUser;
use App\Models\MOptionPlan;
use App\Models\TUserLinePlanRequest;
use DB;

class OperatorLineListController extends Controller
{
    public function list(Request $request)
    {
        
        $userId = $request->get('id');
        $operator = Auth::guard('admin')->user();
        
        if(empty($operator))
        {
            abort(403);
        }
       
        // get line talk group
        $query = DB::table('m_user_line_talk_group')
                    ->join('m_shops', 'm_user_line_talk_group.shop_id', 'm_shops.id')
                    ->join('m_users', 'm_user_line_talk_group.user_id', 'm_users.id')
                    ->join('m_user_lines', 'm_user_line_talk_group.line_id', 'm_user_lines.id')
                    ->join('m_user_talk_groups', 'm_user_line_talk_group.group_id', 'm_user_talk_groups.id')
                    ->where('m_user_line_talk_group.deleted_at', null);

        if(!empty($userId)) {
            $query = $query->where('m_user_lines.user_id', $userId);
        }

        $lineTalkGroups = $query->orderBy('m_user_line_talk_group.line_id', 'asc')
                                ->orderBy( 'm_user_line_talk_group.number', 'asc')
                                ->select('m_user_line_talk_group.id as ilt', 'm_user_line_talk_group.*', 'm_shops.name as shop_name', 'm_users.contract_name', 'm_user_lines.*', 'm_user_talk_groups.*')
                                ->get();
       
        // create line talk group join with line talk group to 
        $user = null;
        if(!empty($userId)) {
        
            $lines = MUserLine::where('m_user_lines.user_id', $userId)
                        ->join('m_shops', 'm_user_lines.shop_id', 'm_shops.id')
                        ->join('m_users', 'm_user_lines.user_id', 'm_users.id')
                        ->select('m_user_lines.*', 'm_shops.name as shop_name', 'm_users.contract_name')
                        ->get();

            $talkGroups = MUserTalkGroup::where('m_user_talk_groups.user_id', $userId)
                        ->join('m_shops', 'm_user_talk_groups.shop_id', 'm_shops.id')
                        ->join('m_users', 'm_user_talk_groups.user_id', 'm_users.id')
                        ->select('m_user_talk_groups.*', 'm_shops.name as shop_name', 'm_users.contract_name')
                        ->get();

            $user = MUser::where('id', $userId)->first();

        }else{
           
            $lines = MUserLine::select('m_user_lines.*', 'm_shops.name as shop_name', 'm_users.contract_name')
                        ->join('m_shops', 'm_user_lines.shop_id', 'm_shops.id')
                        ->join('m_users', 'm_user_lines.user_id', 'm_users.id')
                        ->get();

            $talkGroups = MUserTalkGroup::select('m_user_talk_groups.*', 'm_shops.name as shop_name', 'm_users.contract_name')
                        ->join('m_shops', 'm_user_talk_groups.shop_id', 'm_shops.id')
                        ->join('m_users', 'm_user_talk_groups.user_id', 'm_users.id')
                        ->get();
            
        }


        $shops = MShop::orderBy('id', 'desc')->get();
        
        return view('operator.line_list.list', compact('lineTalkGroups', 'lines', 'talkGroups', 'userId', 'user', 'shops'));
    }

    public function edit(Request $request)
    {
        
        $idLine = $request->get('id');
        $userId = null;
        $userId = $request->get('user_id');
        $line = MUserLine::find($idLine);
        
        $models = MModel::all();
       
        $tLinePlan = TUserLinePlan::where('line_id', $idLine)->first();
        // list usage plan
        $usagePlans = MPlan::all();
        // list option plan
        $optionPlans = MOptionPlan::all();

        $user = MUser::where('id', $userId)->first();

        return view('operator.line_list.input', compact('line', 'models', 'tLinePlan', 'usagePlans', 'optionPlans', 'userId', 'user'));
    }

    public function store(Request $request)
    {
        
        $validator = validator()->make(request()->all(), MUserLine::columnConstraints());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // check sim exist
        $data = $request->all();
        if(!empty($request->get('simNum'))) {
            $sim = $this->getSimIdBySimNum($request->get('simNum'));
           
            if(empty($sim)) {
                return redirect()->back()->with('error', __('Sim Id is invalid. Please check again!'))->withInput();
            }
            $checkSimIdUsed = $this->checkSimSetInLineId($sim->sim_num, $request->get('id'));
            
            if(!empty($checkSimIdUsed)) {
                return redirect()->back()->with('error', __('Sim Id has been used. Please use another sim!'))->withInput();
            }
            $data['simNum'] = $sim->sim_num;
        }

        // check voip line id exist 
        $checkVoipLineId = $this->checkVoipLineIdExist($data);
        if(!empty($checkVoipLineId)) {
            return redirect()->back()->with('error', __('Line Id has been used. Please use another voip line id!'))->withInput();
        }

        DB::beginTransaction();

        try {
            // add or update in line request
            $line = $this->storeInLineId($data);
           
            // add or update in table t_user_line_plan_requests
            $this->storeInLinePlan($line, $data);
            // update line status in m_user_line_plan_request
            $this->updateLineStatusInLinePlanRequest($line, $data);
            DB::commit();

            if(!empty($request->get('currentUserId'))) {
                $currentUserId = $request->get('currentUserId');
                return redirect()->route('admin.lineList',['id'=>$currentUserId])->with('success', __('success update line list'));
            }
            return redirect()->route('admin.lineList')->with('success', __('success update line list'));

        } catch (Exception $e) {

            DB::rollBack();
            
            throw new Exception($e->getMessage());

            return redirect()->back()->with('error', __('error happen'));
        }
    }

    public function checkSimSetInLineId( $simNum, $lineId) {
        
        $line = MUserLine::where('sim_num', $simNum)->where('id', '!=', $lineId)->first();
      
        return $line;
    }

    public function getSimIdBySimNum($simNum) {
        $sim = MSim::where('sim_num', $simNum )->first();
        return $sim;
    }

    public function checkVoipLineIdExist($data) {
        $line = MUserLine::where('id', '!=', $data['id'])->where('voip_line_id', $data['voipLineId'])->first();
        return $line;
    }

    public function storeInLineId($data){
        $line = MUserLine::where('id', $data['id'])->first();
        $line->shop_id = $data['shopId'];
        $line->user_id = $data['userId'];
        $line->voip_line_id = $data['voipLineId'];
        $line->line_num = $data['lineNum'];
        $line->voip_id_name = $data['voipIdName'];
        $line->models_id = $data['modelId'];
        $line->sim_num = $data['simNum'];
        $line->voip_line_password = $data['voipLinePassword'];
        if(!empty($data['priority'])) {
            $line->priority = $data['priority'];
        }

        if(!empty($data['callType'])) {
            $line->call_type = $data['callType'];
        }

        if(!empty($data['individual'])) {
            $line->individual = $data['individual'];
        }

        if(!empty($data['recording'])) {
            $line->recording = $data['recording'];
        }
        
        if(!empty($data['gps'])) {
            $line->gps = $data['gps'];
        }

        if(!empty($data['commander'])) {
             $line->commander = $data['commander'];
        }

        if(!empty($data['individualPriority'])) {
            $line->individual_priority = $data['individualPriority'];
        }

        if(!empty($data['cueReception'])) {
            $line->cue_reception = $data['cueReception'];
        }

        if(!empty($data['video'])) {
            $line->video = $data['video'];
        }

        if(!empty($data['startDate'])) {
            $line->start_date = date('Y-m-d', strtotime($data['startDate']));
        }
        else {
            $line->start_date = null;
        }

        if(!empty($data['status'])) {
            $line->status = $data['status'];
        }else {
            $line->status = null;
        }

        if(!empty($data['changeApplicationDate'])){

            $line->change_application_date = date('Y-m-d', strtotime($data['changeApplicationDate']));
        }
        else {
            $line->change_application_date = null;
        }

        if(!empty($data['contractRenewalDate'])) {
            $line->contract_renewal_date = date('Y-m-d', strtotime($data['contractRenewalDate']));
        }
        else{
            $line->contract_renewal_date = null;
        }

        if(!empty($data['memo'])) {
            $line->memo = $data['memo'];
        }else {
            $line->memo = null;
        }
        
        $line->save();

        return $line;
    }

    public function updateLineStatusInLinePlanRequest($line, $data) {
        $linePlanRequest = TUserLinePlanRequest::where('line_id', $line->request_line_id)->first();

        if(!empty($linePlanRequest) && !empty($data['status'])) {
            $linePlanRequest->line_status = $data['status'];
        }else{
            $linePlanRequest->line_status = null;
        }
        $linePlanRequest->save();
    }

    public function storeInLinePlan($line, $data){
       
        $tLinePlan = TUserLinePlan::where('line_id', $line->id)->first();
        
        // update the columns data for the t_user_line_plan_requests
        if(empty($tLinePlan))
        {
            $tLinePlan = new TUserLinePlan();
        }

        $tLinePlan->line_id = $line->id;
        $tLinePlan->shop_id = $line->shop_id;
        $tLinePlan->user_id = $line->user_id;

        if(!empty($data['status'])) $tLinePlan->line_status = $data['status'];

        $tLinePlan->plan_id = $data['usagePlan'];
        // if(!empty($data['startDateOfPlan'])){
        //     $tLinePlan->start_date = date('Y-m-d', strtotime($data['startDateOfPlan']));
        // } 
        if(!empty($data['startDate'])) {
            $tLinePlan->start_date = date('Y-m-d', strtotime($data['startDate']));
        }else{
            $tLinePlan->start_date = null;
        } 

        if(!empty($data['startDate'])) {
            $tLinePlan->plan_set_start_date = date('Y-m-d', strtotime($data['startDate']));
        }else{
            $tLinePlan->plan_set_start_date = null;
        }
            
        if(!empty($data['automaticUpdate'])) {
            $tLinePlan->automatic_update = $data['automaticUpdate'];
        }
       
        if(!empty( $data['optionPlan'])) {

            foreach($data['optionPlan'] as $key => $op)
            {
                $key += 1;
                $keyForOption = 'option_id'.$key;
                $tLinePlan->$keyForOption = $op;
            }
        }
        
        if(!empty($data['optionPlan']) && count($data['optionPlan']) <= 10)
        {
           
            for( $i = count($data['optionPlan']) + 1 ; $i < 11; $i++){

                $keyForOption = 'option_id'.$i;
                
                $tLinePlan->$keyForOption = null;
            }
        }
        else {
            for( $i =  1 ; $i < 11; $i++){
                $keyForOption = 'option_id'.$i;
                
                $tLinePlan->$keyForOption = null;
            }
        }
        
        $tLinePlan->save();
    }

    public function showInformationLineId(Request $request)
	{
		if ($request->ajax()) {

			$line = MUserLine::where('id',$request->line_id)->first();

			return response()->json($line);
		}
	}

}
