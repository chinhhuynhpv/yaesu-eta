<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MShop;
use App\Models\TSale;
use App\Models\TUserLinePlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

/**
 * BA0107「契約インセンティブ計算明細書（回線利用レポート）」のPDF作成バッチ
 */
class ShopIncentivePdfBatch extends Command
{
    /**
     * The name and signature of the console command.
     * （コマンド名: command:pdf_shop_incentives）
     *
     * @var string
     */
    protected $signature = 'command:pdf_shop_incentives';

    /**
     * The console command description.
     * （コマンドの説明）
     *
     * @var string
     */
    protected $description = "Create PDF files of the shop's incentive statement.";

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
    const MIN_NUMBER_OF_LINES = 10;

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
        // 対象年月を取得する。
        // （年月のみ使用する）
        $targetMonth = $nowDate->copy();
        //$targetMonth = $targetMonth->addMonthsNoOverflow(-1);

        // 年月文字列
        $strYMD = $targetMonth->format('Ym');

        // ディレクトリを作成する。
        $storageDir = 'pdf/shop_incentive/' . $strYMD;
        $dirName = storage_path($storageDir);
        if (file_exists($dirName) === false) {
            mkdir($dirName, 0777, true);
        }

        // 販売店データを取得する。
        $shopArray = MShop::orderBy('id')->get();

        $count = 0;
        $minNumberOfLines = self::MIN_NUMBER_OF_LINES;
        foreach ($shopArray as &$shop) {
            // インセンティブ計算明細書データを取得する。
            $incentiveDetailArray = $this->getIncentiveDetails($shop->id, $targetMonth);

            // インセンティブ計算明細書データの件数が 0件の場合は、PDF を作成しない。
            if (count($incentiveDetailArray) == 0) {
                continue;
            }

            $sumIncentivePrice = 0;
            foreach ($incentiveDetailArray as &$incentiveDetail) {
                $sumIncentivePrice += $incentiveDetail->incentive_price;
            }

            $pdf = PDF::loadView('operator/billing/shop_incentive',
                                compact('shop', 'incentiveDetailArray', 'sumIncentivePrice',
                                        'nowDate', 'targetMonth', 'minNumberOfLines'));

            // 用紙サイズと向きの設定 (A4縦)
            $pdf->setPaper('A4', 'portrait');
            // $pdf->setPaper('A3', 'portrait');
            // PDFファイルのサイズを小さくする設定
            $pdf->setOptions([
                'enable_font_subsetting'    => true
            ]);

            // ファイルに保存する。
            $fileName = $dirName . "/" . $strYMD . "_str_incentive_" . $shop->code . ".pdf";
            $pdf->save($fileName);
        }

        return true;
    }

    /**
     * インセンティブ計算明細書データを取得する。
     * @param int $shopId 販売店ID
     * @param $date 日付
     */
    function getIncentiveDetails($shopId, $date) {
        // 売上データを取得する。
        $saleArray = TSale::select(
                            't_sales.user_id',
                            't_sales.shop_id',
                            't_sales.sales_ym',
                            't_sale_details.plan_type',
                            't_sale_details.plan_id',
                            't_sale_details.amount',
                            't_sale_details.incentive_total_price',
                            'm_users.contractor_id',
                            'm_users.contract_name'
                        )
                        ->join('t_sale_details', function($join) {
                            $join->on('t_sale_details.sale_id', '=', 't_sales.id');
                        })
                        ->join('m_users', function($join) {
                            $join->on('m_users.id', '=', 't_sales.user_id');
                        })
                        ->where('t_sales.shop_id', '=', $shopId)
                        ->where('t_sales.sales_ym', '=', $date->format('Ym'))
                        ->whereIn('t_sale_details.plan_type', array('1', '3'))
                        ->orderBy('m_users.contractor_id')
                        ->orderBy('t_sale_details.plan_type')
                        ->orderBy('t_sale_details.plan_id')
                        ->get();
        
        // インセンティブ明細の配列
        $incentiveDetailArray = array();

        foreach ($saleArray as &$sale) {
            if ($sale->plan_type === '1') {             // メインプラン
                // ユーザー回線プランテーブルから、販売店ID, ユーザーID, プランID が一致するデータを
                // 取得する。
                // TODO: 利用開始日、ステータスは見なくても良い？
                $userLinePlanArray = TUserLinePlan::select(
                                'm_user_lines.line_num',            // 回線ID
                                't_user_line_plans.start_date',     // 利用開始日
                                'm_plans.incentive_unit_price'      // インセンティブ単価
                            )
                            ->join('m_user_lines', function($join) {
                                $join->on('m_user_lines.id', '=', 't_user_line_plans.line_id');
                            })
                            ->join('m_plans', function($join) {
                                $join->on('m_plans.id', '=', 't_user_line_plans.plan_id');
                            })
                            ->where('t_user_line_plans.shop_id', '=', $sale->shop_id)
                            ->where('t_user_line_plans.user_id', '=', $sale->user_id)
                            ->where('t_user_line_plans.plan_id', '=', $sale->plan_id)
                            ->whereNull('t_user_line_plans.plan_set_end_date')
                            ->orderBy('t_user_line_plans.plan_id')
                            ->get();

                // 売上データと、ユーザー回線プランのデータから、インセンティブ明細データを作成し、
                // 配列に追加する。
                foreach ($userLinePlanArray as &$userLinePlan) {
                    $incentiveDetail = new \stdClass();
                    $incentiveDetail->data_kind = '回線利用';
                    $incentiveDetail->line_num = $userLinePlan->line_num;
                    $targetMonth = $this->convertDate($sale->sales_ym);
                    $startDate = new Carbon($userLinePlan->start_date);
                    $incentiveDetail->target_month = isset($targetMonth) ? $targetMonth->format('Y年m月分') : '';
                    $incentiveDetail->start_date = $startDate->format('Y/m/d');
                    if (isset($startDate) && isset($targetMonth)) {
                        $incentiveDetail->use_months = $this->calcUseMonths($startDate, $targetMonth);
                    } else {
                        $incentiveDetail->use_months = '';      // 利用月数
                    }
                    $incentiveDetail->contractor_id = $sale->contractor_id;
                    $incentiveDetail->contract_name = $sale->contract_name;
                    $incentiveDetail->incentive_price = $userLinePlan->incentive_unit_price;

                    $incentiveDetailArray[] = $incentiveDetail;
                }

            } else if ($sale->plan_type === '3') {      // 拡販プログラム
                // 売上データから、インセンティブ明細データを作成し、配列に追加する。
                $incentiveDetail = new \stdClass();
                $incentiveDetail->data_kind = '販売報奨金';
                $incentiveDetail->line_num = '新規回線キャンペーン';
                $targetMonth = $this->convertDate($sale->sales_ym);
                $incentiveDetail->target_month = isset($targetMonth) ? $targetMonth->format('Y年m月分') : '';
                $incentiveDetail->start_date = '';      // 利用開始日
                $incentiveDetail->use_months = '';      // 利用月数
                $incentiveDetail->contractor_id = $sale->contractor_id;
                $incentiveDetail->contract_name = $sale->contract_name;
                $incentiveDetail->incentive_price = $sale->incentive_total_price;

                $incentiveDetailArray[] = $incentiveDetail;
            }
        }

        return  $incentiveDetailArray;
    }

    /**
     * YYYYmm 文字列を日付型に変換する。
     * @param $yyyymm 年月文字列
     * @return  日付型
     */
    private function convertDate($yyyymm) {
        if (strlen($yyyymm) == 6) {
            return  Carbon::createFromDate(substr($yyyymm, 0, 4), substr($yyyymm, 4), 1);
        } else {
            return  null;
        }
    }

    /**
     * 利用月数を計算して返す。
     * @param $startDate 利用開始日
     * @param $currentDate 現在の月
     * @return int 利用月数
     */
    private function calcUseMonths($startDate, $currentDate) {
        $tmpStart = Carbon::createFromDate($startDate->year, $startDate->month, 1);
        $tmpCurrent = Carbon::createFromDate($currentDate->year, $currentDate->month, 1);
        if ($tmpCurrent->lt($tmpStart)) {
            return  0;
        }
        $subYear = $currentDate->year - $startDate->year;
        $subMonth = $currentDate->month - $startDate->month;

        if ($subMonth < 0) {
            $subMonth += 12;
            $subYear -= 1;
        }

        return ($subYear * 12) + $subMonth + 1;
    }
}
