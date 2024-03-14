@extends('shop.body')

@section('container')
    <div class="mt-5 mb-5">
        <div><h3>{{__("Confirmation")}}</h3></div>
        @include('common.contractor.form-input', ['isOperator' => false, 'needPassword' => true, 'actionRoute' => 'shop.userHandleConfirm', 'readonly' => 'readonly', 'cancelRoute' => 'shop.userList', 'submitText' => 'update'])
    </div>
@stop
