@extends('shop.body')

@section('container')
    <div class="mt-3">

        <div class="text-center">
            <h4>{{session('message')}}</h3>
            <div><a class="btn btn-primary" href="{{route("shop.userList")}}">{{__("Go to list")}}</a></div>
        </div>
        
    </div>
@stop
