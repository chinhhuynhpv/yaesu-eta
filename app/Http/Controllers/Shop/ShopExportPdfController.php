<?php

namespace App\Http\Controllers\Shop;

use \Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\MUser;
use App\Models\MUserRequest;
use Auth;
use PDF;

class ShopExportPdfController extends Controller
{
    public function exportSettingDocument($id) {
        $userRequest = MUserRequest::find($id);
        $this->checkValidItem($userRequest);

        $issuedDate = Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('shop/export/setting-document', compact('userRequest', 'issuedDate'));
        // PDFファイルのサイズを小さくする設定
        $pdf->setOptions([
            'enable_font_subsetting'    => true
        ]);
        return $pdf->download('setting-document.pdf');
    }

    public function exportRegisterDocument($id) {
        $userRequest = MUserRequest::find($id);
        $this->checkValidItem($userRequest);
        $contract_name = MUser::find($userRequest->user_id);
       
        $dt = Carbon::now();
        $issuedDate = $dt->format('d-m-Y');
        $currentMonth = $dt->format('d-m-Y');
        $nextMonth = $dt->endOfMonth()->addDay()->format('d-m-Y');
        $fees = $userRequest->calculateFee();
       
        $pdf = PDF::loadView('shop/export/register-document', compact('userRequest', 'issuedDate', 'fees', 'currentMonth', 'nextMonth', 'contract_name'));
        // PDFファイルのサイズを小さくする設定
        $pdf->setOptions([
            'enable_font_subsetting'    => true
        ]);

        return $pdf->download('register-document.pdf');
    }

    private function checkValidItem($item) {
        if (!$item) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->shop_id != $item->shop_id) abort(403);
    }
}
