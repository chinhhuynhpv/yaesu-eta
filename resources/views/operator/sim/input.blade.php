@extends('operator.body')

@section('container')
    <div class="mt-5 mb-5">
        <h3 class="text-center mb-5">{{$item->id ? __("Update Sim") : __("Create Sim")}}</h3>
        @include('alert.validate')
        @include(
            'operator.sim.form',
            [
                'cancelRoute' => "admin.simList",
                'needShopName' => true,
                'actionRoute' => 'admin.simHandleInput',
                'submitText' => __('Confirm'),
                'readonly' => '',
                'method' => $item->id ? 'PUT' : 'POST',
                'statusReadonly' => $item->id ? false : true
            ]
        )
    </div>
@stop
