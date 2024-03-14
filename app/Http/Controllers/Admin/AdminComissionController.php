<?php

namespace App\Http\Controllers\Admin;

use App\Models\MCommission;

class AdminComissionController extends AdminPlanController
{
    protected $model = MCommission::class;
    protected $prefixRouteName = 'admin.commission';
    protected $rootDir = 'admin/commission/';
    protected $ignoredUpdatedFields = [];
}
