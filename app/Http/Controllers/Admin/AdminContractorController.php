<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Prefectures;
use App\Http\Controllers\ContractorManagementController;
use App\Models\MShop;
use App\Models\MUser;
use Illuminate\Http\Request;

class AdminContractorController extends ContractorManagementController
{
    protected $guard = 'admin';
    protected $rootDir = 'operator/contractor/';
    protected $ignoredUpdatedFields = ['email', 'password', 'shop_id'];

    public function list()
    {
        $req = request();
        $query = MUser::orderBy('id', 'desc');
        $appendParams = [];

        if ($shopId = $req->query('shop_id')) {
            $query = $query->where('shop_id', $shopId);
            $appendParams['shop_id'] = $shopId;
        }

        if (($contractorId = $req->query('contractor_id')) !== null) {
            $query = $query->where('contractor_id', 'LIKE', "%$contractorId%");
            $appendParams['contractor_id'] = $contractorId;
        }

        if (($contractName = $req->query('contract_name')) !== null) {
            $query = $query->where('contract_name', 'LIKE', "%$contractName%");
            $appendParams['contract_name'] = $contractName;
        }

        if ($registrationDate = $req->query('registration_date')) {
            $query = $query->whereDate('created_at', $registrationDate);
            $appendParams['registration_date'] = $registrationDate;
        }

        if (($representativeName = $req->query('representative_name')) !== null) {
            $query = $query->where('representative_name', 'LIKE', "%$representativeName%");
            $appendParams['representative_name'] = $representativeName;
        }

        $users = $query->with('shop')->paginate();
        $users->appends($appendParams);

        $shops = MShop::orderBy('id', 'desc')->get();

        return view($this->rootDir . 'list', compact('users', 'shops'));
    }

    public function handleConfirm(Request $req)
    {
        $user = MUser::find($req->id);
        $this->_checkHtmlStatus($user);

        $columnConstraints = $this->getRule();
        $this->_updatedColumnConstraints($columnConstraints);
        $req->validate($columnConstraints);

        $data = $req->all();
        $data['status'] = 2;

        $user->setPrefectures($data);

        $res = $user->smartSave($data, ['email', 'password', 'shop_id']);

        if ($res === true)  return  redirect()->route('admin.userComplete')->with(['status' => 'completed', 'message' => __('Update user successfully')]);

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    protected function _checkHtmlStatus($user)
    {
        if (!$user) abort(404);
    }

    protected function getRule()
    {
        return MUser::columnConstraints(true);
    }

    /**
     * アップロードされたドキュメントをzip形式でダウンロードする
     *
     * @param integer $id
     * @return void
     */
    public function documentDownload(int $id)
    {
        $user = MUser::find($id);
        // zipファイルの保存場所
        $save_path = storage_path("app/upload/" . $user->contractor_id . "/" . $user->contractor_id . ".zip");
        $zip = new \ZipArchive();
        $zip->open($save_path, \ZipArchive::CREATE);
        // 契約者のフォルダのファイル一覧を取得
        $rootPath = storage_path("app/upload/" . $user->contractor_id . "/");
        $files = \File::files($rootPath);
        foreach ($files as $v) {
            // ファイルを追加
            $zip->addFile($v->getpathName(), mb_convert_encoding($v->getfileName(), 'CP932', 'UTF-8'));
        }
        $zip->close();
        // ダウンロード後にzipは削除
        return response()->download($save_path)->deleteFileAfterSend();
    }
}
