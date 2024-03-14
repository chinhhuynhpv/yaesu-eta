<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TBillinge;
use App\Models\TBillingDetail;
use Carbon\Carbon;
use App\Traits\HandleCsv;

class AdminBillingController extends Controller
{
    use HandleCsv;
    /**
     * 請求データ一覧
     * @param Request $request  HTTPリクエスト
     */
    public function listBillings(Request $request)
    {

        // 請求年月を取得する。
        if ($request->has(['billing_y', 'billing_m'])) {    // パラメータが設定されている場合
            $billing_y = $request->billing_y;
            $billing_m = $request->billing_m;

        } else {                                            // パラメータが未設定の場合
            // 現在の年月を取得する。
            $today = Carbon::today();
            $billing_y = $today->year;
            $billing_m = $today->month;
        }

        // 請求データを取得する。 
        $billing = new TBillinge();        
        $billingList = $billing->getBillings($billing_y, $billing_m);

        // ペジネーションのリンクに追加するパラメータ
        $billingList->appends([
            'billing_y' => $billing_y,
            'billing_m' => $billing_m
        ]);

        return view('operator/billing/list', compact('billing_y', 'billing_m', 'billingList'));
    }

    /**
     * 請求詳細データ一覧
     * @param int $billingId  請求データのID
     */
    public function detailBillings($billingId)
    {
        // 請求IDのパラメータチェック
        $constraints = [
            'id' => 'required|integer',
        ];
        $validator = validator()->make(['id' => $billingId], $constraints);
        if ($validator->fails()) {
            abort(404);
        }

        // 請求データを取得する。
        $billing = TBillinge::find($billingId);

        // 請求詳細データを取得する。 
        $billingDetail =  new TBillingDetail();
        $detailList = $billingDetail->getBillingDetails($billingId);

        return view('operator/billing/detail', compact('billing', 'detailList'));
    }

    /**
     * 請求データのダウンロード(F0101)
     * @param Request $request  HTTPリクエスト
     */
    public function downloadBillings(Request $request)
    {
        // 請求年月を取得する。
        $billingYear = $request->billing_y;
        $billingMonth = $request->billing_m;

        // 請求データを取得する。 
        $billing = new TBillinge();        
        $billingList = $billing->getBillingsForCsv($billingYear, $billingMonth);

        $rowDataList = array();
        foreach ($billingList as $billing) {
            // １行分のデータ配列
            $row = [
                $billing->bank_num,                     // 銀行番号
                $billing->branchi_num,                  // 支店番号
                $billing->deposit_type,                 // 預金種目
                $billing->account_num,                  // 口座番号
                $billing->account_name,                 // 口座名義
                $billing->bank_entruster_num
                    . $billing->bank_customer_num,      // BK顧客番号
                $billing->sum_billing_total_price       // 引落金額
            ];

            // データリストに追加
            array_push($rowDataList, $row);
        }

        // CSV文字列に変換する。
        $csv = $this->toCsv($rowDataList);

        // Shift JIS に変換する。
        $csv = mb_convert_encoding($csv, "SJIS-win");

        // ファイルサイズ
        $fileSize = strlen($csv);

        // ファイル名
        $fileName = 'billings_' . sprintf("%04d%02d", $billingYear, $billingMonth) . '.csv';

        $headers = [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => "attachment; filename=\"${fileName}\"",
            'Content-Length' => $fileSize
        ];

        return  response($csv)->withHeaders($headers);
    }

    public function statusUpdate(Request $request) {
        // 請求年月を取得する。
        $year = $request->billing_y;
        $month = $request->billing_m;
        $billingList = TBillinge::where('billing_ym', $year . $month)
                                    ->where('status', config('const.billing_status.new'))->orderBy('id')->get();
        foreach ($billingList as $item) {
            $billing = TBillinge::find($item->id);
            $billing->status = config('const.billing_status.comfirm');
            $billing->save();
        }

        return redirect()->route('operator.billingList')->with('success', __('success update billing info'));
    }
}
