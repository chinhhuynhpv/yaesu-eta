<?php

namespace App\Http\Controllers\Admin;

use App\Models\MOptionPlan;

class AdminOptionPlanController extends AdminPlanController
{
    protected $model = MOptionPlan::class;
    protected $prefixRouteName = 'admin.option';
    protected $rootDir = 'admin/option/';
    protected $ignoredUpdatedFields = [];
}
