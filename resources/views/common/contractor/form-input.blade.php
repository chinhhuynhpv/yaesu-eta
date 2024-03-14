<form method="post" action="{{route($actionRoute)}}" class="iu" >
    @csrf
    {{method_field($method ?? "POST")}}
    @if ($user->id)
        <input {{$readonly}} type="hidden" name="id" class="form-control col-md-8" value="{{$user->id}}" required>
    @endif
        
    @if (!empty($needShopName))
    <div class="form-group row row">
        <label class="col-md-4 mt-1">{{__("Shop name")}}</label>
        <input {{$readonly}} type="text" name="shop_name" class="form-control col-md-8" value="{{$user->shop->name}}" disabled>
    </div>
    @endif
    <div class="form-group row row">
        <label class="col-md-4 mt-1">{{__("Contractor Id")}}</label>
        <input {{$readonly}} type="text" name="contractor_id" class="form-control col-md-8" value="{{$user->contractor_id}}" disabled>
    </div>
    <div class="form-group row row">
        <label class="col-md-4 mt-1">{{__("Email address")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="email" name="email" class="form-control col-md-8" value="{{$user->email}}" required>
    </div>
    @if (!empty($needPassword))
        <div class="form-group row">
            <label class="col-md-4 mt-1">{{__("Password")}}<span class="asta">*</span></label>
            <input {{$readonly}} type="password" name="password" class="form-control col-md-8" value="{{$readonly ? $user->password : ($user->id ? '' : $user->password)}}" required>
        </div>
        <div class="form-group row">
            <label class="col-md-4 mt-1">{{__("Confirmed Password")}}<span class="asta">*</span></label>
            <input {{$readonly}} type="password" name="password_confirmation" class="form-control col-md-8" value="{{$user->password_confirmation}}" required>
        </div>
    @endif
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Contractor name")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="contract_name" class="form-control col-md-8" value="{{$user->contract_name}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Contractor name Kana")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="contract_name_kana" pattern="[ァ-ヴー　]+" title="{{__('Please input Zenkana.')}}" placeholder="{{__('ゼンカクカナ　シメイ')}}" class="form-control col-md-8" value="{{$user->contract_name_kana}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Representative position")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="representative_position" class="form-control col-md-8" value="{{$user->representative_position}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Representative's name")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="representative_name" class="form-control col-md-8" value="{{$user->representative_name}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Representative name Kana")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="representative_name_kana" pattern="[ァ-ヴー　]+" title="{{__('Please input Zenkana.')}}" placeholder="{{__('ゼンカクカナ　シメイ')}}" class="form-control col-md-8" value="{{$user->representative_name_kana}}" required>
    </div>
    <h3 class="mt-3 mb-3">{{__("Billing information")}}</h3>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Department")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_department" class="form-control col-md-8" value="{{$user->billing_department}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Manager position")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_manager_position" class="form-control col-md-8" value="{{$user->billing_manager_position}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Manager name")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_manager_name" class="form-control col-md-8" value="{{$user->billing_manager_name}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Postal code")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_post_number" class="form-control col-md-8" value="{{$user->billing_post_number}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Prefecture")}}<span class="asta">*</span></label>
        <select {{$readonly}} name="billing_prefectures" class="form-control col-md-8" required>
            <option value="">{{__("Choose one")}}</option>
            @php $prefecturesArr = $prefectures->getArray(); @endphp
            @foreach($prefecturesArr as $key => $prefecture)
                @php $billingSelected = $readonly ? 'disabled' : ''; @endphp
                @if ( $user->billing_prefectures == $key || $user->billing_prefectures == $prefecture['pref'] )
                    @php
                        $billingPrefectureKey = $key;
                        $billingSelected = 'selected';
                    @endphp
                @endif
                <option value="{{$key}}" {{$billingSelected}}>{{$prefecture['pref']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Municipalities")}}<span class="asta">*</span></label>
        <select {{$readonly}} name="billing_municipalities" class="form-control col-md-8" required>
            <option value="">{{__("Choose one")}}</option>
            @if(!empty($billingPrefectureKey))
                @foreach($prefecturesArr[$billingPrefectureKey]['city'] as $ckey => $city)
                    <option value="{{$city['id']}}" {{$user->billing_municipalities == $city['id'] || $user->billing_municipalities == $city['name'] ? 'selected' : ($readonly ? 'disabled' : '')}}>{{$city['name']}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Address")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_address" class="form-control col-md-8" value="{{$user->billing_address}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Building name")}}</label>
        <input {{$readonly}} type="text" name="billing_building" class="form-control col-md-8" value="{{$user->billing_building}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("TEL")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="billing_tel" class="form-control col-md-8" value="{{$user->billing_tel}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Fax")}}</label>
        <input {{$readonly}} type="text" name="billing_fax" class="form-control col-md-8" value="{{$user->billing_fax}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Email address")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="email" name="billing_email" class="form-control col-md-8" value="{{$user->billing_email}}" required>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Invoice mail")}}<span class="asta">*</span></label>
        <div class="col-md-3">
            <input {{$readonly ? 'onclick="return false;"' : ''}}  type="radio"  name="billing_shipping" value="0" {{!$user->billing_shipping || $user->getRawValue('billing_shipping') == 0 ? 'checked' : ''}}>
            <label>{{__("No")}}</label>
        </div>
        <div class="col-md-3">
            <input {{$readonly ? 'onclick="return false;"' : ''}}  type="radio"  name="billing_shipping" value="1" {{$user->getRawValue('billing_shipping') == 1 ? 'checked' : ''}}>
            <label>{{__("Yes")}}</label>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Shipping destination")}}<span class="asta">*</span></label>
        <div class="col-md-2">
            <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio" id="shipping_contractor" name="shipping_destination" value="1" {{!$user->shipping_destination || $user->getRawValue('shipping_destination') == 1 ? 'checked' : ''}}>
            <label>{{__("Contractor")}}</label>
        </div>
        <div class="col-md-2">
            <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio" id="shipping_dealer"  name="shipping_destination" value="2" {{$user->getRawValue('shipping_destination') == 2 ? 'checked' : ''}}>
            <label>{{__("Dealer")}}</label>
        </div>
        <div class="col-md-2">
            <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio" id="shipping_designated"  name="shipping_destination" value="3" {{$user->getRawValue('shipping_destination') == 3 ? 'checked' : ''}}>
            <label>{{__("Designated")}}</label>
        </div>
    </div>
    <div id="shipping-destination">
        <h3 class="mt-3 mb-3">{{__("Designated shipping destination")}}</h3>
        <div class="ml-3">
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Postal code")}}</label>
                <input {{$readonly}} type="text" name="shipping_post_number" class="form-control col-md-8" value="{{$user->shipping_post_number}}">
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Prefectures")}}</label>
                <select name="shipping_prefectures" class="form-control col-md-8">
                    <option value="">{{__("Choose one")}}</option>
                    @foreach($prefecturesArr as $key => $prefecture)
                        @php $shippingSelected = $readonly ? 'disabled' : ''; @endphp
                        @if ( $user->shipping_prefectures == $key || $user->shipping_prefectures == $prefecture['pref'] )
                            @php
                                $shippingPrefectureKey = $key;
                                $shippingSelected = 'selected';
                            @endphp
                        @endif
                        <option value="{{$key}}" {{$shippingSelected}}>{{$prefecture['pref']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Municipalities")}}</label>
                <select name="shipping_municipalities" class="form-control col-md-8">
                    <option value="">{{__("Choose one")}}</option>
                    @if(!empty($shippingPrefectureKey))
                        @foreach($prefecturesArr[$shippingPrefectureKey]['city'] as $ckey => $city)
                            <option value="{{$city['id']}}" {{$user->shipping_municipalities == $city['id'] || $user->shipping_municipalities == $city['name'] ? 'selected' : ($readonly ? 'disabled' : '')}}>{{$city['name']}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Address")}}</label>
                <input {{$readonly}} type="text" name="shipping_address" class="form-control col-md-8" value="{{$user->shipping_address}}">
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Building name")}}</label>
                <input {{$readonly}} type="text" id="shipping_building" name="shipping_building" class="form-control col-md-8" value="{{$user->shipping_building}}">
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("TEL")}}</label>
                <input {{$readonly}} type="text" name="shipping_tel" class="form-control col-md-8" value="{{$user->shipping_tel}}">
            </div>
            <div class="form-group row">
                <label class="col-md-4 mt-1">{{__("Fax")}}</label>
                <input {{$readonly}} type="text" id="shipping_fax" name="shipping_fax" class="form-control col-md-8" value="{{$user->shipping_fax}}">
            </div>
        </div>
    </div>
    @if ($isOperator)
    <h3 class="mt-3 mb-3">{{__("Payment information")}}</h3>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Method of payment")}}<span class="asta">*</span></label>
        <div class="col-md-8">
            <div class="radio-box">
                <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio"  name="payment_type" value="1" {{!$user->payment_type || $user->getRawValue('payment_type') == 1 ? 'checked' : ''}}>
                <label>{{__("Automatic withdrawal")}}</label>
            </div>
            <div class="radio-box">
                <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio"  name="payment_type" value="2" {{$user->getRawValue('payment_type') == 2 ? 'checked' : ''}}>
                <label>{{__("Invoice")}}</label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Bank number")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" pattern="[0-9]+" name="bank_num" class="form-control col-md-8" value="{{$user->bank_num}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Bank name")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="bank_name" class="form-control col-md-8" value="{{$user->bank_name}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Branch number")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" pattern="[0-9]+" name="branchi_num" class="form-control col-md-8" value="{{$user->branchi_num}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Branch name")}}<span class="asta">*</span></label>
        <input {{$readonly}} type="text" name="branchi_name" class="form-control col-md-8" value="{{$user->branchi_name}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Deposit type")}}<span class="asta">*</span></label>
        <div class="col-md-8">
            <div class="radio-box">
                <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio"  name="deposit_type" value="1" {{!$user->deposit_type || $user->getRawValue('deposit_type') == 1 ? 'checked' : ''}}>
                <label>{{__("Savings Account")}}</label>
            </div>
            <div class="radio-box">
               <input {{$readonly ? 'onclick="return false;"' : ''}} type="radio"  name="deposit_type" value="2" {{$user->getRawValue('deposit_type') == 2 ? 'checked' : ''}}>
               <label>{{__("Checking Account")}}</label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Account number")}}</label>
        <input {{$readonly}} type="text" name="account_num" class="form-control col-md-8" value="{{$user->account_num}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Account name")}}</label>
        <input {{$readonly}} type="text" name="account_name" pattern="[ｧ-ﾝﾞﾟ ]+"  title="{{__('Please input Hankana.')}}" placeholder="ｶﾌﾞｼｷｶｲｼｬﾊﾝｶｸｶﾅﾒｲ" class="form-control col-md-8" value="{{$user->account_name}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Bank entruster number")}}</label>
        <input {{$readonly}} type="text" name="bank_entruster_num" class="form-control col-md-8" value="{{$user->bank_entruster_num}}">
    </div>
    <div class="form-group row">
        <label class="col-md-4 mt-1">{{__("Bank customer number")}}</label>
        <input {{$readonly}} type="text" name="bank_customer_num" class="form-control col-md-8" value="{{$user->bank_customer_num}}">
    </div>
    @endif
    <div class="submit-buttons">
        <a class="btn btn-cancel btn-square" href="{{route($cancelRoute)}}">{{__("Cancel")}}</a>
       <input type="submit" class="btn btn-submit btn-square" value="{{__($submitText)}}">
    </div>
</form>

@section('script')
    <script>
        const prefectures = <?php echo $prefectures->getJsonData(); ?>;

        $('select[name="billing_prefectures"]').change(function(e){
            append($(this), $('select[name="billing_municipalities"]'));
        });

        $('select[name="shipping_prefectures"]').change(function(e){
            append($(this), $('select[name="shipping_municipalities"]'));
        });

        function append($prefecture, $cities) {
            const firstOption = $cities.find('option').first().prop('outerHTML');
            $cities.html(firstOption);

            if ($prefecture.val()) {
                const cities = prefectures[$prefecture.val()].city;

                // 政令指定都市
                var target_city_id  = "";
                var target_city_name = "";

                for (let city of cities) {
                    var city_id = city.id;
                    var city_name = city.name;
                    var target = city_id.slice(-2);
                    // 40130福岡市だけちょっと特殊なコード
                    if (target == "00" || city_id == "40130") {
                        target_city_id = city_id;
                        target_city_name = city_name;
                        continue;
                    }
                    if (target_city_id.slice(0, 3) == city_id.slice(0, 3)) {
                        $cities.append('<option value="' + city_id + '">' + target_city_name + city_name +'</option>');
                    } else {
                        $cities.append('<option value="' + city_id + '">' + city_name +'</option>');
                    }
                }
            }
        }

    </script>
    <script>
        $('#shipping-destination').hide();

        if($('#shipping_designated').is(":checked")) {
            $('#shipping-destination').show();
        } else {
            // 指定ではない場合に出荷先をクリア
            $('#shipping_post_number').val('');
            $('#shipping_prefectures').val('');
            $('#shipping_municipalities').val('');
            $('#shipping_address').val('');
            $('#shipping_building').val('');
            $('#shipping_tel').val('');
            $('#shipping_fax').val('');
        }

        $('#shipping_designated').click(function() {
            if($(this).is(':checked')) {
                $('#shipping-destination').show();
            }
        });

        $('#shipping_contractor').click(function() {
            $('#shipping-destination').hide();
        });

        $('#shipping_dealer').click(function() {
            $('#shipping-destination').hide();
        });

    </script>
@stop
