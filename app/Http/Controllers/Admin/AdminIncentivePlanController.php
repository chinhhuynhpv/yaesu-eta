<?php

namespace App\Http\Controllers\Admin;

use App\Models\MSalesPromotionPlan;

class AdminIncentivePlanController extends AdminPlanController
{
    protected $model = MSalesPromotionPlan::class;
    protected $prefixRouteName = 'admin.incentive';
    protected $rootDir = 'admin/incentive/';
    protected $ignoredUpdatedFields = [];
}
