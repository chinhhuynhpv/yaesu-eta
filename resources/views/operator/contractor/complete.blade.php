@extends('operator.body')

@section('container')
    <div class="text-center">
        <h3 class="mt-2">{{__("Completion")}}</h3>
        <h3 class="mt-3">{{session('message')}}</h3>
        <div class="mt-5"><a class="" href="{{route("admin.userList")}}">{{__("Go to list")}}</a></div>
    </div>
@stop
