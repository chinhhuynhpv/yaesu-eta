<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ResourceManagementController;
use App\Models\MSim;
use App\Traits\HandleSearchedItem;
use \Carbon\Carbon;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class AdminSimController extends ResourceManagementController
{
    use HandleSearchedItem;

    protected $model = MSim::class;
    protected $prefixRouteName = 'admin.sim';
    protected $rootDir = 'operator/sim/';
    protected $ignoredUpdatedFields = [];

    public function list()
    {
        $req = request();

        $possibleStatuses = ['new', 'in_use', 'in_pause', 'abolition', 'in_reissue'];

        $status = $this->checkSearchedItem($req->query('status'), $possibleStatuses);

        if ($status === false) {
            abort(404);
        }

        $query = $this->model::orderBy('id', 'desc')->with('line');
        $appendParams = [];

        if ($status !== null) {
            $query = $query->where('status', $status + 1);
            $appendParams['status'] = $possibleStatuses[$status];
            $status = $possibleStatuses[$status];
        }

        if (($simNum = $req->query('sim_num')) !== null) {
            $query = $query->where('sim_num', 'LIKE', "%$simNum%");
            $appendParams['sim_num'] = $simNum;
        }

        if ($simOpeningDate = $req->query('opening_date')) {
            $query = $query->whereDate('sim_opening_date', $simOpeningDate);
            $appendParams['opening_date'] = $simOpeningDate;
        }

        if (($simContractor = $req->query('sim_contractor')) !== null) {
            $query = $query->where('sim_contractor', 'LIKE', "%$simContractor%");
            $appendParams['sim_contractor'] = $simContractor;
        }

        $list = $query->paginate();
        $list->appends($appendParams);

        return $this->view(
            $this->rootDir . 'list',
            compact('list', 'possibleStatuses', 'status', 'simNum', 'simOpeningDate', 'simContractor')
        );
    }

    public function import(Request $req) {
        $req->validate([
            'file' => [
                'required',
                'mimes:csv,txt',
            ]
        ]);

        $columnConstraints = $this->model::columnConstraints();
        unset($columnConstraints['sim_num'][3]);

        $data = [];
        $errors = [];

        try {
            $contents = File::get($req->file('file'));
            $csvData = str_getcsv($contents,"\n");

            $possibleStatuses = ['新規', '利用中', '休止', '廃止', '再発行中'];

            if (count($csvData)) {
                unset($csvData[0]);

                foreach ($csvData as $key => $item) {
                    $csvRow = str_getcsv($item);

                    if (!strlen(trim(implode($csvRow)))) {
                        continue;
                    }

                    $row = [];

                    foreach (['sim_num', 'career', 'sim_contractor', 'sim_opening_date'] as $skey => $value) {
                        $row[$value] = isset($csvRow[$skey]) ? trim($csvRow[$skey]) : null;
                    }

                    $validator = validator()->make($row, $columnConstraints);
                    $rowErrors = [];

                    if ($validator->fails()) {
                        $rowErrors = $validator->errors()->all();
                    }
                    else {
                        $row['sim_opening_date'] = Carbon::parse($row['sim_opening_date'])->format('Y/m/d');
                    }

                    if (count($rowErrors)) {
                        $rowNumber = $key + 1;
                        $errors[] = __("The following inputs on row :row_number", [ "row_number" => $rowNumber]) . ":" . implode(";", $rowErrors);

                    }

                    $data[] = $row;
                }

                if (count($errors)) {
                    return redirect()->back()->withErrors(new MessageBag($errors));
                } else {
                    if ($this->model::upsert($data, ['sim_num'])) {
                        return redirect()->back()->with('success', __('Import sim list successfully'));
                    }
                }
            }

            return redirect()->back()->with('error',  __('The file is empty'));

        }  catch (FileNotFoundException $exception) {
            return redirect()->back()->with('error', __('The file doesn`t exist'));
        }
    }

    public function checkSimNumExist($simnum, $id) {

        if(!empty($id)) {
            $simId = MSim::where('sim_num', $simnum)->where('id', '!=', $id)->first();
        }else{
            $simId = MSim::where('sim_num', $simnum)->first();
        }
        
        return $simId;
    }

}
