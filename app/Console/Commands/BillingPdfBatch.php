<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TBillinge;
use App\Models\TBillingDetail;
use App\Models\MTax;
use App\Models\MUser;
use Carbon\Carbon;
use PDF;

/**
 * BA0103 「ご利用明細書」のPDF作成バッチ
 */
class BillingPdfBatch extends Command
{
    /**
     * The name and signature of the console command.
     * （コマンド名: command:pdf_billings）
     *
     * @var string
     */
    protected $signature = 'command:pdf_billings';

    /**
     * The console command description.
     * （コマンドの説明）
     * 
     * @var string
     */
    protected $description = 'Create PDF files of the invoice.';

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
     * 振替日（毎月 13 日）
     */
    const TRANSFER_DAY = 13;

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

        // 請求対象期間（先月の 1日 と 月末）
        $billingMonthStart = $nowDate->copy();
        $billingMonthStart = $billingMonthStart->addMonthsNoOverflow(-1);
        $billingMonthStart = Carbon::createFromDate($billingMonthStart->year, $billingMonthStart->month, 1);
        $billingMonthEnd = $billingMonthStart->copy();
        $billingMonthEnd = $billingMonthEnd->addMonthsNoOverflow(1);
        $billingMonthEnd = $billingMonthEnd->addDay(-1);

        // 抽出する請求年月
        $billingYM = $billingMonthStart->format('Ym');

        // ディレクトリを作成する。
        $storageDir = 'pdf/billing/' . $billingYM;
        $dirName = storage_path($storageDir);
        if (file_exists($dirName) === false) {
            mkdir($dirName, 0777, true);
        }
        
        // 振替日（営業日）
        if ($nowDate->day >= self::TRANSFER_DAY) {
            $transferDate = $nowDate->copy();
            $transferDate = $transferDate->addMonthsNoOverflow(1);
            $transferDate = date('Y-m-d', mktime(0, 0, 0, $transferDate->month, self::TRANSFER_DAY, $transferDate->year));
        } else {
            $transferDate = date('Y-m-d', mktime(0, 0, 0, $nowDate->month, self::TRANSFER_DAY, $nowDate->year));
        }

        // 土日、祝日をスキップする。
        $transferDate = new Carbon($this->getBusinessDay($transferDate));
        
        // 請求データを取得する。
        $billingArray = TBillinge::where('billing_ym', '=', $billingYM)
                                ->orderBy('id')
                                ->get();

        // 消費税率を取得する。
        $taxRate = $this->getTaxRate($nowDate);

        $count = 0;
        foreach ($billingArray as &$billing) {
            // 消費税額を計算する。
            $billing->consumption_tax = floor($billing->billing_total_price * $taxRate);

            // 請求詳細データを取得する。 
            $billingDetail =  new TBillingDetail();
            $detailList = $billingDetail->getBillingDetails($billing->id);
            
            $pdf = PDF::loadView('operator/billing/billing',
                                compact('billing', 'detailList', 'nowDate',
                                        'transferDate', 'billingMonthStart', 'billingMonthEnd'));

            // 用紙サイズと向きの設定 (A4縦)
            $pdf->setPaper('A4', 'portrait');
            // $pdf->setPaper('A3', 'portrait');
            // PDFファイルのサイズを小さくする設定
            $pdf->setOptions([
                'enable_font_subsetting'    => true
            ]);
            
            $user = MUser::find($billing->user_id);

            // ファイルに保存する。
            $fileName = $dirName . "/" . $billingYM . "_billing_" . $user->contractor_id . ".pdf";
            $pdf->save($fileName);
        }

        return true;
    }

    /**
     * 土日、祝日をスキップした営業日を取得する
     * @param string $originalDay 元の日付
     * @return  string 土日、祝日をスキップした営業日
     */
    private function getBusinessDay($originalDay)
    {
        // 固定休業日。この場合は土日
        $week_end = array('Sat', 'Sun');

        // 年末年始
        $new_year_horiday = array('01-01', '01-02', '01-03', '12-31');
        
        // ローカルのAPIファイル名
        $localApiFileName = storage_path("pdf/date.json");

        // 祝日APIにリクエスト
        $apiUrl = 'https://holidays-jp.github.io/api/v1/date.json';
        $public_holiday_api = @file_get_contents($apiUrl);

        // 祝日APIファイルが取得できた場合
        if ($public_holiday_api !== false) {
            // ローカルに保存しておく。
            file_put_contents($localApiFileName, $public_holiday_api);
        
        // 取得できなかった場合
        } else {
            // エラーメッセージを出力
            echo 'Holiday API URL not found. (' . $apiUrl . ')' . PHP_EOL;
            // ローカルに保存したファイルから取得する。
            $public_holiday_api = file_get_contents($localApiFileName);
        }

        // 祝日APIファイルが取得できた場合、連想配列に変換
        $tmp_public_holiday = json_decode($public_holiday_api, true);

        // 年月日のみ配列に格納
        $public_holiday = array_keys($tmp_public_holiday);

        // 営業日
        $business_day = $originalDay;

        while (true) {
            $tmp = strtotime($business_day);

            // 土日祝日の場合
            if (in_array(date('D', $tmp), $week_end)
                || in_array(date('m-d', $tmp), $new_year_horiday)
                || in_array(date('Y-m-d', $tmp), $public_holiday)) {
                // スキップする。

            // 土日祝日じゃない（営業日）の場合
            } else {
                break;
            }

            // 日付を +1
            $business_day = date('Y-m-d', strtotime($business_day . ' +1 day')); 
        }

        return  $business_day;
    }

    /**
     * 消費税率を取得する。
     * @param $date 日付
     * @return double 消費税率
     */
    private function getTaxRate($date) {
        $taxData = MTax::getEffectiveTax($date);
        $taxRate = 0.0;
        if (isset($taxData)) {
            $taxRate = $taxData->tax / 100.0;
        }       
        return  $taxRate;
    }
}
