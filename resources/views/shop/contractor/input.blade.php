@extends('shop.body')

@section('container')
    <div class="mt-5 mb-5">
        <h2 class="text-center mt-3">{{ $user->id ? __("Update User") : __("Create User")}}</h2>
        @include('alert.validate')
        @include('common.contractor.form-input',
            [
                'isOperator' => false,
                'needPassword' => true,
                'passwordRequired' => $user->id ? '' : 'required',
                'actionRoute' => 'shop.userHandleInput',
                'readonly' => '',
                'cancelRoute' => 'shop.userList',
                'submitText' => 'confirm',
                'method' => $user->id ? 'PUT' : 'POST'
            ])
    </div>
@stop
