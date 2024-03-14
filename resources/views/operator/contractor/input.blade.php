@extends('operator.body')

@section('container')
    <div class="mt-5 mb-5">
        <h3 class="text-center mb-5">{{__("Update Contractor")}}</h3>
        @include('alert.validate')
        @include(
            'common.contractor.form-input',
            ['isOperator' => true, 'cancelRoute' => 'admin.userList', 'needShopName' => true, 'actionRoute' => 'admin.handleUserEdit', 'submitText' => __('Confirm'), 'readonly' => '', 'method' => 'PUT']
        )
    </div>
@stop
