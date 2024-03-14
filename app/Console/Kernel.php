<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CreateBillings;
use App\Console\Commands\UpdateRemainPeriod;
use App\Console\Commands\BillingPdfBatch;
use App\Console\Commands\ShopLinePdfBatch;
use App\Console\Commands\SendMailAuto;
use App\Console\Commands\CreateSales;
use App\Console\Commands\ShopIncentivePdfBatch;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\CreateBillings',
        'App\Console\Commands\UpdateRemainPeriod',
        'App\Console\Commands\BillingPdfBatch',
        'App\Console\Commands\ShopLinePdfBatch',
        'App\Console\Commands\SendMailAuto',
        'App\Console\Commands\CreateSales',
        'App\Console\Commands\ShopIncentivePdfBatch',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // BA0101 請求データ作成
        $schedule->command(CreateBillings::class)->monthlyOn(1, '00:00')->timezone('Asia/Tokyo');
        // BA0102 残月数を更新する
        $schedule->command(UpdateRemainPeriod::class)->monthlyOn(1, '01:00')->timezone('Asia/Tokyo');
        // BA0103 ご利用明細PDF作成
        $schedule->command(BillingPdfBatch::class)->monthlyOn(1, '01:30')->timezone('Asia/Tokyo');
        // BA0104 回線利用レポート
        $schedule->command(ShopLinePdfBatch::class)->monthlyOn(1, '02:00')->timezone('Asia/Tokyo');
        // BA0105 利用明細メール送信
        $schedule->command(SendMailAuto::class, ['--send-mail-to-contractor'])->monthlyOn(4, '09:00')->timezone('Asia/Tokyo');
        $schedule->command(SendMailAuto::class, ['--send-mail-to-shop'])->monthlyOn(4, '09:00')->timezone('Asia/Tokyo');
        // BA0106 売上データ作成
        $schedule->command(CreateSales::class)->monthlyOn(21, '00:00')->timezone('Asia/Tokyo');
        // BA0107 インセンティブ計算書PDF作成
        $schedule->command(ShopIncentivePdfBatch::class)->monthlyOn(21, '01:00')->timezone('Asia/Tokyo');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
