<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TSale;
use App\Models\TSaleDetail;
use Carbon\Carbon;
use App\Traits\HandleCsv;


class AdminSaleController extends Controller
{
  use HandleCsv;
    /**
     * 売上データCSV出力の売上年月、インセンティブのみフラグの入力画面
     * @param Request $request  HTTPリクエスト
     */
    public function listSales(Request $request)
    {
        // 売上年月を取得する。
        if ($request->has(['sales_y', 'sales_m'])) {    // パラメータが設定されている場合
            $sales_y = $request->sales_y;
            $sales_m = $request->sales_m;

        } else {                                            // パラメータが未設定の場合
            // 現在の年月を取得する。
            $today = Carbon::today();
            $sales_y = $today->year;
            $sales_m = $today->month;
        }

        // 売上データを取得する。 
        $sale = new TSale();        
        $salesList = $sale->getSales($sales_y, $sales_m);

        // ペジネーションのリンクに追加するパラメータ
        $salesList->appends([
            'sales_y' => $sales_y,
            'sales_m' => $sales_m
        ]);

        return view('operator/sales/list', compact('sales_y', 'sales_m', 'salesList'));
    }

    /**
     * 売上詳細データ一覧
     * @param int $saleId  売上データのID
     */
    public function detailSales($saleId)
    {
        // 売上IDのパラメータチェック
        $constraints = [
            'id' => 'required|integer',
        ];
        $validator = validator()->make(['id' => $saleId], $constraints);
        if ($validator->fails()) {
            abort(404);
        }

        // 売上データを取得する。
        $sale = TSale::find($saleId);

        // 売上詳細データを取得する。 
        $saleDetail =  new TSaleDetail();
        $detailList = $saleDetail->getSaleDetails($saleId);

        return view('operator/sales/detail', compact('sale', 'detailList'));
    }

    /**
     * 売上データのダウンロード(F0103, F0104)
     * @param Request $request  HTTPリクエスト
     * @param int $incentiveOnly インセンティブのみ
     */
    public function downloadSales(Request $request)
    {

        // 売上年月、インセンティブのみフラグを取得する。
        $salesYear = $request->sales_y;
        $salesMonth = $request->sales_m;
        $incentiveOnly = $request->incentive_only;
        
        // 売上データを取得する。
        $sales = new TSale();
        $salesList = $sales->getSalesForCsv($salesYear, $salesMonth);

        $rowDataList = array();
        foreach ($salesList as $sales) {
            // 対象月を yyyy/MM 形式に変換する。
            $salesYM = $sales->sales_ym;
            if (strlen($salesYM) == 6) {
                $workYM = substr($salesYM, 0, 4) . '/' . substr($salesYM, 4);
                $salesYM = $workYM;
            }

            // インセンティブのみでない場合
            if ($incentiveOnly !== '1') {
                if ($sales->sum_sales_total_price > 0) {
                    // １行分のデータ配列
                    $row = [
                        $sales->code,                       // 販売店NO
                        $sales->sap_supplier_num,           // SAP仕入先番号
                        $sales->name,                       // 販売店名
                        // '',                                 // 締め日
                        // '',                                 // 支払日
                        $salesYM,                           // 対象月
                        $sales->sum_sales_total_price       // 金額
                    ];
    
                    // データリストに追加
                    array_push($rowDataList, $row);
                }
            }

            if ($sales->sum_incentive_total_price > 0) {
                // １行分のデータ配列
                $row = [
                    $sales->code,                       // 販売店NO
                    $sales->sap_supplier_num,           // SAP仕入先番号
                    $sales->name,                       // 販売店名
                    // '',                                 // 締め日
                    // '',                                 // 支払日
                    $salesYM,                           // 対象月
                    $sales->sum_incentive_total_price   // インセンティブ合計金額
                ];

                // データリストに追加
                array_push($rowDataList, $row);
            }
        }
        
        // CSV文字列に変換する。
        $csv = $this->toCsv($rowDataList);

        // Shift JIS に変換する。
        // $csv = mb_convert_encoding($csv, "SJIS-win");

        // ファイルサイズ
        $fileSize = strlen($csv);

        // ファイル名
        if ($incentiveOnly === '1') {       // インセンティブのみの場合
            $fileHead = 'sales_incentive';
        } else {                            // そうでない場合
            $fileHead = 'sales_all';
        }
        $fileName = $fileHead . '_' . sprintf("%04d%02d", $salesYear, $salesMonth) . '.csv';

        $headers = [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => "attachment; filename=\"${fileName}\"",
            'Content-Length' => $fileSize
        ];

        return  response($csv)->withHeaders($headers);
    }
}
