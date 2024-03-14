<table class="table table-borderless detail">
    <tbody>
    @if (!empty($needShopName))
        <tr>
            <td>{{__("Shop Name")}}</td>
            <td>{{$user->shop->name}}</td>
        </tr>
    @endif
    <tr>
        <td>{{__("Contractor Id")}}</td>
        <td>{{$user->contractor_id}}</td>
    </tr>
    <tr>
        <td>{{__("Email address")}}</td>
        <td>{{$user->email}}</td>
    </tr>
    <tr>
        <td>{{__("Contractor name")}}</td>
        <td>{{$user->contract_name}}</td>
    </tr>
    <tr>
        <td>{{__("Contractor name Kana")}}</td>
        <td>{{$user->contract_name_kana}}</td>
    </tr>
    <tr>
        <td>{{__("Representative position")}}</td>
        <td>{{$user->representative_position}}</td>
    </tr>
    <tr>
        <td>{{__("Representative's name")}}</td>
        <td>{{$user->representative_name}}</td>
    </tr>
    <tr>
        <td>{{__("Representative name Kana")}}</td>
        <td>{{$user->representative_name_kana}}</td>
    </tr>
    <tr>
        <td colspan="2"><h3 class="mt-3 mb-3">{{__("Billing information")}}</h3></td>
    </tr>
    <tr>
        <td>{{__("Department")}}</td>
        <td>{{$user->billing_department}}</td>
    </tr>
    <tr>
        <td>{{__("Person Position")}}</td>
        <td>{{$user->billing_manager_position}}</td>
    </tr>
    <tr>
        <td>{{__("Person name")}}</td>
        <td>{{$user->billing_manager_name}}</td>
    </tr>
    <tr>
        <td>{{__("Zip code")}}</td>
        <td>{{$user->billing_post_number}}</td>
    </tr>
    <tr>
        <td>{{__("Prefecture")}}</td>
        <td>{{$user->billing_prefectures}}</td>
    </tr>
    <tr>
        <td>{{__("City")}}</td>
        <td>{{$user->billing_municipalities}}</td>
    </tr>
    <tr>
        <td>{{__("Address")}}</td>
        <td>{{$user->billing_address}}</td>
    </tr>
    <tr>
        <td>{{__("Building name")}}</td>
        <td>{{$user->billing_building}}</td>
    </tr>
    <tr>
        <td>{{__("TEL")}}</td>
        <td>{{$user->billing_tel}}</td>
    </tr>
    <tr>
        <td>{{__("Fax")}}</td>
        <td>{{$user->billing_fax}}</td>
    </tr>
    <tr>
        <td>{{__("E-mail")}}</td>
        <td>{{$user->billing_email}}</td>
    </tr>
    <tr>
        <td>{{__("Invoice mail")}}</td>
        <td>{{__($user->billing_shipping)}}</td>
    </tr>
    <tr>
        <td>{{__("Shipping destination")}}</td>
        <td>{{__($user->shipping_destination)}}</td>
    </tr>

@if ( $user->getRawValue('shipping_destination') == 3 )
    <tr><td colspan="2"><h3 class="mt-3 mb-3">{{__("Designated shipping destination")}}</h3></td></tr>
    <tr>
        <td>{{__("Postal code")}}</td>
        <td>{{$user->shipping_post_number}}</td>
    </tr>
    <tr>
        <td>{{__("Prefectures")}}</td>
        <td>{{$user->shipping_prefectures}}</td>
    </tr>
    <tr>
        <td>{{__("Municipalities")}}</td>
        <td>{{$user->shipping_municipalities}}</td>
    </tr>
    <tr>
        <td>{{__("Address")}}</td>
        <td>{{$user->shipping_address}}</td>
    </tr>
    <tr>
        <td>{{__("Building name")}}</td>
        <td>{{$user->shipping_building}}</td>
    </tr>
    <tr>
        <td>{{__("TEL")}}</td>
        <td>{{$user->shipping_tel}}</td>
    </tr>
    <tr>
        <td>{{__("Fax")}}</td>
        <td>{{$user->shipping_fax}}</td>
    </tr>
@endif

    <tr><td colspan="2"><h3 class="mt-3 mb-3">{{__("Payment information")}}</h3></td></tr>
    <tr>
        <td>{{__("Method of payment")}}</td>
        <td>{{__($user->payment_type)}}</td>
    </tr>
    <tr>
        <td>{{__("Bank number")}}</td>
        <td>{{$user->bank_num}}</td>
    </tr>
    <tr>
        <td>{{__("Bank name")}}</td>
        <td>{{$user->bank_name}}</td>
    </tr>
    <tr>
        <td>{{__("Branch number")}}</td>
        <td>{{$user->branchi_num}}</td>
    </tr>
    <tr>
        <td>{{__("Deposit type")}}</td>
        <td>{{__($user->deposit_type)}}</td>
    </tr>
    <tr>
        <td>{{__("Account number")}}</td>
        <td>{{$user->account_num}}</td>
    </tr>
    <tr>
        <td>{{__("Account name")}}</td>
        <td>{{$user->account_name}}</td>
    </tr>

    <tr>
        <td>{{__("Bank entruster number")}}</td>
        <td>{{$user->bank_entruster_num}}</td>
    </tr>
    <tr>
        <td>{{__("Bank customer number")}}</td>
        <td>{{$user->bank_customer_num}}</td>
    </tr>
    <tr>
        <td>{{__("Contractor status")}}</td>
        <td>{{__("$user->status")}}</td>
    </tr>
    </tbody>
</table>
