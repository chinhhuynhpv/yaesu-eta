@extends('shop.body')

@section('container')



    <div class="d-flex flex-column align-items-center mt-5">
        <div class="mt-3">
            <a href="{{route('shop.userList')}}" class="btn btn-other">{{__("Contractor list")}}</a>
        </div>
    </div>

    <div>
        TODO<br />
        Request List on '下書き' or '差し戻し'.<br />
    </div>

@stop
