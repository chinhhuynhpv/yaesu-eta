<?php

namespace App\Helper;

use Illuminate\Support\Facades\Route;

class RouteGenerator
{
    static public function createGroup($controller,$prefixRouteName) {
        Route::get('list', [$controller, 'list'])->name("{$prefixRouteName}List");
        Route::get('detail/{id}', [$controller, 'detail'])->name("{$prefixRouteName}Detail");
        Route::get('input', [$controller, 'input'])->name("{$prefixRouteName}Input");
        Route::get('input/{id}', [$controller, 'edit'])->name("{$prefixRouteName}Edit");
        Route::post('input', [$controller, 'handleInput'])->name("{$prefixRouteName}HandleInput")->middleware('XssSanitizer');
        Route::put('input', [$controller, 'handleEdit'])->name("{$prefixRouteName}HandleEdit")->middleware('XssSanitizer');
        Route::delete('input', [$controller, 'handleDelete'])->name("{$prefixRouteName}HandleDelete");
        Route::get('confirm', [$controller, 'confirm'])->name("{$prefixRouteName}Confirm");
        Route::post('confirm', [$controller, 'handleConfirm'])->name("{$prefixRouteName}HandleConfirm");
        Route::get('complete', [$controller, 'complete'])->name("{$prefixRouteName}Complete");
    }
}
