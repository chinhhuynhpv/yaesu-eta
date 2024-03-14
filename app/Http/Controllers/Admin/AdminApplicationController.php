<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MCommission;
use App\Models\MSalesPromotionPlan;
use App\Models\MShop;
use App\Models\MUserLine;
use App\Models\MUserLineTalkGroup;
use App\Models\MUserRequest;
use App\Models\MUserTalkGroup;
use App\Models\TLinePauses;
use App\Models\TShopSalesPromotionPlan;
use App\Models\TUserLinePlan;
use App\Models\TUserCommission;
use Arr;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AdminApplicationController  extends Controller
{
    /**
     * Get list of applications that are filtered and it's status = 2
     * @param Request $req
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $req)
    {
        $query = MUserRequest::join('m_users', 'user_id', 'm_users.id')
            ->join('m_shops', 'm_user_requests.shop_id', 'm_shops.id');

        $appendParams = [];

        if (($request_number = $req->query('request_number')) !== null) {
            $query = $query->where('request_number', 'LIKE', "%$request_number%");
            $appendParams['request_number'] = $request_number;
        }

        if ($shop_id =$req->query('shop_id')) {
            $query = $query->where('m_users.shop_id', $shop_id);
            $appendParams['shop_id'] = $shop_id;
        }

        if (($contract_name = $req->query('contract_name')) !== null) {
            $query = $query->where('m_users.contract_name', 'LIKE', "%" . trim($contract_name) . "%");
            $appendParams['contract_name'] = $contract_name;
        }

        $query = $query->where('m_user_requests.status', '=', '2');

        $userRequests = $query->orderBy('status')
            ->orderBy('request_number', 'desc')
            ->orderBy('id', 'desc')
            ->select('m_user_requests.*', 'm_shops.name as shop_name', 'm_users.contract_name as contract_name')
            ->paginate();

        $userRequests->appends($appendParams);
        $shops = MShop::orderBy('id', 'desc')->get();

        return view('operator/application/list', compact('userRequests', 'shops'));
    }

    /**
     * Get detail of the application
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detail($id) {
        $userRequest = MUserRequest::find($id);

        if (!$userRequest || $userRequest->getRawValue('status') != '2') abort(404);

        $userRequest->mergeInput(request()->old());

        return view('operator/application/detail', compact('userRequest'));
    }

    /**
     * Handle processing settings of the application
     * @param Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleEdit(Request $req) {
        $userRequest = MUserRequest::with('talk_group_requests')->where('id',$req->id)->first();

        if (!$userRequest || $userRequest->getRawValue('status') != '2') abort(404);

        $userRequest->remark = $req->remark;
        $userRequest->precautionary_statement = $req->precautionary_statement;

        if (!empty($req->submit['reflect'])) {
            if($this->reflectSettings($userRequest)) {
                $message = __('Reflect settings successfully');
            }
        }
        elseif (!empty($req->submit['complete'])) {
            $currentDate = Carbon::now()->toDateString('Y-m-d');
            $mCommission = MCommission::whereDate('effective_date', '<=', $currentDate)
                ->whereDate('expire_date', '>=', $currentDate)
                ->orderBy('id', 'DESC')
                ->first();

            if ($mCommission) {
                if($this->completeSettings($mCommission, $userRequest, $currentDate)) {
                    $message = __('Complete settings successfully');
                }
            }
            else {
                return redirect()->back()->with('error', __('No plan is availabe now'));
            }
        }
        else if (!empty($req->submit['dec'])) {
            $userRequest->status = '3';

            if ($userRequest->save()) {
                $message = __('Decline successfully');
            }
        }

        if (!empty($message)) return redirect()->route('admin.applicationList')->with('success', __($message));

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    /**
     * Handle processing of reflecting settings
     * @param $userRequest
     * @return bool
     */
    private function reflectSettings($userRequest) {
        DB::beginTransaction();

        try {
            $talkGroups = $userRequest->talk_group_requests;

            if ($talkGroups->count()) {
                $insertedTalkGroups = []; //contains rows inserted to talk group master

                foreach ($talkGroups as $talkGroup) {
                    $row = Arr::except($talkGroup->toArray(), ['id', 'created_at', 'updated_at', 'request_type', 'row_num']);

                    if ($talkGroup->void_group_id) {
                        //Update changes of talk group
                        MUserTalkGroup::where('void_group_id', $row['void_group_id'])->update($row);
                    }
                    else {
                        $row['request_group_id'] = $talkGroup->id;
                        $insertedTalkGroups[] = $row;
                    }
                }

                //Insert new talk group
                MUserTalkGroup::upsert($insertedTalkGroups, ['shop_id', 'user_id', 'request_id', 'request_group_id']);

            }

            $lineIds = $userRequest->line_requests;

            if ($lineIds->count()) {
                $insertedLines = []; //contains rows inserted to line master

                foreach ($lineIds as $line) {
                    $row = Arr::except($line->toArray(), ['id', 'created_at', 'updated_at', 'request_type', 'row_num']);

                    if (MUserLine::where('voip_line_id', $line->voip_line_id)->count()) {
                        //Update changes of line
                        MUserLine::where('voip_line_id', $line->voip_line_id)->update($row);
                    }
                    else {
                        $row['request_line_id'] = $line->id;
                        $insertedLines[] = $row;
                    }
                }

                //Insert new lines
                MUserLine::upsert($insertedLines, ['shop_id', 'user_id', 'request_id','request_line_id']);
            }

            $linePlansRequests = $userRequest->line_plans_requests;

            if ($linePlansRequests->count()) {
                $insertedLinePlans = []; //contains rows inserted

                foreach ($linePlansRequests as $linePlansRequest) {
                    $row = array_merge(
                        Arr::except($linePlansRequest->toArray(), ['created_at', 'updated_at', 'request_id', 'line_id']),
                        ['plan_set_start_date' => $linePlansRequest->start_date]
                    );

                    if ($line = MUserLine::where('voip_line_id', $linePlansRequest->line_request->voip_line_id)
                        ->orWhere('request_line_id', $linePlansRequest->line_request->id)
                        ->first()) {

                        //Update changes of line
                        $userLinePlan = TUserLinePlan::where('line_id', $line->id)->first();

                        if (!$userLinePlan) {
                            $userLinePlan = new TUserLinePlan();
                            $userLinePlan->line_id = $line->id;
                        }

                        $userLinePlan->smartSave($row);

                    }
                    else {
                        $insertedLinePlans[] = $row;
                    }
                }

                TUserLinePlan::upsert($insertedLinePlans, ['id']);
            }

            $lineTalkGroups = $userRequest->line_talk_group_requests;

            if ($lineTalkGroups->count()) {
                $lineTalkGroupArr = [];

                foreach ($lineTalkGroups as $lineTalkGroup) {
                    //get the respective talk group of the talk group request
                    $mUserTalkGroup = MUserTalkGroup::where('request_group_id', $lineTalkGroup->group_id)
                        ->where('shop_id', $lineTalkGroup->shop_id)
                        ->where('user_id', $lineTalkGroup->user_id)
                        ->where('request_id', $lineTalkGroup->request_id)
                        ->first();

                    //get the respective line of the line request
                    $mUserLine = MUserLine::where('request_line_id', $lineTalkGroup->line_id)
                        ->where('shop_id', $lineTalkGroup->shop_id)
                        ->where('user_id', $lineTalkGroup->user_id)
                        ->where('request_id', $lineTalkGroup->request_id)
                        ->first();

                    //check if both the respective talk group and the respective line exists, add to the array of upserted line talk group
                    if ($mUserTalkGroup && $mUserLine) {
                        $number = 1;

                        $lineTalkGroupArr[] = [
                            'shop_id' => $lineTalkGroup->shop_id,
                            'user_id' => $lineTalkGroup->user_id,
                            'request_id' => $lineTalkGroup->request_id,
                            'line_id' => $mUserLine->id,
                            'group_id' => $mUserTalkGroup->id,
                            'number' => $number
                        ];

                        if ($lineTalkGroup->additional_groups->count()) {
                            foreach ($lineTalkGroup->additional_groups as $additionalGroup) {
                                $number ++;

                                $additionalMUserTalkGroup = MUserTalkGroup::where('request_group_id', $additionalGroup->group_id)
                                    ->where('shop_id', $lineTalkGroup->shop_id)
                                    ->where('user_id', $lineTalkGroup->user_id)
                                    ->where('request_id', $lineTalkGroup->request_id)
                                    ->first();

                                if ($additionalMUserTalkGroup) {
                                    $number ++;
                                    $lineTalkGroupArr[] = [
                                        'shop_id' => $lineTalkGroup->shop_id,
                                        'user_id' => $lineTalkGroup->user_id,
                                        'request_id' => $lineTalkGroup->request_id,
                                        'line_id' => $mUserLine->id,
                                        'group_id' => $additionalMUserTalkGroup->id,
                                        'number' => $number
                                    ];
                                }
                            }
                        }
                    }
                }

                MUserLineTalkGroup::upsert($lineTalkGroupArr, ['shop_id', 'user_id', 'request_id', 'group_id', 'line_id']);
            }

            if (!$userRequest->save()) {
                DB::rollBack();
            } else {
                DB::commit();
                return true;
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return false;
    }

    /**
     * Handle processing of completing settings
     * @param $mCommission
     * @param $userRequest
     * @return bool
     */
    private function completeSettings(MCommission $mCommission, MUserRequest $userRequest, $currentDate) {
        DB::beginTransaction();

        try {
            //Array contains values will be inserted to t_user_commissions table
            $tUserCommissionArr = [];
            //Array contains values will be inserted to t_shop_sales_promotion_plans table
            $tShopSalesPromotionPlanArr = [];
            //Array contains values will be inserted to t_line_pauses table
            $tLinePauseArr = [];

            //Add to $tUserCommissionArr if the application has talk group requests
            foreach ($userRequest->talk_group_requests as $talkGroup) {
                if ($talkGroup->getRawValue('request_type') != '2') {
                    $tUserCommissionArr[] = $this->createRowOfTUserCommission($mCommission, $userRequest, $currentDate);
                }
            }

            //Add to $tUserCommissionArr if the application has line requests
            if ($userRequest->line_requests->count()) {
                $mSalesPromotionPlan = MSalesPromotionPlan::whereDate('effective_date', '<=', $currentDate)
                    ->whereDate('expire_date', '>=', $currentDate)
                    ->orderBy('id', 'DESC')
                    ->first();

                foreach ($userRequest->line_requests as $lineRequest) {
                    $requestType = $lineRequest->getRawValue('request_type');
                    $line = MUserLine::where('request_line_id', $lineRequest->id)
                        ->orWhere('voip_line_id', $lineRequest->voip_line_id)
                        ->first();

                    if ($line) {
                        switch ($requestType) {
                            case '1':
                                if ($mSalesPromotionPlan) {
                                    //Add to $tShopSalesPromotionPlanArr
                                    $tShopSalesPromotionPlanArr[] = [
                                        'shop_id' => $userRequest->shop_id,
                                        'user_id' => $userRequest->user_id,
                                        'incentive_date' => $currentDate,
                                        'sales_promotion_id' => $mSalesPromotionPlan->id,
                                        'user_request_id' => $userRequest->id,
                                        'line_id' => $line->id
                                    ];
                                }
                            case "3":
                                if ($tUserLinePlan = TUserLinePlan::where('line_id', $line->id)->first()) {
                                    //Add to $tLinePauseArr
                                    $tLinePauseArr[] = [
                                        'line_id' => $line->id,
                                        'shop_id' => $line->shop_id,
                                        'user_id' => $line->user_id,
                                        'line_plans_id' => $tUserLinePlan->id,
                                        'pause_start_date' => $tUserLinePlan->start_date,
                                        'pause_end_date' => $tUserLinePlan->end_date,
                                    ];
                                }
                            default:
                        }

                        if ($requestType != "2" && $requestType != "5") {
                            //Add to $tUserCommissionArr
                            $tUserCommissionArr[] = $this->createRowOfTUserCommission($mCommission, $userRequest, $currentDate, $line->id);
                        }
                    }
                }
            }

            //Add to $tUserCommissionArr if the application has modify_flg = true and line talk groups
            foreach ($userRequest->line_talk_group_requests as $lineTalkGroup) {
                $tUserCommissionArr[] = $this->createRowOfTUserCommission($mCommission, $userRequest, $currentDate);
            }

            //insert elements of $tUserCommissionArr
            if (count($tUserCommissionArr)) {
                TUserCommission::upsert($tUserCommissionArr, ['id']);
            }

            //insert elements of $tShopSalesPromotionPlanArr
            if (count($tShopSalesPromotionPlanArr)) {
                TShopSalesPromotionPlan::upsert($tShopSalesPromotionPlanArr, ['id']);
            }

            //insert elements of $tLinePauseArr
            if (count($tLinePauseArr)) {
                TLinePauses::upsert($tLinePauseArr, ['id']);
            }

            $userRequest->status = '4';

            if (!$userRequest->save()) {
                DB::rollBack();
            } else {
                DB::commit();
                return true;
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return false;
    }

    /**
     * Generate a row of values inserted to t_user_commission table
     * @param MSalesPromotionPlan $mCommission
     * @param MUserRequest $userRequest
     * @param datestring $comissionDate
     * @param null|int $lineId
     * @return array
     */
    private function createRowOfTUserCommission(MCommission $mCommission, MUserRequest $userRequest, $comissionDate, $lineId = null) {
        $incentiveUnitPrice = 0;

        if (!$userRequest->shop->incentive_flg) {
            switch ($userRequest->shop->incentive_type) {
                case "1":
                    $incentiveUnitPrice = $mCommission->incentive_unit_price;
                    break;
                case "2":
                    $incentiveUnitPrice = $mCommission->incentive_unit_price2;
                    break;
                case "3":
                    $incentiveUnitPrice = $mCommission->incentive_unit_price3;
                    break;
                default:
                    break;
            }
        }

        return [
            'commission_date' => $comissionDate,
            'commission_id' => $mCommission->id,
            'commission_unit_price' => $mCommission->usage_unit_price,
            'incentive_unit_price' => $incentiveUnitPrice,
            'shop_id' => $userRequest->shop_id,
            'user_id' => $userRequest->user_id,
            'line_id' => $lineId,
            'user_request_id' => $userRequest->id
        ];
    }
}
