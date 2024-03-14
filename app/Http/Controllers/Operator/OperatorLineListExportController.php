<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OperatorLineListExport;
use Maatwebsite\Excel\Facades\Excel;

class OperatorLineListExportController extends Controller
{

    public function exportCSVLineList(Request $request) {
        $userId = null;
        $userId = $request->get('id');
        
        return Excel::download(new OperatorLineListExport($userId), 'lineList.csv');
    }
}
