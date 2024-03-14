<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Models\WBillingDetail;
use App\Models\WBilling;
use App\Models\TBillingDetail;
use App\Models\TBillinge;
use Illuminate\Support\Facades\Log;
use stdClass;

class CreateBillings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_billings {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'when the end of the month, create billings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel("batch")->info(__METHOD__ . ' ' . 'START');
        $date = $this->argument('date');
        if ($date == null) {
            $date = now()->format('Ymd');
        }
        // 1日夜間に前月を対象に処理を行う。
        $targetDate = CarbonImmutable::createFromFormat('Ymd', $date)->subMonths(1);
        
        // トランザクションスタート
        DB::beginTransaction();
        try {

            // ワークテーブルの初期化
            $this->initdata();
            // 対象データをワークテーブルに登録
            $this->createWuserLinePlans();
            $this->createWlinePauses($targetDate);
            $this->createWcommissions($targetDate);
            $this->createWshopSalesPromotionPlans($targetDate);
            // 日割りか通常か分類
            $this->updateWuserLinePlans($targetDate);
            $targetList = DB::table('w_user_line_plans')->orderBy('shop_id')->orderBy('user_id')->orderBy('start_date')->orderBy('line_id')->get();
            foreach($targetList as $linePlan) {
                // 日割　新規開線
                if ($linePlan->billing_calc_type == config('const.billing_calc_type.daily_new')) {
                    // メインプラン
                    $this->createWbillingDetailsForDailyNewMain($targetDate, $linePlan);
                    // オプションプラン
                    $this->createWbillingDetailsForDailyNewOption($targetDate, $linePlan);
                }
                // 日割　再開
                if ($linePlan->billing_calc_type == config('const.billing_calc_type.daily_restart')) {
                    // メインプラン
                    $this->createWbillingDetailsForDailyRestartMain($targetDate, $linePlan);
                    // オプションプラン
                    $this->createWbillingDetailsForDailyRestartOption($targetDate, $linePlan);
                }
                // 通常
                if ($linePlan->billing_calc_type == config('const.billing_calc_type.monthly')) {
                    // メインプラン
                    $this->createWbillingDetailsForMothlyMain($linePlan);
                    // オプションプラン
                    $this->createWbillingDetailsForMothlyOption($linePlan);
                }
                // 手数料
                $this->createWbillingDetailsFromCommistions($linePlan);
                // 販売プロモーション
                $this->createWbillingDetailsFromPromotionPlans($linePlan);
            }

            // 回線、オプションを契約日で集計
            $this->createWbillingDetailsTotalling();
            // 請求明細のヘッダを作成
            $this->createWbilling($targetDate);
            // 当月請求を削除
            $this->deleteTbilling($targetDate);
            // ワークテーブルからt_billing,t_billingdetailsへ
            $this->createTbillingAndDetails();

            DB::commit();

        } catch (\Exception $e) {
            // 処理途中にエラーが発生したら、全ての更新を元に戻す
            DB::rollback();
            Log::channel("batch")->info(__METHOD__ . ' ' . '登録に失敗しました');
            return false;
        }

        Log::channel("batch")->info(__METHOD__ . ' ' . 'END');
        return true;
    }
    /**
     * 初期処理ワークテーブルのdelete
     *
     * @return void
     */
    private function initdata(): void {
        DB::table('w_user_line_plans')->delete();
        DB::table('w_line_pauses')->delete();
        DB::table('w_user_commissions')->delete();
        DB::table('w_shop_sales_promotion_plans')->delete();
        DB::table('w_billinges')->delete();
        DB::table('w_billing_details')->delete();
    }
    /**
     * t_user_line_plansをワークテーブルに登録
     *
     * @return void
     */
    private function createWuserLinePlans(): void {
        // ステータスが利用中の回線プランを取得
        $t_plan = DB::table('t_user_line_plans')
            ->where('t_user_line_plans.line_status', config('const.t_user_line_plans.line_status.in_use'))
            ->where('month_left_num', '>', 0)->get()->toArray();
        // 対象が存在する
        if (count($t_plan) > 0) {

            $t_plan_array = $this->convertToArray($t_plan);
            DB::table('w_user_line_plans')->insert($t_plan_array);
        }
        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_user_line_plans 処理対象:' . count($t_plan) . '件');
    }
    /**
     * t_line_pausesをワークテーブルに登録
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function createWlinePauses(CarbonImmutable $date): void {
        // w_user_line_plans
        $t_line_plan_id = DB::table('w_user_line_plans')->select('id')->get()->toArray();

        $t_line_plan_id_array = $this->convertToArray($t_line_plan_id);
        // 休止で日割り対象となるものを取得
        $t_pauses = DB::table('t_line_pauses')->whereIn('line_plans_id', $t_line_plan_id_array)->whereDate('pause_end_date', '<' , $date->endOfMonth())->get()->toArray();
        // 対象が存在する
        if (count($t_pauses) > 0) {

            $t_pauses_array = $this->convertToArray($t_pauses);
            DB::table('w_line_pauses')->insert($t_pauses_array);
        }
        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_line_pauses 処理対象:' . count($t_pauses) . '件');
    }

    /**
     * t_user_commissionsをワークテーブルに登録
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function createWcommissions(CarbonImmutable $date): void {
        // 当月発生した手数料
        $thisMonth = $date->format('m');
        $t_user_commissions = DB::table('t_user_commissions')->whereMonth('commission_date', $thisMonth)->get()->toArray();
        // 対象が存在する
        if (count($t_user_commissions) > 0) {

            $t_user_commissions_array = $this->convertToArray($t_user_commissions);
            DB::table('w_user_commissions')->insert($t_user_commissions_array);
        }
        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_user_commissions 処理対象:' . count($t_user_commissions) . '件');
    }

    /**
     * t_shop_sales_promotion_plansをワークテーブルに登録
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function createWshopSalesPromotionPlans(CarbonImmutable $date): void {
        // 当月発生したプロモーション
        $thisMonth = $date->format('m');
        $t_shop_sales_promotion_plans = DB::table('t_shop_sales_promotion_plans')->whereMonth('incentive_date', $thisMonth)->get()->toArray();
        if (count($t_shop_sales_promotion_plans) > 0) {

            $promotion_array = $this->convertToArray($t_shop_sales_promotion_plans);
            DB::table('w_shop_sales_promotion_plans')->insert($promotion_array);
        }
        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_shop_sales_promotion_plans 処理対象:' . count($t_shop_sales_promotion_plans) . '件');
    }
    /**
     * 日割りか通常の請求作成かを分類する
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function updateWuserLinePlans(CarbonImmutable $date): void {
        // 日割り対象1 新規追加
        $thisMonth = $date->format('m');
        $newlinelist = DB::table('w_user_line_plans')->whereMonth('start_date', $thisMonth)->select('id')->get()->toArray();
        if (count($newlinelist) > 0) {
            $newlinelist_array = $this->convertToArray($newlinelist);
            // 日割で新規追加は請求計算タイプ 1(日割り新規)を設定
            DB::table('w_user_line_plans')->whereIn('id', $newlinelist_array)->update(['billing_calc_type' => config('const.billing_calc_type.daily_new')]);
        }
        // 日割り対象2 休止/再開
        $restartlist = DB::table('w_line_pauses')->select('line_plans_id')->get()->toArray();
        if (count($restartlist) > 0) {

            $restartlist_array = $this->convertToArray($restartlist);
            // 日割で休止/再開は請求計算タイプ 2(日割り再開)を設定
            DB::table('w_user_line_plans')->whereIn('id', $restartlist_array)->update(['billing_calc_type' => config('const.billing_calc_type.daily_restart')]);
        }
        // その他を更新
        $daylylist = DB::table('w_user_line_plans')->whereIn('billing_calc_type',
                [config('const.billing_calc_type.daily_new'), config('const.billing_calc_type.daily_restart')])->select('id')->get()->toArray();
        if (count($daylylist) > 0) {

            $daylylist_array = $this->convertToArray($daylylist);
            // 通常の場合は請求計算タイプ 3(通常)を設定
            DB::table('w_user_line_plans')->whereNotIn('id', $daylylist_array)->update(['billing_calc_type' => config('const.billing_calc_type.monthly')]);
        }
    }
    /**
     * 請求明細ワークテーブルに新規追加（日割り）メインプランのレコードを登録する。
     *
     * @param CarbonImmutable $date
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForDailyNewMain(CarbonImmutable $date, stdClass $linePlan): void {
        $endDay = $date->endOfMonth()->day;
        // 日割り対象1 新規追加
        $startDay = CarbonImmutable::parse($linePlan->start_date)->day;
        $m_plan = DB::table('m_plans')->where('id', $linePlan->plan_id)->first();
        $dayliyPrice = $this->calcDayliyPrice($startDay ,$endDay, $m_plan->usage_unit_price);

        $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                        'user_id' => $linePlan->user_id,
                                        'billing_id' => null,
                                        'line_id' => $linePlan->line_id,
                                        'contract_date' =>  $linePlan->start_date,
                                        'plan_type' => config('const.plan_type.main'),
                                        'plan_id' => $m_plan->id,
                                        'plan_num' => $m_plan->plan_num,
                                        'plan_name' =>  $m_plan->plan_name  . ' * 日割',
                                        'unit_price' => $dayliyPrice,
                                        'incentive_unit_price' => 0,
                                        'amount' => 1,
                                        'total_price' =>  $dayliyPrice * 1,
                                        'incentive_total_price' => 0,
        ]);
    }
    /**
     * 請求明細ワークテーブルに新規追加（日割り）オプションプランのレコードを登録する。
     *
     * @param CarbonImmutable $date
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForDailyNewOption(CarbonImmutable $date, stdClass $linePlan): void {
         // 月末を取得
        $endDay = $date->endOfMonth()->day;
        $otionlist = $this->createOptionList($linePlan);
        // オプションごとにレコードを作成
        foreach ($otionlist as $value) {
            $startDay = CarbonImmutable::parse($linePlan->start_date)->day;
            $m_option = DB::table('m_option_plans')->where('id', $value)->first();
            $dayliyPrice = $this->calcDayliyPrice($startDay ,$endDay, $m_option->usage_unit_price);
            $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                            'user_id' => $linePlan->user_id,
                                            'billing_id' => null,
                                            'line_id' => $linePlan->line_id,
                                            'contract_date' =>  $linePlan->start_date,
                                            'plan_type' => config('const.plan_type.option'),
                                            'plan_id' => $m_option->id,
                                            'plan_num' => $m_option->plan_num,
                                            'plan_name' =>  $m_option->plan_name  . ' * 日割',
                                            'unit_price' => $dayliyPrice,
                                            'incentive_unit_price' => 0,
                                            'amount' => 1,
                                            'total_price' =>  $dayliyPrice * 1,
                                            'incentive_total_price' => 0,
            ]);
        }
    }
    /**
     * 請求明細ワークテーブルに休止/再開（日割り）メインプランのレコードを登録する。
     *
     * @param CarbonImmutable $date
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForDailyRestartMain(CarbonImmutable $date, stdClass $linePlan): void {

        // 月末を取得
        $endDay = $date->endOfMonth()->day;
        // 日割り対象2 休止/再開
        $restartline = DB::table('w_line_pauses')->where('line_plans_id', $linePlan->id)->first();
        $startDay = CarbonImmutable::parse($restartline->pause_end_date)->day;
        $m_plan = DB::table('m_plans')->where('id', $linePlan->plan_id)->first();
        $dayliyPrice = $this->calcDayliyPrice($startDay ,$endDay, $m_plan->usage_unit_price);
        $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                        'user_id' => $linePlan->user_id,
                                        'billing_id' => null,
                                        'line_id' => $linePlan->line_id,
                                        'contract_date' =>  $linePlan->start_date,
                                        'plan_type' => config('const.plan_type.main'),
                                        'plan_id' => $m_plan->id,
                                        'plan_num' => $m_plan->plan_num,
                                        'plan_name' =>  $m_plan->plan_name  . ' * 日割',
                                        'unit_price' => $dayliyPrice,
                                        'incentive_unit_price' => 0,
                                        'amount' => 1,
                                        'total_price' =>  $dayliyPrice * 1,
                                        'incentive_total_price' => 0,
        ]);
    }
    /**
     * 請求明細ワークテーブルに休止/再開（日割り）オプションプランのレコードを登録する。
     *
     * @param CarbonImmutable $date
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForDailyRestartOption(CarbonImmutable $date, stdClass $linePlan): void {
        // 月末を取得
        $endDay = $date->endOfMonth()->day;
        $restartline = DB::table('w_line_pauses')->where('line_plans_id', $linePlan->id)->first();
        $startDay = CarbonImmutable::parse($restartline->pause_end_date)->day;
        $otionlist = $this->createOptionList($linePlan);
        // オプションごとにレコードを作成
        foreach ($otionlist as $value) {
            $m_option = DB::table('m_option_plans')->where('id', $value)->first();
            $dayliyPrice = $this->calcDayliyPrice($startDay ,$endDay, $m_option->usage_unit_price);
            $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                            'user_id' => $linePlan->user_id,
                                            'billing_id' => null,
                                            'line_id' => $linePlan->line_id,
                                            'contract_date' =>  $linePlan->start_date,
                                            'plan_type' => config('const.plan_type.option'),
                                            'plan_id' => $m_option->id,
                                            'plan_num' => $m_option->plan_num,
                                            'plan_name' =>  $m_option->plan_name  . ' * 日割',
                                            'unit_price' => $dayliyPrice,
                                            'incentive_unit_price' => 0,
                                            'amount' => 1,
                                            'total_price' =>  $dayliyPrice * 1,
                                            'incentive_total_price' => 0,
            ]);
        }
    }
    /**
     * 請求明細ワークテーブルに通常メインプランのレコードを登録する。
     *
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForMothlyMain(stdClass $linePlan):void {
        // 通常1か月
        $m_plan = DB::table('m_plans')->where('id', $linePlan->plan_id)->first();
        $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                        'user_id' => $linePlan->user_id,
                                        'billing_id' => null,
                                        'line_id' => $linePlan->line_id,
                                        'contract_date' =>  $linePlan->start_date,
                                        'plan_type' => config('const.plan_type.main'),
                                        'plan_id' => $m_plan->id,
                                        'plan_num' => $m_plan->plan_num,
                                        'plan_name' =>  $m_plan->plan_name,
                                        'unit_price' => $m_plan->usage_unit_price,
                                        'incentive_unit_price' => $m_plan->incentive_unit_price,
                                        'amount' => 1,
                                        'total_price' =>  $m_plan->usage_unit_price * 1,
                                        'incentive_total_price' => $m_plan->incentive_unit_price * 1,
        ]);
    }
    /**
     * 請求明細ワークテーブルに通常オプションプランのレコードを登録する。
     *
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsForMothlyOption(stdClass $linePlan): void {
        // 通常1か月
        $otionlist = $this->createOptionList($linePlan);
        // オプションごとにレコードを作成
        foreach ($otionlist as $value) {
            $m_option = DB::table('m_option_plans')->where('id', $value)->first();
            $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                            'user_id' => $linePlan->user_id,
                                            'billing_id' => null,
                                            'line_id' => $linePlan->line_id,
                                            'contract_date' =>  $linePlan->start_date,
                                            'plan_type' => config('const.plan_type.option'),
                                            'plan_id' => $m_option->id,
                                            'plan_num' => $m_option->plan_num,
                                            'plan_name' =>  $m_option->plan_name,
                                            'unit_price' => $m_option->usage_unit_price,
                                            'incentive_unit_price' => $m_option->incentive_unit_price,
                                            'amount' => 1,
                                            'total_price' =>  $m_option->usage_unit_price * 1,
                                            'incentive_total_price' => $m_option->incentive_unit_price * 1,
            ]);
        }
    }

    /**
     * 請求明細ワークテーブルに当月発生した販促プロモーションプランのレコードを登録する。
     *
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsFromPromotionPlans(stdClass $linePlan): void {
        // プランタイプ3 拡販プログラム
        $promotionList = DB::table('w_shop_sales_promotion_plans')->where('line_id', $linePlan->line_id)->get();
        foreach($promotionList as $item){
            $m_promotion = DB::table('m_sales_promotion_plans')->where('id', $item->sales_promotion_id)->first();
            $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                            'user_id' => $linePlan->user_id,
                                            'billing_id' => null,
                                            'line_id' => $linePlan->line_id,
                                            'contract_date' =>  $linePlan->start_date,
                                            'plan_type' => config('const.plan_type.promotion'),
                                            'plan_id' => $m_promotion->id,
                                            'plan_num' => $m_promotion->plan_num,
                                            'plan_name' =>  $m_promotion->plan_name,
                                            'unit_price' => $m_promotion->usage_unit_price,
                                            'incentive_unit_price' => $m_promotion->incentive_unit_price,
                                            'amount' => 1,
                                            'total_price' =>  $m_promotion->usage_unit_price * 1,
                                            'incentive_total_price' => $m_promotion->incentive_unit_price * 1,
            ]);
        }
    }
    /**
     * 請求明細ワークテーブルに当月発生した手数料のレコードを登録する。
     *
     * @param stdClass $linePlan
     * @return void
     */
    private function createWbillingDetailsFromCommistions(stdClass $linePlan): void {
        // プランタイプ4 手数料
        $commissionList = DB::table('w_user_commissions')->where('line_id', $linePlan->line_id)->get();
        foreach($commissionList as $item){
            $m_commission = DB::table('m_commissions')->where('id', $item->commission_id)->first();
            $detail = WBillingDetail::create(['shop_id' => $linePlan->shop_id,
                                            'user_id' => $linePlan->user_id,
                                            'billing_id' => null,
                                            'line_id' => $linePlan->line_id,
                                            'contract_date' =>  $linePlan->start_date,
                                            'plan_type' => config('const.plan_type.commission'),
                                            'plan_id' => $m_commission->id,
                                            'plan_num' => $m_commission->plan_num,
                                            'plan_name' =>  $m_commission->plan_name,
                                            'unit_price' => $m_commission->usage_unit_price,
                                            'incentive_unit_price' => $m_commission->incentive_unit_price,
                                            'amount' => 1,
                                            'total_price' =>  $m_commission->usage_unit_price * 1,
                                            'incentive_total_price' => $m_commission->incentive_unit_price * 1,
            ]);
        }
    }
    /**
     * 請求明細ワークテーブルをグループピングして集計する。契約日でまとめる。
     *
     * @return void
     */
    private function createWbillingDetailsTotalling(): void {
        // 契約日で明細を集計する。
        $result = DB::table('w_billing_details')
                        ->select('shop_id', 'user_id', 'plan_id', 'plan_num', 'contract_date', 'plan_type', 'plan_name')
                        ->selectRaw('MAX(unit_price) AS unit_price')
                        ->selectRaw('MAX(incentive_unit_price) AS incentive_unit_price')
                        ->selectRaw('SUM(amount) AS amount')
                        ->selectRaw('SUM(unit_price) AS total_price')
                        ->selectRaw('SUM(incentive_unit_price) AS incentive_total_price')
                        ->groupBy('shop_id', 'user_id', 'contract_date', 'plan_type', 'plan_id', 'plan_name', 'plan_num', 'unit_price')
                        ->orderBy('shop_id')
                        ->orderBy('user_id')
                        ->orderBy('contract_date')
                        ->orderBy('plan_type')
                        ->orderBy('plan_id')
                        ->get();

        foreach ($result as $item) {
            $detail = WBillingDetail::create(['shop_id' => $item->shop_id,
                                        'user_id' => $item->user_id,
                                        'billing_id' => null,
                                        'line_id' =>  null,
                                        'contract_date' =>  $item->contract_date,
                                        'plan_type' => $item->plan_type,
                                        'plan_id' => $item->plan_id,
                                        'plan_num' => $item->plan_num,
                                        'plan_name' =>  $item->plan_name,
                                        'unit_price' => $item->unit_price,
                                        'incentive_unit_price' => $item->incentive_unit_price,
                                        'amount' => $item->amount,
                                        'total_price' =>  $item->total_price,
                                        'incentive_total_price' => $item->incentive_total_price,
                                        'totalling' => true,
            ]);
        }
    }
    /**
     * 請求明細ワークテーブルをグループピングして請求ワークテーブルを作成する。
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function createWbilling(CarbonImmutable $date): void {
        // 明細行の集計を行う
        $details = DB::table('w_billing_details')
            ->select('shop_id', 'user_id')
            ->selectRaw('SUM(total_price) AS total_price')
            ->selectRaw('SUM(incentive_total_price) AS incentive_total_price')
            ->where('totalling', true)
            ->groupBy('shop_id', 'user_id')
            ->orderBy('shop_id')
            ->orderby('user_id')
            ->get();

        foreach ($details as $item) {
            $result = WBilling::create(['shop_id' => $item->shop_id,
                                        'user_id' => $item->user_id,
                                        'billing_ym' => $this->getBillingYm($date),
                                        'billing_total_price' =>  $item->total_price,
                                        'incentive_total_price' => $item->incentive_total_price,
                                        'status' => config('const.billing_status.new')
                                    ]);
            // 取得したIDで明細行を更新する
            WBillingDetail::where('shop_id', $result->shop_id)
                                    ->where('user_id', $result->user_id)
                                    ->update([
                                        'billing_id' => $result->id,
                                    ]);
        }
    }
    /**
     * 請求ワークテーブルから請求テーブルと請求明細テーブルを作成する。
     *
     * @return void
     */
    private function createTbillingAndDetails(): void {

        $wbilling = WBilling::orderBy('shop_id')
                            ->orderby('user_id')
                            ->get();

        foreach($wbilling as $item) {
            $result = TBillinge::create(['user_id' => $item->user_id,
                                        'shop_id' => $item->shop_id,
                                        'billing_ym' => $item->billing_ym,
                                        'billing_total_price' => $item->billing_total_price,
                                        'incentive_total_price' => $item->incentive_total_price,
                                        'status' => $item->status
                                    ]);
            $this->createTbillingDetails($result);
        }
    }

    /**
     * 請求明細ワークテーブルから請求明細テーブルを作成する。
     *
     * @param TBillinge $result
     * @return void
     */
    private function createTbillingDetails(TBillinge $result): void {

        $details = WBillingDetail::where('shop_id', $result->shop_id)
                                    ->where('user_id', $result->user_id)
                                    ->where('totalling', true)
                                    ->get();
        foreach($details as $item) {
            TBillingDetail::create(['billing_id' => $result->id,
                                        'contract_date' => $item->contract_date,
                                        'plan_type' => $item->plan_type,
                                        'plan_id' => $item->plan_id,
                                        'plan_num' => $item->plan_num,
                                        'plan_name' => $item->plan_name,
                                        'unit_price' => $item->unit_price,
                                        'incentive_unit_price' => $item->incentive_unit_price,
                                        'amount' => $item->amount,
                                        'total_price' => $item->total_price,
                                        'incentive_total_price' => $item->incentive_total_price
            ]);
        }
    }
    /**
     * 当月請求を削除する（リラン時の処理）
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function deleteTbilling(CarbonImmutable $date): void {
        $billingYm = $this->getBillingYm($date);
        $billing = TBillinge::where('billing_ym', $billingYm)->get();
        foreach ($billing as $item) {
            TBillingDetail::where('billing_id', $item->id)->delete();
            TBillinge::where('id', $item->id)->delete();
        }
    }
    
    /**
     * オプションリストを作る
     *
     * @param stdClass $linePlan
     * @return array
     */
    private function createOptionList(stdClass $linePlan): array {
        $optionlist = array();
        $count = 0;
        if ($linePlan->option_id1 != null 
        && $this->checkRemainPeriod($linePlan->option_id1, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id1;
            $count++;
        }
        if ($linePlan->option_id2 != null 
        && $this->checkRemainPeriod($linePlan->option_id2, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id2;
            $count++;
        }
        if ($linePlan->option_id3 != null
        && $this->checkRemainPeriod($linePlan->option_id3, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id3;
            $count++;
        }
        if ($linePlan->option_id4 != null
        && $this->checkRemainPeriod($linePlan->option_id4, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id4;
            $count++;
        }
        if ($linePlan->option_id5 != null
        && $this->checkRemainPeriod($linePlan->option_id5, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id5;
            $count++;
        }
        if ($linePlan->option_id6 != null
        && $this->checkRemainPeriod($linePlan->option_id6, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id6;
            $count++;
        }
        if ($linePlan->option_id7 != null
        && $this->checkRemainPeriod($linePlan->option_id7, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id7;
            $count++;
        }
        if ($linePlan->option_id8 != null
        && $this->checkRemainPeriod($linePlan->option_id8, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id8;
            $count++;
        }
        if ($linePlan->option_id9 != null
        && $this->checkRemainPeriod($linePlan->option_id9, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id9;
            $count++;
        }
        if ($linePlan->option_id10 != null
        && $this->checkRemainPeriod($linePlan->option_id10, $linePlan->limited_time_month_left_num)) {
            $optionlist[$count] = $linePlan->option_id10;
            $count++;
        }

        return $optionlist;
    }

    private function checkRemainPeriod(int $id, int $remainPeriod):bool {
        $result = false;
        $item = DB::table('m_option_plans')->where('id', $id)->first();
        // オプションタイプ:機能
        if ($item->option_type == '1') {
            $result = true;
            return $result;
        }
        // オプションタイプ:割引 期間==0
        if ($item->option_type == '2' && $item->period == 0) {
            $result = true;
            return $result;
        }
        // オプションタイプ:割引 期間!=0 残月数あり
        if ($item->option_type == '2' && $remainPeriod != 0) {
            $result = true;
            return $result;
        }
        return $result;
    }

    /**
     * array/stdclassからarray/arrayに変換
     *
     * @param array $object
     * @return array
     */
    private function convertToArray(array $object): array {
        return json_decode(json_encode($object), true);
    }

    /**
     * 日割計算処理
     *
     * @param integer $startDay
     * @param integer $endDay
     * @param integer $price
     * @return integer
     */
    private function calcDayliyPrice(int $startDay, int $endDay, int $price):int {
        $dayliyPrice = round($price * (($endDay - $startDay) / $endDay));
        return $dayliyPrice;
    }

    /**
     * 請求年月を取得する。
     *
     * @param CarbonImmutable $date
     * @return string
     */
    private function getBillingYm(CarbonImmutable $date):string {
        //$newdate = $date->addMonthsNoOverflow();
        $billingYm = $date->format('Ym');
        return $billingYm;
    }
}
