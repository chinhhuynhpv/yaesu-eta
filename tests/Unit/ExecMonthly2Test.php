<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TBillinge;
use Carbon\Carbon;

class ExecMonthly2Test extends TestCase
{

    /**
     * 月次バッチ（引数なし）
     * @test
     * @return void
     */
    public function monthlyTest()
    {
        $tbillinglist = TBillinge::all();
        foreach ($tbillinglist as $item) {
            $blling = TBillinge::find($item->id);
            $blling->status = '3'; //引落済み
            $blling->save();
        }
        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-21 00:00:00'));
        // コマンド実行
        $result = \Artisan::call('command:create_sales');

        $this->assertEquals(1, $result);

        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-21 00:00:00'));
        // コマンド実行
        $result = \Artisan::call('command:pdf_shop_incentives');

        $this->assertEquals(1, $result);
        // 元に戻す
        foreach ($tbillinglist as $item) {
            $blling = TBillinge::find($item->id);
            $blling->status = '1'; //新規
            $blling->save();
        }
    }
}
