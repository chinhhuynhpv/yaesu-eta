<?php

use App\Helper\RouteGenerator;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminContractorController;
use App\Http\Controllers\Admin\AdminComissionController;
use App\Http\Controllers\Admin\AdminIncentivePlanController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminOptionPlanController;
use App\Http\Controllers\Admin\AdminTopController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminBillingController;
use App\Http\Controllers\Admin\AdminSaleController;
use App\Http\Controllers\Admin\AdminSimController;
use App\Http\Controllers\Operator\OperatorLineListController;
use App\Http\Controllers\Operator\OperatorTalkGroupController;
use App\Http\Controllers\Operator\OperatorLineTalkGroupController;
use App\Http\Controllers\Operator\OperatorLineListExportController;
use App\Http\Controllers\Shop\ShopAuthController;
use App\Http\Controllers\Shop\MUserTalkGroupRequestController;
use App\Http\Controllers\Shop\ShopApplicationController;
use App\Http\Controllers\Shop\ShopContractorController;
use App\Http\Controllers\Shop\ShopTopController;
use App\Http\Controllers\Shop\ShopDocumentController;
use App\Http\Controllers\Shop\ShopExportPdfController;
use App\Http\Controllers\Shop\ShopLineListController;
use App\Http\Controllers\Shop\ShopLineTalkGroupRequestController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserTopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::domain(env('ADMIN_SUB_DOMAIN') . '.' . env('APP_URL'))->group(function () {
    Route::redirect('/', '/top');
    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'handleLogin'])->name('admin.handleLogin');
    //Top
    Route::get('/top', [AdminTopController::class, 'index'])->name('admin.top')->middleware('auth:admin');
    //Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::group(['middleware' => ['auth:admin', 'role.operator']], function () {
        Route::group(['prefix' => 'user'], function () {
            //Contractor Management
            Route::get('/list', [AdminContractorController::class, 'list'])->name('admin.userList');
            Route::get('/detail/{id}', [AdminContractorController::class, 'detail'])->name('admin.userDetail');
            Route::get('/input/{id}', [AdminContractorController::class, 'edit'])->name('admin.userEdit');
            Route::put('/input', [AdminContractorController::class, 'handleEdit'])->name('admin.handleUserEdit')->middleware('XssSanitizer');
            Route::delete('/input', [AdminContractorController::class, 'handleDelete'])->name('admin.handleUserDelete');
            Route::get('/confirm', [AdminContractorController::class, 'confirm'])->name('admin.userConfirm');
            Route::post('/confirm', [AdminContractorController::class, 'handleConfirm'])->name('admin.userHandleConfirm');
            Route::delete('/confirm', [AdminContractorController::class, 'handleConfirmDelete'])->name('admin.handleUserConfirmDelete');
            Route::get('/complete', [AdminContractorController::class, 'complete'])->name('admin.userComplete');
            Route::get('/documentDownload/{id}', [AdminContractorController::class, 'documentDownload'])->name('admin.documentDownload');
            

            // Application
            Route::get('/request_list', [AdminApplicationController::class, 'list'])->name('admin.applicationList');
            Route::get('/request_detail/{id}', [AdminApplicationController::class, 'detail'])->name('admin.applicationDetail');
            Route::put('/request_detail', [AdminApplicationController::class, 'handleEdit'])->name('admin.applicationHandleEdit');

            //line list
            Route::get('/line_list', [OperatorLineListController::class, 'list'])->name('admin.lineList');
            Route::get('/talk_group/input', [OperatorTalkGroupController::class, 'edit'])->name('operator.talkGroupUpdate');
            Route::post('/talk_group/store', [OperatorTalkGroupController::class, 'store'])->name('operator.talkGroupStore');
            Route::get('/line_list/input', [OperatorLineListController::class, 'edit'])->name('operator.lineUpdate');
            Route::post('/line_list/store', [OperatorLineListController::class, 'store'])->name('operator.lineStore');
            Route::get('/line_talk_group_input', [OperatorLineTalkGroupController::class, 'edit'])->name('operator.lineTalkGroupUpdate');
            Route::post('/line_talk_group_store', [OperatorLineTalkGroupController::class, 'store'])->name('operator.lineTalkGroupStore');
            Route::post('/line_information', [OperatorLineListController::class, 'showInformationLineId'])->name('operator.line.id');
            Route::get('/line_csv_output', [OperatorLineListExportController::class, 'exportCSVLineList'])->name('operator.exportCSVLineList');
        });

        //Sim Management
        Route::group(['prefix' => 'sim'], function () {
            RouteGenerator::createGroup(AdminSimController::class, 'admin.sim');
            Route::post('/import', [AdminSimController::class, 'import'])->name('admin.simImport');
        });

        // 請求データCSV出力
        Route::get('/billing/list', [AdminBillingController::class, 'listBillings'])->name('operator.billingList');
        Route::get('/billing/status_update', [AdminBillingController::class, 'statusUpdate'])->name('operator.statusUpdate');
        Route::get('/billing/csv_output', [AdminBillingController::class, 'downloadBillings'])->name('operator.billingCsvOutput');
        Route::get('/billing/detail/{id}', [AdminBillingController::class, 'detailBillings'])->name('operator.billingDetail');
        // 売上データCSV出力
        Route::get('/sales/list', [AdminSaleController::class, 'listSales'])->name('operator.salesList');       // 年月の入力画面
        Route::get('/sales/csv_output', [AdminSaleController::class, 'downloadSales'])->name('operator.salesCsvOutput');
        Route::get('/sales/detail/{id}', [AdminSaleController::class, 'detailSales'])->name('operator.salesDetail');
    });

    // plan management
    Route::group(['middleware' => ['auth:admin', 'role.admin'], 'prefix' => 'plan'], function () {
        RouteGenerator::createGroup(AdminPlanController::class, 'admin.plan');
    });

    // incentive management
    Route::group(['middleware' => ['auth:admin', 'role.admin'], 'prefix' => 'incentive'], function () {
        RouteGenerator::createGroup(AdminIncentivePlanController::class, 'admin.incentive');
    });

    //Option management
    Route::group(['middleware' => ['auth:admin', 'role.admin'], 'prefix' => 'option'], function () {
        RouteGenerator::createGroup(AdminOptionPlanController::class, 'admin.option');
    });

    //Comission management
    Route::group(['middleware' => ['auth:admin', 'role.admin'], 'prefix' => 'commission'], function () {
        RouteGenerator::createGroup(AdminComissionController::class, 'admin.commission');
    });
});

Route::domain(env('SHOP_SUB_DOMAIN') . '.' . env('APP_URL'))->group(function () {
    Route::redirect('/', '/top');
    Route::get('/login', [ShopAuthController::class, 'login'])->name('shop.login');
    Route::post('/login', [ShopAuthController::class, 'handleLogin'])->name('shop.handleLogin');
    //Top
    Route::get('/top', [ShopTopController::class, 'index'])->name('shop.top')->middleware('auth:shop');
    //Upload
    Route::get('/register_upload', [ShopDocumentController::class, 'upload'])->name('shop.registerUpload')->middleware('auth:shop');
    Route::post('/register_upload', [ShopDocumentController::class, 'handleUpload'])->name('shop.handleUpload')->middleware('auth:shop');
    //Logout
    Route::post('/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');

    Route::group(['middleware' => ['auth:shop'], 'prefix' => 'user'], function () {
        //Contractor Management
        Route::get('/list', [ShopContractorController::class, 'list'])->name('shop.userList');
        Route::get('/detail/{id}', [ShopContractorController::class, 'detail'])->name('shop.userDetail');
        Route::get('/input', [ShopContractorController::class, 'input'])->name('shop.userInput');
        Route::post('/input', [ShopContractorController::class, 'handleInput'])->name('shop.userHandleInput')->middleware('XssSanitizer');
        Route::get('/input/{id}', [ShopContractorController::class, 'edit'])->name('shop.userEdit');
        Route::put('/input', [ShopContractorController::class, 'handleEdit'])->name('shop.handleUserEdit')->middleware('XssSanitizer');
        Route::delete('/input', [ShopContractorController::class, 'handleDelete'])->name('shop.handleUserDelete');
        Route::get('/confirm', [ShopContractorController::class, 'confirm'])->name('shop.userConfirm');
        Route::post('/confirm', [ShopContractorController::class, 'handleConfirm'])->name('shop.userHandleConfirm');
        Route::delete('/confirm', [ShopContractorController::class, 'handleConfirmDelete'])->name('shop.handleUserConfirmDelete');
        Route::get('/complete', [ShopContractorController::class, 'complete'])->name('shop.userComplete');

        //Application Management
        Route::get('/request_list', [ShopApplicationController::class, 'list'])->name('shop.applicationList');
        Route::get('/request_input', [ShopApplicationController::class, 'input'])->name('shop.applicationInput');
        Route::get('/request_input/{id}', [ShopApplicationController::class, 'edit'])->name('shop.applicationEdit')->middleware('userRequest.prevent');
        Route::post('/request_input', [ShopApplicationController::class, 'handleInput'])->name('shop.handleApplicationInput')->middleware('userRequest.prevent');
        Route::put('/request_input', [ShopApplicationController::class, 'handleEdit'])->name('shop.handleApplicationEdit');
        Route::get('/request_detail/{id}', [ShopApplicationController::class, 'detail'])->name('shop.applicationDetail');

        Route::get('/export/setting-document/{id}', [ShopExportPdfController::class, 'exportSettingDocument'])->name('shop.exportSettingDocument');
        Route::get('/export/register-document/{id}', [ShopExportPdfController::class, 'exportRegisterDocument'])->name('shop.exportRegisterDocument');

        // Talk group
        Route::get('/exist_talk_group_list', [MUserTalkGroupRequestController::class, 'getList'])->name('shop.talk.group.list')->middleware('userRequest.prevent');
        Route::get('/talk_group_detail', [MUserTalkGroupRequestController::class, 'getDetail'])->name('shop.talk.group.detail');
        Route::post('/talk_group_store', [MUserTalkGroupRequestController::class, 'store'])->name('shop.talk.group.store');
        Route::post('/talk_group_existed', [MUserTalkGroupRequestController::class, 'handleAddExistedGroup'])->name('shop.handleAddExistedGroup')->middleware('userRequest.prevent');
        Route::get('/talk_group_input', [MUserTalkGroupRequestController::class, 'input'])->name('shop.talk.group.add')->middleware('userRequest.prevent');
        Route::get('/talk_group_input/{id}', [MUserTalkGroupRequestController::class, 'update'])->name('shop.talk.group.update')->middleware('userRequest.prevent');
        Route::get('/talk_group_search', [MUserTalkGroupRequestController::class, 'search'])->name('shop.talk.group.search');
        Route::delete('/talk_group_input', [MUserTalkGroupRequestController::class, 'handleDelete'])->name('shop.talk.group.delete')->middleware('userRequest.prevent');

        // line list
        Route::get('/line_list', [ShopLineListController::class, 'list'])->name('shop.talk.line.list');
        Route::get('/exist_line_list', [ShopLineListController::class, 'existedList'])->name('shop.existedList')->middleware('userRequest.prevent');
        Route::get('/line_input', [ShopLineListController::class, 'input'])->name('shop.line.add')->middleware('userRequest.prevent');
        Route::post('/line_input', [ShopLineListController::class, 'handleInput'])->name('shop.line.handle.input')->middleware('userRequest.prevent');
        Route::post('/line_input_existed', [ShopLineListController::class, 'handleAddExistedLine'])->name('shop.handleAddExistedLine')->middleware('userRequest.prevent');
        Route::get('/line_input/{id}', [ShopLineListController::class, 'edit'])->name('shop.line.update')->middleware('userRequest.prevent');
        Route::post('/line_store', [ShopLineListController::class, 'store'])->name('shop.line.store')->middleware('userRequest.prevent');
        Route::get('/line_detail', [ShopLineListController::class, 'detail'])->name('shop.user.line.list.detail');
        Route::delete('/line_input', [ShopLineListController::class, 'handleDelete'])->name('shop.line.delete')->middleware('userRequest.prevent');
        Route::post('/line_information', [ShopLineListController::class, 'showInformationLineId'])->name('shop.line.id');

        // line talk group
        Route::get('/line_talk_group_detail', [ShopLineListController::class, 'lineTalkGroupDetail'])->name('shop.line.talk.detail');
        Route::get('/line_talk_group_input', [ShopLineTalkGroupRequestController::class, 'input'])->name('shop.line.talk.group.add')->middleware('userRequest.prevent');
        Route::post('/line_talk_group_store', [ShopLineTalkGroupRequestController::class, 'store'])->name('shop.line.talk.group.store')->middleware('userRequest.prevent');
        Route::post('/line_talk_group_store_update', [ShopLineTalkGroupRequestController::class, 'confirmUpdate'])->name('shop.line.talk.group.store.update')->middleware('userRequest.prevent');
        Route::get('/line_talk_group_input/{id}', [ShopLineTalkGroupRequestController::class, 'update'])->name('shop.line.talk.group.update')->middleware('userRequest.prevent');
        Route::delete('/line_talk_group_input', [ShopLineListController::class, 'handeDeleteLineTalkGroup'])->name('shop.handleTalkGroupDelete')->middleware('userRequest.prevent');
        
    });
});

// user
Route::domain(env('USER_SUB_DOMAIN') . '.' . env('APP_URL'))->group(function () {
    Route::redirect('/', '/top');
    Route::get('/top', [UserTopController::class, 'index'])->name('user.top')->middleware('auth:user');
    Route::get('login', [UserAuthController::class, 'login'])->name('user.login');
    Route::post('login', [UserAuthController::class, 'handleLogin'])->name('user.handleLogin');

    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
});

