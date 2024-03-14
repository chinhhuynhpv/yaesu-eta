@extends('admin.body')

@section('container')
    <div class="mt-5 mb-5">
        <div class="row">
            <div class="col-md-10"><h3>{{__("Confirmation")}}</h3></div>
        </div>
        @include('alert.validate')
        <form method="post" action="{{route("{$prefixRouteName}HandleConfirm")}}">
            @csrf
            @if ($item->id)
                <input readonly type="hidden" name="id" class="form-control" value="{{$item->id}}" required>
            @endif
            <div class="form-group">
                <label>{{__("Calculation unit")}}</label>
                <div class="row">
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="calculation_unit" value="1" {{$item->calculation_unit == 'line' ? 'checked' : ''}}>
                        {{__("Line")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="calculation_unit" value="2" {{$item->calculation_unit == 'application' ? 'checked' : ''}}>
                        {{"Application"}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Plan name")}}</label>
                <input readonly type="text" name="plan_name" class="form-control" value="{{$item->plan_name}}">
            </div>
            <div class="form-group">
                <label>{{__("Effective date")}}</label>
                <input readonly type="date" name="effective_date" class="form-control" value="{{$item->effective_date}}">
            </div>
            <div class="form-group">
                <label>{{__("Expire date")}}</label>
                <input readonly type="date" name="expire_date" class="form-control" value="{{$item->expire_date}}">
            </div>
            <div class="form-group">
                <label>{{__("Dealer Web")}}</label>
                <div class="row">
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="shop_web" value="1" {{$item->getRawValue('shop_web') == '1' ? 'checked' : ''}}>
                        {{__("Enabled")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="shop_web" value="2" {{$item->getRawValue('shop_web') == '2' ? 'checked' : ''}}>
                        {{__("Hidden")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Authority")}}</label>
                <div class="row">
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="authority" value="1" {{$item->getRawValue('authority') == '1' ? 'checked' : ''}}> {{__("Dealer")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="authority" value="2" {{$item->getRawValue('authority') == '2' ? 'checked' : ''}}> {{__("Operator")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="authority" value="3" {{$item->getRawValue('authority') == '3' ? 'checked' : ''}}> {{__("Administrator")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Calculation type")}}</label>
                <div class="row">
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="calculation_type" value="1" {{$item->getRawValue('calculation_type') == '1' ? 'checked' : ''}}> {{__("Occurrence month only")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="radio" name="calculation_type" value="2" {{$item->getRawValue('calculation_type') == '2' ? 'checked' : ''}}> {{__("Monthly")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <input onclick="return false;" type="checkbox" name="usage_details_description" value="1" {{$item->getRawValue('usage_details_description') == "1" ? 'checked' : ''}}> {{__("Usage statement")}}
                    </div>
                    <div class="col-md-4">
                        <input onclick="return false;" type="checkbox" name="incentive_description" value="1" {{$item->getRawValue('incentive_description') == "1" ? 'checked' : ''}}> {{__("Incentive details")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Cancellation limit period")}}</label>
                <input readonly type="text" name="cancellation_limit_period" class="form-control" value="{{$item->cancellation_limit_period}}">
            </div>
            <div class="form-group">
                <label>{{__("Usage fee unit price")}}</label>
                <input readonly type="text" name="usage_unit_price" class="form-control" value="{{$item->usage_unit_price}}">
            </div>
            <div class="form-group">
                <label>{{__("Period")}}</label>
                <input readonly type="text" name="period" class="form-control" value="{{$item->period}}">
            </div>
            <div class="form-group">
                <label>{{__("Incentive unit price")}}</label>
                <input readonly type="text" name="incentive_unit_price" class="form-control" value="{{$item->incentive_unit_price}}">
            </div>
            <div class="form-group">
                <label>{{__("Incentive unit price 2")}}</label>
                <input readonly type="text" name="incentive_unit_price2" class="form-control" value="{{$item->incentive_unit_price2}}">
            </div>
            <div class="form-group">
                <label>{{__("Incentive unit price 3")}}</label>
                <input readonly type="text" name="incentive_unit_price3" class="form-control" value="{{$item->incentive_unit_price3}}">
            </div>
            @if (!empty($needMidwayCancellUnitPrice))
                <div class="form-group">
                    <label>{{__("Midway Cancel unit price")}}</label>
                    <input type="text" name="midway_cancell_unit_price" class="form-control" value="{{$item->midway_cancell_unit_price}}">
                </div>
            @endif
            <div class="submit-buttons">
                <input class="btn btn-square" type="button" value="{{__("Fix")}}" onclick="history.back()">
                <input readonly type="submit" class="btn btn-submit" value="{{__("Submit")}}">
            </div>
        </form>
    </div>
@stop
