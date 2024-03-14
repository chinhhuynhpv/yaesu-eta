<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 売上詳細データモデル
 */
class TSaleDetail extends Model
{
    use SoftDeletes;    // 論理削除
    use HasFactory;     // ファクトリー
    /**
     * どのテーブルと関連付けられるか？
     */
    protected $table = 't_sale_details';

    /**
     * 主キーのカラム名
     */
    protected $primaryKey = 'id';

    /**
     * 日付型のカラム
     */
    protected $dates = ['contract_date'];


    protected $guarded = ['id'];

    // ... not use __get()
    // __get() は、クラスのプロパティの元の値を隠してしまうので使用しない。

    /**
     * 売上詳細データを取得する。
     * @param int $saleId 売上データのID
     * @return array 請求詳細データ(stdClass)の配列
     */
    public function getSaleDetails($saleId)
    {
        $detailList = self::where('sale_id', '=', $saleId)
                        ->orderBy('id')
                        ->get();

        return  $detailList;
    }
 }
