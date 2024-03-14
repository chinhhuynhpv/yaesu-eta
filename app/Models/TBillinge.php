<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 請求データモデル
 */
class TBillinge extends Model
{
    use SoftDeletes;    // 論理削除
    use HasFactory;     // ファクトリー
  
    /**
     * どのテーブルと関連付けられるか？
     */
    protected $table = 't_billinges';

    /**
     * 主キーのカラム名
     */
    protected $primaryKey = 'id';

    /**
     * 更新可能なカラムの設定（INSERT or UPDATE時）
     * fill(), create(), update() のメソッドを呼び出したときだけ、この設定は有効
     */
    protected $fillable = ['user_id', 'shop_id', 'billing_ym', 'billing_total_price', 'incentive_total_price', 'status'];
    
    /**
     * 関連付けられた契約者データを取得する。
     * （このメソッドは、請求データの編集の時だけ使用する。）
     */
    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }
    
    /**
     * 関連付けられた販売店データを取得する。
     * （このメソッドは、請求データの編集の時だけ使用する。）
     */
    public function shop()
    {
        return $this->belongsTo(MShop::class, 'shop_id');
    }

    /**
     * ステータス(status) の表示文字を取得する。
     * @return  表示文字
     */
    public function getStatusColumn() {
        switch ($this->status) {
            case    '1':        // 新規
                return  __("Billing Status New");
            case    '2':        // 確認済
                return  __("Billing Status Confirm");
            case    '3':        // 引落済み
                return  __("Billing Status Direct Debit");
            case    '4':        // 再引落し
                return  __("Billing Status Direct Debit Returned");
            default:            // 未定義
                return  __("undefined");
        }
    }

    // ... not use __get()
    // __get() は、クラスのプロパティの元の値を隠してしまうので使用しない。

    /**
     * 請求データを取得する。（一覧表示用）
     * @param int $billingYear 請求年
     * @param int $billingMonth 請求月
     * @return LengthAwarePaginator 請求データ(stdClass)の配列
     */
    public function getBillings($billingYear, $billingMonth)
    {
        $billingList = self::select(
                            't_billinges.id',
                            't_billinges.user_id',
                            'm_users.contract_name',
                            't_billinges.shop_id',
                            self::raw('m_shops.name       AS shop_name'),
                            't_billinges.billing_ym',
                            't_billinges.billing_total_price',
                            't_billinges.incentive_total_price',
                            't_billinges.status'
                        )
                        ->join('m_users', function($join) {
                            $join->on('m_users.id', '=', 't_billinges.user_id')
                                    ->whereNull('m_users.deleted_at'); 
                        })
                        ->join('m_shops', function($join) {
                            $join->on('m_shops.id', '=', 't_billinges.shop_id')
                                    ->whereNull('m_shops.deleted_at'); 
                        })
                        ->where('t_billinges.billing_ym', '=', sprintf("%04d%02d", $billingYear, $billingMonth))
                        ->orderBy('t_billinges.id')
                        ->paginate(100);

        return  $billingList;
    }
  
    /**
     * 請求データを取得する。（CSV出力用）
     * @param int $billingYear 請求年
     * @param int $billingMonth 請求月
     * @return array 請求データ(stdClass)の配列
     */
    public function getBillingsForCsv($billingYear, $billingMonth)
    {
        $billingList = self::select(
                            'm_users.bank_num',
                            'm_users.branchi_num',
                            'm_users.deposit_type',
                            'm_users.account_num',
                            'm_users.account_name',
                            'm_users.bank_entruster_num',
                            'm_users.bank_customer_num',
                            self::raw('SUM(t_billinges.billing_total_price) AS sum_billing_total_price'),
                            self::raw('SUM(t_billinges.incentive_total_price) AS sum_incentive_total_price')
                        )
                        ->join('m_users', function($join) {
                            $join->on('m_users.id', '=', 't_billinges.user_id')
                                    // 契約者情報が未削除(deleted_at IS NULL) の判定も必要
                                    ->whereNull('m_users.deleted_at'); 
                        })
                        ->where('t_billinges.billing_ym', '=', sprintf("%04d%02d", $billingYear, $billingMonth))
                        ->groupBy(
                            'm_users.id',
                            'm_users.bank_num',
                            'm_users.branchi_num',
                            'm_users.deposit_type',
                            'm_users.account_num',
                            'm_users.account_name',
                            'm_users.bank_entruster_num',
                            'm_users.bank_customer_num'
                        )
                        ->orderBy('m_users.id')
                        ->get();

        return  $billingList;
    }
}
