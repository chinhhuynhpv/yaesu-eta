<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResourceManagementController extends Controller
{
    protected $model = [];
    protected $prefixRouteName;
    protected $rootDir;
    protected $ignoredUpdatedFields = [];

    public function list()
    {
        $list = $this->model::orderBy('id', 'desc')->search();

        return $this->view($this->rootDir . 'list', compact('list'));
    }

    public function detail($id)
    {
        $item = $this->model::find($id);
        $this->checkHtmlStatus($item);

        return $this->view($this->rootDir . 'detail', compact('item'));
    }

    public function input(Request $req)
    {
        $item = new $this->model();
        $item->mergeInput($req->old());

        return $this->view($this->rootDir . 'input', compact('item'));
    }

    public function handleInput(Request $req)
    {
        $req->validate($this->model::columnConstraints());

        if(!empty($req->get('sim_num'))) {
            $checkSimnum = $this->checkSimNumExist($req->get('sim_num'), null);
        
            if(!empty($checkSimnum)) {
                return redirect()->back()->withInput()->with('error', __('Sim number has existed !'));
            }
        }

        return redirect()->route("{$this->prefixRouteName}Confirm")->withInput();
    }

    public function edit(Request $req, $id)
    {
        $item = $this->model::find($id);
        $this->checkHtmlStatus($item);

        $item->mergeInput($req->old());

        return $this->view($this->rootDir . 'input', compact('item'));
    }

    public function handleEdit(Request $req)
    {
        $columnConstraints = $this->model::columnConstraints();
        
        if(!empty($req->get('sim_num'))) {
            $idSim = null;
            if(!empty($req->get('id'))) {
                $idSim = $req->get('id');
            }

            $checkSimnum = $this->checkSimNumExist($req->get('sim_num'), $idSim);
            
            if(!empty($checkSimnum)) {
                return redirect()->back()->withInput()->with('error', __('Sim number has existed !'));
            }
        }

        $req->validate($columnConstraints);

        return redirect()->route("{$this->prefixRouteName}Confirm")->withInput();
    }

    public function handleDelete(Request $req)
    {
        $item = $this->model::find($req->id);
        $this->checkHtmlStatus($item);

        $res = $item->delete();

        if ($res === true)  return  redirect()->route("{$this->prefixRouteName}List")->with('success', __('Delete data successfully'));

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    public function confirm(Request $req)
    {
        
        $input = $req->old();
        $columnConstraints = $this->model::columnConstraints();

        if (isset($input['id'])) {
            $item = $this->model::find($input['id']);

        } else {
            $item = new $this->model();
        }

        $validator = validator()->make($input, $columnConstraints);
        $item->mergeInput($input);

        if ($validator->fails()) {
            return redirect()->route("{$this->prefixRouteName}Input")->with('error', __('Session expired! Please input again'));
        }

        return $this->view($this->rootDir . 'confirm', compact('item'));
    }

    public function handleConfirm(Request $req)
    {
        
        $columnConstraints = $this->model::columnConstraints();

        if ($req->id) {
            $item = $this->model::find($req->id);
            $this->checkHtmlStatus($item);
        } else {
            $item = new $this->model();
        }

        $req->validate($columnConstraints);

        $data = $req->all();
        $res = $item->smartSave($data);

        if ($res === true)  return  redirect()->route("{$this->prefixRouteName}Complete")->with(['status' => 'completed', 'message' => $req->id ? __('Update successfully') : __('Create successfully')]);

        return redirect()->back()->withInput()->with('error', __('Connection error! Please try again'));
    }

    public function complete()
    {
        if (session()->has('status') && session('status') == 'completed') {
            return $this->view($this->rootDir . 'complete');
        };

        return redirect()->route("{$this->prefixRouteName}Input")->with('error', __('Session expired! Please input again'));
    }

    protected function _updatedColumnConstraints(&$columnConstraints) {
        foreach ($this->ignoredUpdatedFields as $field) {
            unset($columnConstraints[$field]);
        }
    }

    protected function view($route, $data = []) {
        return view($route, array_merge($data, ['prefixRouteName' => $this->prefixRouteName]));
    }

    protected function checkHtmlStatus($data) {
        if (!$data) abort(404);
    }
}
