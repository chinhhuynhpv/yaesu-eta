@extends('operator.body')

@section('container')
    <div class="mt-5 mb-5">
        <div><h3>{{__("Confirmation")}}</h3></div>
        @include(
                'common.contractor.form-input',
                ['isOperator' => true,'cancelRoute' => 'admin.userList', 'needShopName' => true, 'actionRoute' => 'admin.userHandleConfirm', 'submitText' => __('Update'), 'readonly' => 'readonly']
            )
    </div>
@stop
