<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Models\TBillingDetail;
use App\Models\TBillinge;
use App\Models\WSaleDetail;
use App\Models\WSale;
use App\Models\TSaleDetail;
use App\Models\TSale;

class CreateSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_sales {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'when every month 20 day, create sales data.';

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
        $targetDate = CarbonImmutable::createFromFormat('Ymd', $date);
        // トランザクションスタート
        DB::beginTransaction();
        try {
            // ワークテーブルの初期化
            $this->initdata();
            // 対象データをワークテーブルに登録
            $this->createWsales($targetDate);
            //$this->createWsalesDetails($targetDate);
            // 当月請求を削除
            $this->deleteTsales($targetDate);
            // ワークテーブルからt_sales,t_sale_detailsへ
            $this->createTsalseAndDetails();

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
        DB::table('w_sales')->delete();
        DB::table('w_sale_details')->delete();
    }

    private function createWsales(CarbonImmutable $date): void {

        $billingYm = $this->getBillingYm($date);
        $t_billing = TBillinge::where('billing_ym', $billingYm)->where('status', config('const.billing_status.direct_debit'))->get();
        foreach($t_billing as $item) {
            $sale = WSale::create(['shop_id' => $item->shop_id,
                                    'user_id' => $item->user_id,
                                    'billing_id' =>  $item->id,
                                    'sales_ym' => $this->getSaleYm($date),
                                    'sales_total_price' => $item->billing_total_price,
                                    'incentive_total_price' =>  $item->incentive_total_price,
            ]);
            $this->createWsalesDetails($sale, $item->id);
        }
        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_sales 処理対象:' . count($t_billing) . '件');
    }

    private function createWsalesDetails(WSale $sale, int $billing_id): void {

        $t_billing_details = TBillingDetail::where('billing_id', $billing_id)->get();
        foreach($t_billing_details as $item) {
            $sale_datail = WSaleDetail::create(['sale_id' => $sale->id,
                                    'shop_id' => $sale->shop_id,
                                    'user_id' => $sale->user_id,
                                    'contract_date' => $item->contract_date,
                                    'plan_type' => $item->plan_type,
                                    'plan_id' => $item->plan_id,
                                    'plan_num' =>  $item->plan_num,
                                    'plan_name' => $item->plan_name,
                                    'unit_price' => $item->unit_price,
                                    'incentive_unit_price' =>  $item->incentive_unit_price,
                                    'amount' => $item->amount,
                                    'total_price' => $item->total_price,
                                    'incentive_total_price' =>  $item->incentive_total_price,
            ]);
        }

        Log::channel("batch")->info(__METHOD__ . ' ' . 'w_sale_details 処理対象:' . count($t_billing_details) . '件');
    }

    private function createTsalseAndDetails() {
        $wsale = WSale::orderBy('shop_id')
                            ->orderby('user_id')
                            ->get();

        foreach($wsale as $item) {
            $result = TSale::create(['user_id' => $item->user_id,
                                        'shop_id' => $item->shop_id,
                                        'billing_id' =>  $item->billing_id,
                                        'sales_ym' => $item->sales_ym,
                                        'sales_total_price' => $item->sales_total_price,
                                        'incentive_total_price' => $item->incentive_total_price,
                                        'status' => $item->status
                                    ]);
            $this->createTsaleDetails($result);
        }
    }

    /**
     * 売上明細ワークテーブルから売上明細テーブルを作成する。
     *
     * @param TSale $result
     * @return void
     */
    private function createTsaleDetails(TSale $result): void {

        $details = WSaleDetail::where('shop_id', $result->shop_id)
                                    ->where('user_id', $result->user_id)
                                    ->get();
        foreach($details as $item) {
            TSaleDetail::create(['sale_id' => $result->id,
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
     * 当月売上を削除する（リラン時の処理）
     *
     * @param CarbonImmutable $date
     * @return void
     */
    private function deleteTsales(CarbonImmutable $date): void {
        $saleYm = $this->getSaleYm($date);
        $saleList = TSale::where('sales_ym', $saleYm)->get();
        foreach ($saleList as $item) {
            TSaleDetail::where('sale_id', $item->id)->delete();
            TSale::where('id', $item->id)->delete();
        }
    }
    /**
     * 請求年月を取得する。
     *
     * @param CarbonImmutable $date
     * @return string
     */
    private function getBillingYm(CarbonImmutable $date):string {
        $newdate = $date->subMonths(1);
        $date_Ym = $newdate->format('Ym');
        return $date_Ym;
    }
    /**
     * 売上年月を取得する。
     *
     * @param CarbonImmutable $date
     * @return string
     */
    private function getSaleYm(CarbonImmutable $date):string {
        $date_Ym = $date->format('Ym');
        return $date_Ym;
    }
}
