<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MShop;
use App\Models\TUserLinePlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

/**
 * BA0104「回線利用レポート」のPDF作成バッチ
 */
class ShopLinePdfBatch extends Command
{
    /**
     * The name and signature of the console command.
     * （コマンド名: command:pdf_shop_lines）
     *
     * @var string
     */
    protected $signature = 'command:pdf_shop_lines';

    /**
     * The console command description.
     * （コマンドの説明）
     *
     * @var string
     */
    protected $description = "Create PDF files of the shop's line usage report.";

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
     * 最小の表示行数
     */
    const MIN_NUMBER_OF_LINES = 20;

    /**
     * Execute the console command.
     * （コマンドの実行）
     *
     * @return int
     */
    public function handle()
    {
        // 現在の日付を取得する。
        $nowDate = Carbon::now();

        // 対象年月（先月の日付）を取得する。
        // （年月のみ使用する）
        $targetMonth = $nowDate->copy();
        $targetMonth = $targetMonth->addMonthsNoOverflow(-1);

        // 年月文字列
        $strYMD = $targetMonth->format('Ym');

        // ディレクトリを作成する。
        $storageDir = 'pdf/shop_line/' . $strYMD;
        $dirName = storage_path($storageDir);
        if (file_exists($dirName) === false) {
            mkdir($dirName, 0777, true);
        }
        
        // 販売店データを取得する。
        $shopArray = MShop::orderBy('id')->get();

        $count = 0;
        $minNumberOfLines = self::MIN_NUMBER_OF_LINES;
        foreach ($shopArray as &$shop) {
            // ユーザー回線プランを取得する。
            $userLinePlanArray = $this->getUserLinePlans($shop->id);

            // ユーザー回線プランの件数が 0件の場合は、PDF を作成しない。
            if (count($userLinePlanArray) == 0) {
                continue;
            }

            $pdf = PDF::loadView('operator/billing/shop_line',
                                compact('shop', 'userLinePlanArray', 'nowDate', 'targetMonth', 'minNumberOfLines'));

            // 用紙サイズと向きの設定 (A4縦)
            $pdf->setPaper('A4', 'portrait');
            // $pdf->setPaper('A3', 'portrait');
            // PDFファイルのサイズを小さくする設定
            $pdf->setOptions([
                'enable_font_subsetting'    => true
            ]);

            // ファイルに保存する。
            $fileName = $dirName . "/" . $strYMD . "_subscriber_" . $shop->code . ".pdf";
            $pdf->save($fileName);

        }

        return true;
    }

    /**
     * ユーザー回線プランを取得する。(ユーザーID、プランID 順)
     * @param int $shopId 販売店ID
     */
    function getUserLinePlans($shopId) {
        // TODO: 利用開始日、ステータスは見なくても良い？
        $userLinePlanArray = TUserLinePlan::select(
                    't_user_line_plans.id',
                    't_user_line_plans.line_id',
                    'm_user_lines.voip_line_id',
                    't_user_line_plans.shop_id',
                    't_user_line_plans.user_id',
                    'm_users.contractor_id',
                    'm_users.contract_name',
                    't_user_line_plans.plan_id',
                    'm_plans.plan_num',
                    DB::raw("DATE_FORMAT(t_user_line_plans.start_date, '%Y/%m/%d') AS start_date"),
                    't_user_line_plans.end_date',
                    DB::raw('OP1.plan_num  AS option_plan_num1'),
                    DB::raw('OP2.plan_num  AS option_plan_num2'),
                    DB::raw('OP3.plan_num  AS option_plan_num3'),
                    DB::raw('OP4.plan_num  AS option_plan_num4'),
                    DB::raw('OP5.plan_num  AS option_plan_num5'),
                    DB::raw('OP6.plan_num  AS option_plan_num6'),
                    DB::raw('OP7.plan_num  AS option_plan_num7'),
                    DB::raw('OP8.plan_num  AS option_plan_num8'),
                    DB::raw('OP9.plan_num  AS option_plan_num9'),
                    DB::raw('OP10.plan_num AS option_plan_num10'),
                    't_user_line_plans.plan_set_start_date',
                    't_user_line_plans.plan_set_end_date'            
                )
                ->join('m_user_lines', function($join) {
                    $join->on('m_user_lines.id', '=', 't_user_line_plans.line_id');
                })
                ->join('m_users', function($join) {
                    $join->on('m_users.id', '=', 't_user_line_plans.user_id');
                })
                ->leftJoin('m_plans', function($join) {
                    $join->on('m_plans.id', '=', 't_user_line_plans.plan_id');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP1'), function($join) {
                    $join->on('OP1.id', '=', 't_user_line_plans.option_id1')
                        ->where('OP1.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP2'), function($join) {
                    $join->on('OP2.id', '=', 't_user_line_plans.option_id2')
                        ->where('OP2.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP3'), function($join) {
                    $join->on('OP3.id', '=', 't_user_line_plans.option_id3')
                        ->where('OP3.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP4'), function($join) {
                    $join->on('OP4.id', '=', 't_user_line_plans.option_id4')
                        ->where('OP4.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP5'), function($join) {
                    $join->on('OP5.id', '=', 't_user_line_plans.option_id5')
                        ->where('OP5.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP6'), function($join) {
                    $join->on('OP6.id', '=', 't_user_line_plans.option_id6')
                        ->where('OP6.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP7'), function($join) {
                    $join->on('OP7.id', '=', 't_user_line_plans.option_id7')
                        ->where('OP7.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP8'), function($join) {
                    $join->on('OP8.id', '=', 't_user_line_plans.option_id8')
                        ->where('OP8.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP9'), function($join) {
                    $join->on('OP9.id', '=', 't_user_line_plans.option_id9')
                        ->where('OP9.option_type', '=', '1');
                })
                ->leftJoin(DB::raw('m_option_plans AS OP10'), function($join) {
                    $join->on('OP10.id', '=', 't_user_line_plans.option_id10')
                        ->where('OP10.option_type', '=', '1');
                })
                ->where('t_user_line_plans.shop_id', '=', $shopId)
                ->whereNull('t_user_line_plans.plan_set_end_date')

                ->orderBy('m_users.contractor_id')
                ->orderBy('t_user_line_plans.plan_id')
                ->get();

        foreach ($userLinePlanArray as &$userLinePlan) {
            // プランおよびオプションプランの「プランNO」を配列に設定
            $planNumArray = array(
                                $userLinePlan->plan_num,
                                $userLinePlan->option_plan_num1,
                                $userLinePlan->option_plan_num2,
                                $userLinePlan->option_plan_num3,
                                $userLinePlan->option_plan_num4,
                                $userLinePlan->option_plan_num5,
                                $userLinePlan->option_plan_num6,
                                $userLinePlan->option_plan_num7,
                                $userLinePlan->option_plan_num8,
                                $userLinePlan->option_plan_num9,
                                $userLinePlan->option_plan_num10
                            );
            // 空欄を取り除く。
            $planNumArray = array_filter($planNumArray);

            // カンマで結合して、plan_nums にセットする。
            $userLinePlan->plan_nums = implode(',', $planNumArray);
        }

        return  $userLinePlanArray;
    }
}
