<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\MPlan;
use App\Http\Controllers\ResourceManagementController;

class AdminPlanController extends ResourceManagementController
{
    protected $model = MPlan::class;
    protected $prefixRouteName = 'admin.plan';
    protected $rootDir = 'admin/plan/';
    protected $ignoredUpdatedFields = [];

    public function handleConfirm(Request $req)
    {
        if (!$req->id) {
            $req->request->add(['plan_num' => $this->model::generatePlanNum()]);
        }

        $this->addZeroToInput($req);

        return parent::handleConfirm($req); // TODO: Change the autogenerated stub
    }

    public function handleEdit(Request $req)
    {
        $this->addZeroToInput($req);
        return parent::handleEdit($req); // TODO: Change the autogenerated stub
    }

    private function addZeroToInput($req) {
        if (!$req->usage_details_description) {
            $req->request->add(['usage_details_description' => 0]);
        }

        if (!$req->incentive_description) {
            $req->request->add(['incentive_description' => 0]);
        }
    }
}