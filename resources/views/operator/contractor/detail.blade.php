@extends('operator.body')

@section('container')
    <div class="mt-3">
        <h3 class="text-center">{{__("Contractor Details")}}</h3>
        @include('common.contractor.table-detail', ['needShopName' => true])
        <div class="text-center">
            <a class="btn" href="{{route("admin.userList")}}">{{__("Back")}}</a>
        </div>
    </div>
@stop
