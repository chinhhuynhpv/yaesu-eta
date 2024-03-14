@extends('operator.body')

@section('container')
    <div class="text-center mt-3">
        <h2 class="mt-2">{{__("Completion")}}</h2>
        
        <h3 class="mt-3">{{session('message')}}</h3>
        <div class="mt-5"><a class="btn" href="{{route("admin.simList")}}">{{__("Go to list")}}</a></div>
    </div>
@stop
