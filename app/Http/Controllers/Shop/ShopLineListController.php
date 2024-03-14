<?php

namespace App\Http\Controllers\Shop;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MUserTalkGroupRequest;
use App\Models\MUserRequest;
use App\Models\MUser;
use App\Models\MSim;
use App\Models\MModel;
use App\Models\MUserLineTalkGroupRequest;
use App\Models\MUserLineRequest;
use App\Models\MUserLine;
use App\Models\MUserLineTalkGroup;
use App\Models\MUserTalkGroup;
use App\Models\MPlan;
use App\Models\MOptionPlan;
use Auth;
use App\Models\MUserLineTalkGroupAdditionalRequests;
use App\Models\TUserLinePlan;
use App\Models\TUserLinePlanRequest;
use Illuminate\Support\Str;

class ShopLineListController extends Controller
{

    public function list(Request $request)
    {

        $userId = $request->get('user_id');

        $user = MUser::where('id', $userId)->first();

        $shop = Auth::guard('shop')->user();
        $this->checkValidItem($user);

        // $lineTalkGroups = $userRequests->line_talk_groups;

        $query = DB::table('m_user_line_talk_group')
                    ->join('m_user_lines','m_user_line_talk_group.line_id','=','m_user_lines.id')
                    ->join('m_user_talk_groups','m_user_line_talk_group.group_id','=','m_user_talk_groups.id')
                    ->where('m_user_lines.user_id', $userId)
                    ->where('m_user_lines.shop_id', $shop->id);

        $query = $query->where('m_user_line_talk_group.deleted_at', null);

        $lineTalkGroups = $query->select('m_user_line_talk_group.id as ilt', 'm_user_line_talk_group.*', 'm_user_lines.*', 'm_user_talk_groups.*')
                                ->orderBy('m_user_line_talk_group.line_id', 'asc')
                                ->orderBy( 'm_user_line_talk_group.number', 'asc')
                                ->get();

        $lines = MUserLine::where('shop_id', $shop->id)->where('user_id', $userId)->get();

        $talkGroups = MUserTalkGroup::where('user_id', $userId)->where('shop_id', $shop->id)->get();

        return view('shop.line_list.list', compact('lineTalkGroups', 'lines', 'talkGroups', 'user'));

    }

    public function lineTalkGroupDetail(Request $request)
    {
        $idlineTalk = $request->get('id');
        $shop = Auth::guard('shop')->user();

        // $lineTalkGroup = MUserLineTalkGroup::find($idlineTalk);
        $line = MUserLineTalkGroup::where('id', $idlineTalk)->first();

        $lineTalkGroup = DB::table('m_user_line_talk_group')
                        ->join('m_user_lines','m_user_line_talk_group.line_id','=','m_user_lines.id')
                        ->join('m_user_talk_groups','m_user_line_talk_group.group_id','=','m_user_talk_groups.id')
                        ->where('m_user_line_talk_group.line_id', $line->line_id)
                        ->where('m_user_line_talk_group.deleted_at', null)
                        ->orderBy('m_user_line_talk_group.number', 'asc')
                        ->get();

        $user = MUser::where('id', $line->user_id)->first();

        return view('shop.line_list.line_talk_group_detail', compact('lineTalkGroup', 'user'));
    }

    public function existedList()
    {
        if ($request_id = request()->get('request_id')) {
            $userRequest = MUserRequest::find(request()->get('request_id'));
            if (!$userRequest) abort(404);
        }

        $customAddLines =[];
        $addLines = MUserLineRequest::where('request_id', $userRequest->id )
        ->select('voip_line_id')
        ->get();
        foreach ($addLines as $key => $value) {
            $customAddLines[] = $value->voip_line_id;
        }

        $listLines = MUserLine::where('user_id', $userRequest->user_id)
            ->where('shop_id', $userRequest->shop_id)
            ->whereNotNull('voip_line_id')
            ->paginate();


        $user = MUser::where('id', $userRequest->user_id)->first();

        if (!empty($userRequest)) {
            $listLines->appends(['request_id' => $request_id]);
        } else {
            $userRequest = null;
            $listLines = MUserLineRequest::paginate();
        }

        return view('shop.line_list.existed-list', compact('listLines', 'userRequest', 'customAddLines', 'user'));
    }

    public function search(Request $request)
    {
        $currentUser = Auth::guard('shop')->user();
        $keyword = $request->get('s');
        $listLines =MUserLineRequest
        // ->join('m_user_talk_group_requests', 'm_user_line_requests.id', '=', 'm_user_talk_group_requests.request_id')
        ::where('m_user_line_requests.line_num','like','%'.$keyword.'%')
        ->orwhere('m_user_line_requests.sim_id',$keyword)
        ->orderBy('status', 'desc')
        // ->select('m_user_line_requests.*', 'm_user_talk_group_requests.id as talk_group_id')
        ->paginate(15);

        return view('shop.line_list.result_searching', compact('listLines','keyword'));
    }

    public function detail(Request $request)
    {
        $idLine = $request->get('id');
        $line = MUserLine::where('id',$idLine)->first();

        $user = MUser::where('id', $line->user_id)->first();

        return view('shop.line_list.detail',compact('line', 'user'));
    }

    public function input(Request $request)
    {
        $userId = $request->get('user_id');
        $requestId = $request->get('request_id');
        $currentUser = Auth::guard('shop')->user();
        $userRequest = MUserRequest::all();
        $user = MUser::where('id', $userId)->first();
       
        if (!$userId) abort(404);
        // list usage plan
        $usagePlans = MPlan::all();
        // list option plan
        $optionPlans = MOptionPlan::all();

        $talkGroup = MUserTalkGroupRequest::all();
        // $sims = MSim::all();
        // $models = MModel::all();
        return view('shop.line_list.input', compact('currentUser', 'userRequest',
                                                    'talkGroup',
                                                     'userId', 'requestId',
                                                     'usagePlans', 'optionPlans', 'user'));
    }

    public function edit($id)
    {

        $line = MUserLineRequest::find($id);
        
        $userRequest = MUserRequest::where('id', $line->request_id)->first();
        $currentUser = Auth::guard('shop')->user();
        $user = MUser::where('id', $line->user_id)->first();
        $tLinePlan = TUserLinePlanRequest::where('line_id', $id)->first();

        // list usage plan
        $usagePlans = MPlan::all();
        // list option plan
        $optionPlans = MOptionPlan::all();
        return view('shop.line_list.input', compact('line', 'userRequest',
                                                    'user', 'currentUser',
                                                    'usagePlans', 'optionPlans','tLinePlan'
                                                ));
    }

    public function getSimNumber($simNumber) {
        $sim = MSim::where('sim_num', $simNumber)->first();
        return $sim;
    }

    public function store(Request $request)
    {
        
        $validator = validator()->make(request()->all(), MUserLineRequest::columnConstraints());

        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(empty($request->get('id')))
        {
            $lineId = Str::random(10);
            $request->request->add(['lineId' => $lineId]);
        }

        $data = $request->all();
       
        // create line number
        if( empty($request->get('id') or empty($data['lineNum']) )) {
            $contractorId = $this->getContractorId($data);
            $numberOfLine = $request->get('seri_number');
            $lineNum = $this->generateLineNumber($contractorId, $numberOfLine);
            $data['lineNum'] = $lineNum;

            $checkLine = $this->checkLineNumber($data);
            if (!empty($checkLine)) {
                abort(403);
            }
        }

        // Check if the Talk Group exists
        DB::beginTransaction();
        try {
            // add or update in line request
            $line = $this->storeInLineId($data);

            // add or update in table t_user_line_plan_requests
            $this->storeInLinePlan($line, $data);
            DB::commit();

            if(empty($request->get('id'))) {

                return redirect()->route('shop.applicationDetail', ['id' => $line->request_id])->with('success', __('success add new req line list'));
            }

            return redirect()->route('shop.applicationDetail', ['id' => $line->request_id])->with('success', __('success update req line list'));

        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception($e->getMessage());

            return redirect()->back()->with('error',__('error happen'));
        }
    }

    public function checkLineNumberExist($data) {
        $line = MUserLine::where('id', '!=', $data['id'])->where('line_num', $data['lineNum'])->first();
        return $line;
    }
    
    public function checkLineNumber($data) {
        $checkLine = MUserLineRequest::where('id', '!=', $data['userId'])->where('line_num', $data['lineNum'])->first();
        return $checkLine;
    }
    
    public function getContractorId($data){
        $contractorId = MUser::where('id', $data['userId'])->select('contractor_id')->first();
        return $contractorId;
    }

    public function generateLineNumber($contractorId, $numberOfLine) {
        return $contractorId->contractor_id . sprintf("%04d", $numberOfLine);
    }

    public function setStatusByRequestType($requestType) {
        switch ($requestType) {
            case '3':
                return '1';
            case '4':
                return '2';
            case '5':
                return '3';
            default : 
                return null;
        }
    
    }

    public function storeInLineId($data){
        
        if(!empty($data['id'])) {
            $line = MUserLineRequest::where('id', $data['id'])->first();
        }else{
            $line = new MUserLineRequest();
        }
        
        $line->request_id = $data['requestId'];
        $line->shop_id = $data['shopId'];
        $line->user_id = $data['userId'];
        if(!empty($data['rowNum'])) $line->row_num = $data['rowNum'];
        if(!empty($data['seri_number'])) $line->line_num = $data['contractor_id'].sprintf("%04d", $data['seri_number']);
        if(!empty($data['requestType'])) $line->request_type = $data['requestType'];
        $line->voip_id_name = $data['voipIdName'];
        $line->voip_line_password = $data['voipLinePassword'];
        if(!empty($data['callType'])) $line->call_type = $data['callType'];
        if(!empty($data['priority'])) $line->priority = $data['priority'];
        if(!empty($data['individual'])) $line->individual = $data['individual'];
        if(!empty($data['recording'])) $line->recording = $data['recording'];
        if(!empty($data['gps'])) $line->gps = $data['gps'];
        if(!empty($data['commander'])) $line->commander = $data['commander'];
        if(!empty($data['individualPriority'])) $line->individual_priority = $data['individualPriority'];
        if(!empty($data['cueReception'])) $line->cue_reception = $data['cueReception'];
        if(!empty($data['video'])) $line->video = $data['video'];
        $line->status = $this->setStatusByRequestType($data['requestType']);
        $line->save();
        return $line;
    }

    public function storeInLinePlan($line, $data){

            $tLinePlan = TUserLinePlanRequest::where('line_id', $line->id)->first();

            if(empty($tLinePlan)) {
                $tLinePlan = new TUserLinePlanRequest();
            }

            // update the columns data for the t_user_line_plan_requests
            $tLinePlan->request_id = $data['requestId'];
            $tLinePlan->shop_id = $data['shopId'];
            $tLinePlan->user_id = $data['userId'];
            $tLinePlan->line_id = $line->id;
            $tLinePlan->plan_id = $data['usagePlan'];
            $tLinePlan->line_status = null;
            if(!empty($data['startDateOfPlan'])) $tLinePlan->start_date = date('Y-m-d', strtotime($data['startDateOfPlan']));

            if(!empty($data['automaticUpdate'])) {
                $tLinePlan->automatic_update = $data['automaticUpdate'];
            }

            if(!empty( $data['optionPlan'])) {
                if( count($data['optionPlan']) <= 10 ){
                    foreach($data['optionPlan'] as $key => $op)
                    {
                        $key += 1;
                        $keyForOption = 'option_id'.$key;
                        $tLinePlan->$keyForOption = $op;
                    }
                    for( $i = count($data['optionPlan']) + 1 ; $i < 11; $i++){
                        $keyForOption = 'option_id'.$i;

                        $tLinePlan->$keyForOption = null;
                    }
                }
            }else{
                for( $i =  1 ; $i < 11; $i++){
                    $keyForOption = 'option_id'.$i;

                    $tLinePlan->$keyForOption = null;
                }
            }

            $tLinePlan->save();
    }

    public function handleAddExistedLine(Request $request) {

        $idLine = MUserLine::find($request->id);
        $this->checkValidItem($idLine);

        $userRequest = MUserRequest::find($request->request_id);
        $this->checkValidItem($userRequest);

        if ($idLine->user_id != $userRequest->user_id) abort(403);

        $addedLines = MUserLineRequest::where('request_id', $userRequest->id )
        ->select('voip_line_id')->where('voip_line_id', $idLine->voip_line_id)->count();
        if($addedLines > 0) abort(403);

        $linesRequest = new MUserLineRequest();
        $linesRequest->created_at = Carbon::now();
        $linesRequest->request_id = $userRequest->id;
        $linesRequest->user_id = $userRequest->user_id;
        $linesRequest->shop_id = $userRequest->shop_id;
        $linesRequest->request_type =  2;
        $linesRequest->status  = $idLine->getRawValue('status');
        $linesRequest->voip_line_id  = $idLine->voip_line_id;
        $linesRequest->voip_id_name  = $idLine->voip_id_name;
        $linesRequest->models_id   = $idLine->models_id ;
        $linesRequest->sim_num   = $idLine->sim_num ;
        $linesRequest->call_type   = $idLine->getRawValue('call_type') ;
        $linesRequest->individual   = $idLine->getRawValue('individual') ;
        $linesRequest->recording   = $idLine->getRawValue('recording') ;
        $linesRequest->gps   = $idLine->getRawValue('gps') ;
        $linesRequest->commander = $idLine->getRawValue('commander') ;
        $linesRequest->individual_priority = $idLine->getRawValue('individual_priority') ;
        $linesRequest->cue_reception = $idLine->getRawValue('cue_reception') ;
        $linesRequest->video = $idLine->getRawValue('video')  ;
        $linesRequest->start_date = $idLine->start_date ;
        $linesRequest->change_application_date = $idLine->change_application_date ;
        $linesRequest->contract_renewal_date = $idLine->contract_renewal_date ;
        $linesRequest->memo = $idLine->memo ;
        $linesRequest->voip_line_password   = $idLine->voip_line_password ;
        $linesRequest->priority  =$idLine->getRawValue('priority');
        $linesRequest->line_num  = $idLine->line_num;

        if ($linesRequest->save()) {
            if ($idLine->user_line_plan) {
                $linesRequest->refresh();

                $plan = $idLine->user_line_plan->toArray();
                $plan['line_id'] = $linesRequest->id;
                $plan['request_id'] = $linesRequest->request_id;

                $planRequest = new TUserLinePlanRequest();
                $planRequest->smartSave($plan);
            }

            return redirect()->route('shop.applicationDetail', ['id' => $userRequest->id])->with('success', __('Add line successfully'));
        }

        return redirect()->back()->with('error', __('Error! Please try again'));
    }

    public function handleDelete(Request $request)
    {
        $idLine = MUserLineRequest::find($request->id);
        $this->checkValidItem($idLine);

        if ($idLine->delete()) {
            return redirect()->back()->with('success', __('deleted'));

        }

        return redirect()->back()->with('error', __('fail delete'));

    }

    public function handeDeleteLineTalkGroup(Request $request)
    {

        $lineTalkGroup = MUserLineTalkGroupRequest::find($request->id);
        $this->checkValidItem($lineTalkGroup);

        if ($lineTalkGroup->delete()) {
            return redirect()->back()->with('success', __('deleted'));

        }

        return redirect()->back()->with('error', __('fail delete'));

    }

    private function checkValidItem($idLine)
    {
        if (!$idLine) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->id !== $idLine->shop_id) abort(403);
    }

    public function showInformationLineId(Request $request)
	{
		if ($request->ajax()) {
			$line = MUserLineRequest::where('id',$request->line_id)->first();

			return response()->json($line);
		}
	}
}
