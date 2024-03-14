<?php

namespace App\Models;

use App\Helper\LineFeeCalculation;
use App\Traits\HandleDate;
use App\Traits\HandleInput;
use App\Traits\HandleNumberingRule;
use App\Traits\HandleSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MUserRequest extends Model
{
    use HandleDate;
    use HandleInput;
    use HandleNumberingRule;
    use HandleSearch;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_user_requests';
    /**
     * The relations to eager search.
     *
     * @var array
     */
    protected $search = ['request_number'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ["user"];

    /**
     * Get the user associated with the request.
     */
    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }

    /**
     * Get the user associated with the request.
     */
    public function shop()
    {
        return $this->belongsTo(MShop::class, 'shop_id');
    }

    /**
     * Get talk group requests associated with the request.
     */
    public function talk_group_requests()
    {
        return $this->hasMany(MUserTalkGroupRequest::class, 'request_id')->orderBy('row_num','asc');
    }

    /**
     * Get talk groups associated with the request.
     */
    public function m_talk_groups()
    {
        return $this->hasMany(MUserTalkGroup::class, 'request_id');
    }

    /**
     * Get line requests associated with the request.
     */
    public function line_requests()
    {
        return $this->hasMany(MUserLineRequest::class, 'request_id')->orderBy('row_num','asc');
    }

    /**
     * Get lines associated with the request.
     */
    public function m_lines()
    {
        return $this->hasMany(MUserLine::class, 'request_id');
    }

    /**
     * Get line talk group requests associated with the request.
     */
    public function line_talk_group_requests()
    {
        return $this->hasMany(MUserLineTalkGroupRequest::class, 'request_id')->orderBy('row_num','asc');
    }

    /**
     * Get line talk groups associated with the request.
     */
    public function m_line_talk_groups()
    {
        return $this->hasMany(MUserLineTalkGroup::class, 'request_id');
    }

    /**
     * Get line_plans_requests associated with the request.
     */
    public function line_plans_requests()
    {
        return $this->hasMany(TUserLinePlanRequest::class, 'request_id');
    }

    /**
     * Get line_plans_requests associated with the request.
     */
    public function t_line_plans()
    {
        return $this->hasMany(TUserLinePlan::class, 'request_id');
    }

    /**
     * Get t_user_commissions associated with the request.
     */
    public function t_user_commissions()
    {
        return $this->hasMany(TUserCommission::class, 'user_request_id');
    }

    /**
     * Get the request's status.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('temporary');
            case '2':
                return __('application');
            case '3':
                return __('decline');
            case '4':
                return __('configured');
            default:
                return null;
        }
    }

    static public function columnConstraints()
    {
        return [
            'status' => 'string|in:1,2,3,4',
            'add_flg' => 'boolean',
            'modify_flg' => 'boolean',
            'pause_restart_flg' => 'boolean',
            'discontinued_flg' => 'boolean',
            'request_date' => 'date|nullable|regex:/^\d{4,4}-\d{2,2}-\d{2,2}$/',
        ];
    }

    public function getRequestDateAttribute($value)
    {
        if ($value) {
            return $this->parseDate($value);
        }

        return $value;
    }

    static public function generateRequestNumber()
    {
        $yearRule = self::yearRule();
        $lastRquest = self::where('request_number', 'LIKE', $yearRule . "%")->orderBy('id', 'DESC')->first();

        if ($lastRquest && $lastRquest->request_number) {
            $number = (int) str_replace($yearRule, '', $lastRquest->request_number) + 1;

            return $yearRule . sprintf("%06d", $number);
        }

        return "{$yearRule}000001";
    }

    public function getBooleanColumn($value)
    {
        return $this->$value ? __('O') : __('-');
    }

    public function getGroupIdsAddedToLine() {
        $addedGroups = array_merge($this->line_talk_group_requests->pluck('group_id')->toArray());

        foreach ($this->line_talk_group_requests as $lineGroup) {
            $addedGroups = array_merge($addedGroups, $lineGroup->additional_groups->pluck('group_id')->toArray());
        }

        return $addedGroups;
    }

    /**
     * Calculate fees of this current month and next month
     * @return array
     */
    public function calculateFee() {
        $currentMonthFees = $this->initFee();
        $nextMonthFees = $this->initFee();
        $now = Carbon::now();
        $effectiveCommissionPlan = MCommission::getEffectivePlan($now->format('Y-m-d'));
        $feeCalculation = new LineFeeCalculation();
        $existedVoipLineIds = [];
        
        //calculate requests
        foreach ($this->geLineRequests() as $lineRequest) {
            $currentMonthFees['change_line_number'];
            $requestType = $lineRequest->getRawValue('request_type');

            if ($effectiveCommissionPlan) {
                $currentMonthFees['change_fee'] += $effectiveCommissionPlan->usage_unit_price;
            }

            if(in_array($requestType, ['1', '2', '4'])) {
                $lineStartDate = Carbon::createFromDate($lineRequest->getRawValue('start_date'));


                $feeCalculation->calculateFee($currentMonthFees, $nextMonthFees, $lineRequest->line_usage_fee, $lineStartDate);

                $campaignDiscount = $lineRequest->campaign_discount;

                $currentMonthFees['campaign_discount'] = $campaignDiscount;
                $nextMonthFees['campaign_discount'] = $campaignDiscount;
                $currentMonthFees['line_campaign_number'] = $this->geLineRequests()->count();
                $nextMonthFees['line_campaign_number'] = $this->geLineRequests()->count();

                $existedVoipLineIds[] = $lineRequest->voip_line_id;
            }

            if ($requestType == '5') {
                if ($lineRequest->getRawValue('change_application_date')) {
                    $lineEndDate = Carbon::createFromDate($lineRequest->getRawValue('change_application_date'));
                    $remainMonth = $lineEndDate->month - $now->month;

                    $currentMonthFees['change_fee'] += round($remainMonth * $lineRequest->midway_cancell_unit_price, 2);
                }
            }
        }

        //calculate existed lines
        $existedLines = $this->getExistedLines($existedVoipLineIds);
       
        if ($existedLines->count()) {
            foreach ($existedLines as $line) {
                $lineStartDate = Carbon::createFromDate($line->start_date);
                $feeCalculation->calculateFee($currentMonthFees, $nextMonthFees, $line->line_usage_fee, $lineStartDate);

                $campaignDiscount = $line->campaign_discount;

                $currentMonthFees['campaign_discount'] = $campaignDiscount;
                $nextMonthFees['campaign_discount'] = $campaignDiscount;
            }
        }

        //get effective tax
        $effectiveTax = MTax::getEffectiveTax($now->format('Y-m-d'));

        if ($effectiveTax) {
            $tax = $effectiveTax->tax * 0.01;
        } else {
            $tax =  0;
        }

        if ($this->user->getRawValue('billing_shipping') && $effectiveCommissionPlan) {
            $currentMonthFees['invoice_mailing_service_fee'] = round($tax * $effectiveCommissionPlan->usage_unit_price);
        }

        $currentMonthFees = $this->calculateRemainingFee($currentMonthFees, $tax);

        $nextMonthFees['invoice_mailing_service_fee'] = $currentMonthFees['invoice_mailing_service_fee'];
        $nextMonthFees = $this->calculateRemainingFee($nextMonthFees, $tax);

        return compact('currentMonthFees', 'nextMonthFees');
    }

    /**
     * Calculate total fees and discount of existed lines of the user
     * @param $date
     * @return array
     */
    private function getExistedLines($existedVoipLineIds) {
        return MUserLine::select(
                'm_user_lines.start_date as start_date',
                'm_plans.usage_unit_price as line_usage_fee',
                'm_option_plans.usage_unit_price as campaign_discount'
            )
            ->join('t_user_line_plans', 'm_user_lines.id', 't_user_line_plans.line_id')
            ->join('m_plans', 't_user_line_plans.plan_id','m_plans.id')
            ->join('m_option_plans', 't_user_line_plans.option_id1','m_option_plans.id')
            ->where('m_user_lines.shop_id', $this->shop_id)
            ->where('m_user_lines.user_id', $this->user_id)
            ->where('m_user_lines.status','1')
            ->whereNotIn('m_user_lines.voip_line_id', $existedVoipLineIds)
            ->get();
    }

    /**
     * Calculate total fees and discount of existed lines of the user
     * @param $date
     * @return array
     */
    private function geLineRequests() {
        return MUserLineRequest::select(
                'm_user_line_requests.request_type as request_type',
                'm_user_line_requests.start_date as start_date',
                'm_user_line_requests.change_application_date as change_application_date',
                'm_plans.usage_unit_price as line_usage_fee',
                'm_plans.midway_cancell_unit_price as midway_cancell_unit_price',
                'm_option_plans.usage_unit_price as campaign_discount'
            )
            ->join('t_user_line_plans_requests', 'm_user_line_requests.id', 't_user_line_plans_requests.line_id')
            ->join('m_plans', 't_user_line_plans_requests.plan_id','m_plans.id')
            ->join('m_option_plans', 't_user_line_plans_requests.option_id1','m_option_plans.id')
            ->where('m_user_line_requests.shop_id', $this->shop_id)
            ->where('m_user_line_requests.user_id', $this->user_id)
            ->where('m_user_line_requests.request_id', $this->id)
            ->get();
    }


    /**
     * @param $fees
     * @return array
     */
    private function calculateRemainingFee($fees, $tax = 0) {
        $fees['expected_billing_amount'] = round($fees['line_usage_fee'] - $fees['campaign_discount'] +  $fees['daily_line_usage_fee'] + $fees['change_fee'] + $fees['invoice_mailing_service_fee'], 2);
        $fees['consumption_tax'] = round($tax * $fees['expected_billing_amount'], 2);
        $fees['scheduled_withdrawal_amount'] = round($fees['expected_billing_amount'] + $fees['consumption_tax'], 2);

        return $fees;
    }

    /**
     * @param $fees
     * @return array
     */
    private function initFee()
    {
        return [
            'line_usage_fee' => 0,
            'line_usage_number' => 0,
            'daily_line_usage_fee' => 0,
            'daily_line_usage_number' => 0,
            'change_line_number' => 0,
            'change_fee' => 0,
            'invoice_mailing_service_fee' => 0,
            'expected_billing_amount' => 0,
            'consumption_tax' => 0,
            'scheduled_withdrawal_amount' => 0,
            'line_campaign_number' => 0,
            'campaign_discount' => 0,
            'line_campaign_number' => 0,
        ];

    }
}
