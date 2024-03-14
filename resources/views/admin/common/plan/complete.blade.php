@extends('admin.body')

@section('container')
    <div>
        <h3>{{session('message')}}</h>
        <div><a class="btn" href="{{route("{$prefixRouteName}List")}}">{{__("Go to list")}}</a></div>
    </div>
@stop
