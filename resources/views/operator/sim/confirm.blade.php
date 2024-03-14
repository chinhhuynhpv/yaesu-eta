@extends('operator.body')

@section('container')
    <div class="mt-5 mb-5">
        <h3 class="text-center mb-5">{{__("Confirmation")}}</h3>
        @include('alert.validate')
        @include(
            'operator.sim.form',
            ['cancelRoute' => "admin.simList", 'actionRoute' => 'admin.simHandleConfirm', 'submitText' => $item->id ? __('Update') : __('Create'), 'readonly' => 'readonly', 'method' => 'POST']
        )
    </div>
@stop
