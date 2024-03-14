<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MAdminUser;
use App\Models\MCommission;
use App\Models\MOptionPlan;
use App\Models\MShopUser;
use App\Models\MUser;
use App\Models\MPlan;
use App\Models\MSalesPromotionPlan;
use App\Models\MUserLineRequest;
use App\Models\MUserLine;
use App\Models\MUserTalkGroup;
use App\Models\MUserLineTalkGroup;
use App\Models\MUserLineTalkGroupRequest;
use App\Models\MUserLineTalkGroupAdditionalRequests;
use App\Models\MUserRequest;
use App\Models\MUserTalkGroupRequest;
use App\Models\TLinePauses;
use App\Models\TUserCommission;
use App\Models\TUserLinePlanRequest;
use App\Models\TUserLinePlan;
use App\Models\MSim;
use App\Models\TBillingDetail;
use App\Models\TBillinge;
use App\Models\TSale;
use App\Models\TSaleDetail;

class DataFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $shopUser = MShopUser::factory()->create([
            'shop_code' => 'shop123',
        ]);

        $sim = MSim::factory()->count(10)->create();

        $plans = MPlan::factory()->count(10)->create();

        MUser::factory()->create([
            'email' => 'user@mail.com',
            'shop_id' => $shopUser->shop_id
        ]);

        MUser::factory()
            ->count(15)
            ->create([
                'shop_id' => $shopUser->shop_id
            ]);

        $users = MUser::factory()
            ->count(5)
            ->create([
                'shop_id' => $shopUser->shop_id
            ]);

        $plan = MPlan::factory()->create();
        $mComission = MCommission::factory()->create();

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $request = MUserRequest::factory()->create([
                        'user_id' => $user->id,
                        'shop_id' => $shopUser->id,
                        'request_number' => MUserRequest::generateRequestNumber()
                    ]);

                $lineRequest = MUserLineRequest::factory()->count(3)->create([
                    'request_id' => $request->id,
                    'shop_id' => $shopUser->id,
                    'user_id' => $user->id,
                ]);

                foreach ($lineRequest as $line) {
                    $groupRequest = MUserTalkGroupRequest::factory()->create([
                        'request_id' => $request->id,
                        'user_id' => $user->id,
                        'shop_id' => $shopUser->id
                    ]);

                    $mUserTalkGroup = MUserTalkGroup::factory()->create([
                        'user_id' => $user->id,
                        'shop_id' => $shopUser->id,
                        'request_id' => $request->id,
                        'request_group_id' => $groupRequest->id
                    ]);

                    $mUserLine = MUserLine::factory()->create([
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'request_id' => $request->id,
                        'request_line_id' => $line->id,
                    ]);

                    MUserLineTalkGroup::factory()->create([
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'line_id' => $mUserLine->id,
                        'group_id' => $mUserTalkGroup->id,
                        'request_id' => $request->id,
                        'number' => 1
                    ]);

                    $mUserLineMUserLineTalkGroupRequest = MUserLineTalkGroupRequest::factory()->create([
                        'request_id' => $request->id,
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'group_id' => $groupRequest->id,
                        'group_name' => $groupRequest->name,
                        'line_id' => $line->id,
                        'line_num' => $line->line_num
                    ]);

                    $addGroupRequest = MUserTalkGroupRequest::factory()->count(2)->create([
                        'request_id' => $request->id,
                        'user_id' => $user->id,
                        'shop_id' => $shopUser->id
                    ]);

                    foreach ($addGroupRequest as $key => $addTalkGroup) {
                        MUserLineTalkGroupAdditionalRequests::factory()->create([
                            'line_group_req_id' => $mUserLineMUserLineTalkGroupRequest->id,
                            'shop_id' => $shopUser->id,
                            'user_id' => $user->id,
                            'group_id' => $addTalkGroup->id,
                            'group_name' => $addTalkGroup->name,
                        ]);

                        $mUserTalkGroup = MUserTalkGroup::factory()->create([
                            'user_id' => $user->id,
                            'shop_id' => $shopUser->id,
                            'request_id' => $request->id,
                            'request_group_id' => $addTalkGroup->id
                        ]);

                        MUserLineTalkGroup::factory()->create([
                            'shop_id' => $shopUser->id,
                            'user_id' => $user->id,
                            'line_id' => $mUserLine->id,
                            'group_id' => $mUserTalkGroup->id,
                            'request_id' => $request->id,
                            'number' => $key + 2
                        ]);
                    }

                    TUserLinePlanRequest::factory()->create([
                        'request_id' => $request->id,
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'line_id' => $line->id,
                        'plan_id' => $plan->id
                    ]);

                    $tUserLinePlan = TUserLinePlan::factory()->create([
                        'line_id' => $line->id,
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'plan_set_start_date' => $line->start_date
                    ]);

                    TLinePauses::factory()->create([
                        'line_id' => $line->id,
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'line_plans_id' =>  $tUserLinePlan->id,
                    ]);

                    TUserCommission::factory()->create([
                        'shop_id' => $shopUser->id,
                        'user_id' => $user->id,
                        'line_id' => $mUserLine->id,
                        'user_request_id' => $request->id,
                        'commission_id' => $mComission->id,
                        'commission_date' => $mComission->effective_date,
                        'commission_unit_price' => $mComission->usage_unit_price,
                        'incentive_unit_price' => $mComission->incentive_unit_price,
                    ]);
                }
            }
        }

        MAdminUser::factory()
            ->setRole(1)
            ->create([
                'email' => 'admin@mail.com',
            ]);

        MAdminUser::factory()
            ->setRole(0)
            ->create([
                'email' => 'operator@mail.com',
            ]);

        MOptionPlan::factory()->count(20)->create();

        MPlan::factory()->count(20)->create();
        MSalesPromotionPlan::factory()->count(20)->create();
        MCommission::factory()->count(20)->create();
        
        $billing = TBillinge::factory()->count(301)->create([
            'user_id' => '1',
            'shop_id' => '1',
            'billing_ym' => '202112'
        ]);
        foreach($billing as $item) {
            TBillingDetail::factory()->count(5)->create([
                'billing_id' => $item->id,
            ]);
            TSale::factory()->count(1)->create([
                'user_id' => '1',
                'shop_id' => '1',
                'billing_id'  => $item->id,
                'sales_ym' => '202112'
            ]);
        }
        $sale = TSale::get();
        foreach($sale as $item) {
            TSaleDetail::factory()->count(5)->create([
                'sale_id' => $item->id,
            ]);
        }

    }
}
