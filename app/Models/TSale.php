<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 売上データモデル
 */
class TSale extends Model
{
    /**
     * 論理削除の機能を追加する。
     */
    use SoftDeletes;
    use HasFactory;     // ファクトリー
     
    /**
     * どのテーブルと関連付けられるか？
     */
    protected $table = 't_sales';

    /**
     * 主キーのカラム名
     */
    protected $primaryKey = 'id';

    /**
     * 更新可能なカラムの設定（INSERT or UPDATE時）
     * fill(), create(), update() のメソッドを呼び出したときだけ、この設定は有効
     */
    protected $fillable = ['user_id', 'shop_id', 'billing_id', 'sales_ym', 'sales_total_price', 'incentive_total_price'];

    /**
     * 関連付けられた契約者データを取得する。
     */
    public function user()
    {
        return $this->belongsTo(MUser::class, 'user_id');
    }
    
    /**
     * 関連付けられた販売店データを取得する。
     */
    public function shop()
    {
        return $this->belongsTo(MShop::class, 'shop_id');
    }
    
    // ... not use __get()
    // __get() は、クラスのプロパティの元の値を隠してしまうので使用しない。

    /**
     * 売上データを取得する。（一覧表示用）
     * @param int $salesYear 売上年
     * @param int $salesMonth 売上月
     * @return LengthAwarePaginator 売上データ(stdClass)の配列
     */
    public function getSales($salesYear, $salesMonth)
    {
        $billingList = self::select(
                            't_sales.id',
                            't_sales.user_id',
                            'm_users.contract_name',
                            't_sales.shop_id',
                            self::raw('m_shops.name       AS shop_name'),
                            't_sales.sales_ym',
                            't_sales.sales_total_price',
                            't_sales.incentive_total_price'
                        )
                        ->join('m_users', function($join) {
                            $join->on('m_users.id', '=', 't_sales.user_id')
                                    ->whereNull('m_users.deleted_at'); 
                        })
                        ->join('m_shops', function($join) {
                            $join->on('m_shops.id', '=', 't_sales.shop_id')
                                    ->whereNull('m_shops.deleted_at'); 
                        })
                        ->where('t_sales.sales_ym', '=', sprintf("%04d%02d", $salesYear, $salesMonth))
                        ->orderBy('t_sales.id')
                        ->paginate(100);

        return  $billingList;
    }

    /**
     * 売上データを取得する。（CSV出力用）
     * @param int $salesYear 売上年
     * @param int $salesMonth 売上月
     * @return array 売上データ(stdClass)の配列
     */
    public function getSalesForCsv($salesYear, $salesMonth)
    {
        $salesList = self::select(
                            'm_shops.code',
                            'm_shops.name',
                            'm_shops.sap_supplier_num',
                            't_sales.sales_ym',
                            self::raw('SUM(t_sales.sales_total_price)     AS sum_sales_total_price'),
                            self::raw('SUM(t_sales.incentive_total_price) AS sum_incentive_total_price')
                        )
                        ->join('m_shops', function($join) {
                            $join->on('m_shops.id', '=', 't_sales.shop_id')
                                    // 販売店情報が未削除(deleted_at IS NULL) の判定も必要
                                    ->whereNull('m_shops.deleted_at'); 
                        })
                        ->where('t_sales.sales_ym', '=', sprintf("%04d%02d", $salesYear, $salesMonth))
                        ->groupBy(
                            'm_shops.id',
                            'm_shops.code',
                            'm_shops.name',
                            't_sales.sales_ym'
                        )
                        ->orderBy('m_shops.id')
                        ->get();

        return  $salesList;
    }
}
