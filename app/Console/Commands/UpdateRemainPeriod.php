<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TUserLinePlan;
use App\Models\MPlan;


class UpdateRemainPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:subtract_remain_period {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'when the end of the month, subtract remain period';

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
        // 1日夜間に前月を対象に処理を行う。
        $targetDate = CarbonImmutable::createFromFormat('Ymd', $date)->subMonths(1);
        
        // トランザクションスタート
        DB::beginTransaction();
        try {

            // ステータスが利用中の回線プランを取得
            $t_plan = DB::table('t_user_line_plans')->where('t_user_line_plans.line_status', config('const.t_user_line_plans.line_status.in_use'))->get();
            // 対象が存在する
            foreach ($t_plan as $item) {
                // 残月数
                if ($item->month_left_num != null && $item->month_left_num != 0) {
                    $item->month_left_num = $item->month_left_num - 1;
                }
                // 期間限定残月数
                if ($item->limited_time_month_left_num != null && $item->limited_time_month_left_num != 0) {
                    $item->limited_time_month_left_num = $item->limited_time_month_left_num - 1;
                }
                // 契約自動更新
                if ($item->month_left_num == 0 && $item->automatic_update == '2') {
                    $mplan = MPlan::where('id', $item->plan_id)->first();
                    $item->month_left_num = $mplan->cancellation_limit_period;
                }
                // 契約自動更新なし　ステータスを休止中に変更
                if ($item->month_left_num == 0 && $item->automatic_update == '1') {
                    $item->line_status = config('const.t_user_line_plans.line_status.in_pause');
                }
                // getterでline_statusを見るとyes noになるためmodelを取り直して対応
                $t_line_plan = TUserLinePlan::find($item->id);
                $t_line_plan->month_left_num = $item->month_left_num;
                $t_line_plan->limited_time_month_left_num = $item->limited_time_month_left_num;
                $t_line_plan->line_status = $item->line_status;
                $t_line_plan->save();
            }

            DB::commit();

        } catch (\Exception $e) {
            // 処理途中にエラーが発生したら、全ての更新を元に戻す
            DB::rollback();
            Log::channel("batch")->info(__METHOD__ . ' ' . 'データ更新に失敗しました');
            return false;
        }

        Log::channel("batch")->info(__METHOD__ . ' ' . 'END');
        return true;
    }

}
