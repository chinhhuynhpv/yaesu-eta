<?php

namespace App\Models;

use App\Helper\Prefectures;
use App\Traits\HandleDate;
use App\Traits\HandleSearch;
use App\Traits\HandleInput;
use App\Traits\HandleNumberingRule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MUser extends Authenticatable
{
    use HandleDate;
    use HandleInput;
    use HandleNumberingRule;
    use HandleSearch;
    use HasFactory;
    use SoftDeletes;

    protected $guard = 'user';
    protected $fillable = ['login_id', 'password'];
    protected $hidden = ['password'];
    // Optional Search
    protected $search = ['email', 'contract_name'];

    /**
     * Get the shop associated with the user.
     */
    public function shop()
    {
        return $this->belongsTo(MShop::class, 'shop_id');
    }

    public function getBillingShippingAttribute($value)
    {
        if ($value === null) return null;

        return $value ? __('Ask mail') : __('Not ask mail');
    }

    public function getShippingDestinationAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('Contractor');
            case '2':
                return __('Shop');
            case '3':
                return __('Designated');
            default:
                return null;
        }
    }

    public function getPaymentTypeAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('By automatic withdrawal');
            case '2':
                return __('By invoice');
            //case '3':
            //    return 'By card';
            default:
                return null;
        }
    }

    public function getDepositTypeAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('Ordinary account');
            case '2':
                return __('Current account');
            default:
                return null;
        }
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case '1':
                return __('Temprary contract');
            case '2':
                return __('Permanent contract');
            default:
                return null;
        }
    }

    static public function generateContracId()
    {

        $currentUser = Auth::guard('shop')->user(); 

        $shopCode = substr($currentUser->shop->code, 1, 4);

        $yearRule = self::yearRule();

        $last = self::where('contractor_id', 'LIKE', $shopCode . $yearRule . "%")->orderBy('id', 'DESC')->first();

        if (!$last) {
            $seri_number = 001;
        } else {
            $seri_number = (int) str_replace($shopCode . $yearRule, '', $last->contractor_id) + 1;
        }

        $seri_number = sprintf("%03d", $seri_number);

        return $shopCode . $yearRule . $seri_number;
    }

    public function setPrefectures(&$data) {
        $prefectures = new Prefectures();

        $this->setCity($prefectures, 'billing_prefectures', 'billing_municipalities', $data);
        $this->setPrefecture($prefectures,'billing_prefectures', $data);
        // 出荷先
        switch ($data['shipping_destination']) {
            // 契約者
            case config('const.shipping_destination.user'):
                $data['shipping_post_number'] = $data['billing_post_number'];
                $data['shipping_prefectures'] = $data['billing_prefectures'];
                $data['shipping_municipalities'] = $data['billing_municipalities'];
                $data['shipping_address'] =  $data['billing_address'];
                $data['shipping_building'] = $data['billing_building'];
                $data['shipping_tel'] = $data['billing_tel'];
                $data['shipping_fax'] = $data['billing_fax'];
                $this->setCity($prefectures, 'shipping_prefectures', 'shipping_municipalities', $data);
                $this->setPrefecture($prefectures, 'shipping_prefectures', $data);
                unset($data['shipping_prefectures']);
                unset($data['shipping_municipalities']);
                break;
            // 代理店
            case config('const.shipping_destination.shop'):
                $shopinfo = $this->shop()->first();
                $data['shipping_post_number'] = $shopinfo->postal_cd;
                $data['shipping_prefectures'] = $shopinfo->prefecture;
                $data['shipping_municipalities'] = $shopinfo->city;
                $data['shipping_address'] =  $shopinfo->address;
                $data['shipping_building'] = $shopinfo->building_name;
                $data['shipping_tel'] = $shopinfo->tel_number;
                $data['shipping_fax'] = $shopinfo->fax_number;
                break;
            // 指定
            case config('const.shipping_destination.specify'):
                $this->setCity($prefectures, 'shipping_prefectures', 'shipping_municipalities', $data);
                $this->setPrefecture($prefectures, 'shipping_prefectures', $data);
                unset($data['shipping_prefectures']);
                unset($data['shipping_municipalities']);
                break;
        }

        unset($data['billing_prefectures']);
        unset($data['billing_municipalities']);


    }

    private function setPrefecture(Prefectures $prefectures, $field_name, $data) {
        if ($prefectures->checkPrefecture($data[$field_name])) {
            $this->attributes[$field_name] = $prefectures->getPrefecture($data[$field_name])['pref'];
        }
    }

    private function setCity(Prefectures $prefectures, $prefecture_name, $city_name, $data) {
        if ($prefectures->checkCityPrefectureMatching($data[$prefecture_name], $data[$city_name]) !== false) {
            $this->attributes[$city_name] = $prefectures->getCity($data[$prefecture_name], $data[$city_name])['name'];
        }
    }

    public static function columnConstraints(bool $isOperator)
    {
        $prefectures = new Prefectures();

        $rules = [
            'email' => [
                'required',
                'max:50',
                'email'
            ],
            'password' => 'required|confirmed|max:255',
            'contract_name' => 'required|max:30',
            'contract_name_kana' => 'required|max:30|regex:/\A[ァ-ヴー　]+\z/u',
            'representative_position' => 'required|max:30',
            'representative_name' => 'required|max:30',
            'representative_name_kana' => 'required|max:30|regex:/\A[ァ-ヴー　]+\z/u',
            'billing_department' => 'string|required|max:30',
            'billing_manager_position' => 'string|required|max:30',
            'billing_manager_name' => 'string|required|max:30',
            'billing_post_number' => 'string|required|zip_code',
            'billing_prefectures' => [
                'string',
                'required',
                'max:10',
                function ($attribute, $value, $fail) use ($prefectures) {
                    if (!$prefectures->checkPrefecture($value)) {
                        $fail(__('Billing prefecture is not valid.'));
                    }
                },
            ],
            'billing_municipalities' => [
                'string',
                'required',
                'max:100',
                function ($attribute, $value, $fail) use ($prefectures) {
                    if ($prefectures->checkCityPrefectureMatching(request()->billing_prefectures, $value) === false) {
                        $fail(__('Billing municipality is not valid.'));
                    }
                },
            ],
            'billing_address' => 'string|required|max:100',
            'billing_building' => 'string|nullable|max:30',
            'billing_tel' => 'required|string|phone_number',
            'billing_fax' => 'nullable|string|phone_number',
            'billing_email' => 'email|required|max:50',
            'billing_shipping' => 'boolean',
            'shipping_destination' => 'string|nullable|max:1|in:1,2,3',
            'shipping_post_number' => 'string|nullable|required_if:shipping_destination,3|zip_code',
            'shipping_prefectures' => [
                'string',
                'nullable',
                'max:10',
                'required_if:shipping_destination,3',
                function ($attribute, $value, $fail) use ($prefectures) {
                    if (!$prefectures->checkPrefecture($value)) {
                        $fail(__('Shipping prefecture is not valid.'));
                    }
                },
            ],
            'shipping_municipalities' =>  [
                'string',
                'nullable',
                'max:100',
                'required_if:shipping_destination,3',
                function ($attribute, $value, $fail) use ($prefectures) {
                    if ($prefectures->checkCityPrefectureMatching(request()->shipping_prefectures, $value) === false) {
                        $fail(__('Shipping municipality is not valid.'));
                    }
                },
            ],
            'shipping_address' => 'string|nullable|required_if:shipping_destination,3|max:100',
            'shipping_building' => 'string|nullable|max:30',
            'shipping_tel' => 'string|nullable|required_if:shipping_destination,3|phone_number',
            'shipping_fax' => 'string|nullable|phone_number',

        ];
        if ($isOperator) {
            $rules += ['payment_type' => 'string|required|max:1|in:1,2,3'];
            $rules += ['bank_num' => 'required|numeric|digits:4|regex:/^[0-9]+$/u'];
            $rules += ['bank_name' => 'string|required|max:255'];
            $rules += ['branchi_num' => 'required|numeric|digits:3|regex:/^[0-9]+$/u'];
            $rules += ['branchi_name' => 'string|required|max:255'];
            $rules += ['deposit_type' => 'string|required|max:1|in:1,2,3'];
            $rules += ['account_num' => 'required|numeric|digits:7'];
            $rules += ['account_name' => 'string|required|max:50|regex:/^[ｧ-ﾝﾞﾟ ]+$/u'];
            $rules += ['bank_entruster_num' => 'numeric|nullable|digits:8'];
            $rules += ['bank_customer_num' => 'numeric|nullable|digits:12'];
            $rules += ['status' => 'string|nullable|max:1|in:1,2'];
        }
        return $rules;
    }
}
