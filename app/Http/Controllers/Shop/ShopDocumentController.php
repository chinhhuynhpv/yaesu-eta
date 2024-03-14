<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\MUser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopDocumentController extends Controller
{
    public function upload()
    {
        $user = MUser::find($user_id = request()->get('user_id'));
        $this->checkValidUser($user);

        return view('shop/document/upload', ['user' => $user]);
    }

    public function handleUpload(Request $request)
    {
        $user = MUser::find($user_id = request()->get('user_id'));
        $this->checkValidUser($user);

        $rootPath = "upload/$user->contractor_id/";
        $uploadedDcoument = $request->file('document');

        $request->validate([
            'document' => [
                'required',
                'mimes:pdf',
                'mimetypes:application/pdf',
                function ($attribute, $value, $fail) use ($rootPath, $uploadedDcoument) {
                    $filename = $uploadedDcoument->getClientOriginalName();

                    if (Storage::exists($rootPath . $filename)) {
                        $fail(__('The file name was used.'));
                    }
                }
            ]
        ]);

        $path = $uploadedDcoument->storeAs($rootPath, $uploadedDcoument->getClientOriginalName(), 'local');

        if ($path) {
            // save exists_document status true
            $user->exists_document = true;
            $user->save();

            return redirect()->back()->with('success', __('Upload successfully'));
        }

        return redirect()->back()->with('error', __('Error! Please try again'));
    }

    private function checkValidUser($user) {
        if (!$user) abort(404);

        $shop = Auth::guard('shop')->user();
        if ($shop->id !== $user->shop_id) abort(403);
    }
}
