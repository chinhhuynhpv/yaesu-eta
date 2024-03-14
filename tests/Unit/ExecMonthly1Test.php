<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use Carbon\Carbon;
class ExecMonthly1Test extends TestCase
{
    /**
     * 月次バッチ（引数なし）
     * @test
     * @return void
     */
    public function monthlyTest()
    {
        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-01 00:00:00'));
        // 請求作成
        $result = \Artisan::call('command:create_billings');
        // 処理が正常終了
        $this->assertEquals(1, $result);
        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-01 00:00:00'));
        // 残月数更新
        $result = \Artisan::call('command:subtract_remain_period');
        // 処理が正常終了
        $this->assertEquals(1, $result);
        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-01 01:30:00'));
        // ご利用明細書
        $result = \Artisan::call('command:pdf_billings');
        // 処理が正常終了
        $this->assertEquals(1, $result);
        //　テストの日付を指定
        Carbon::setTestNow(Carbon::parse('2022-01-01 02:00:00'));
        // 回線利用レポート
        $result = \Artisan::call('command:pdf_shop_lines');
        // 処理が正常終了
        $this->assertEquals(1, $result);
    }
}
