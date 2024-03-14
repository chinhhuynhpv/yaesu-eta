@extends('shop.body')

@section('container')
    <div class="mt-3">

        <div class="text-center">
            <h2>{{__("User Details")}}</h2>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <a class="btn btn-normal" href="{{route("shop.applicationList", ['user_id' => $user->id])}}">{{__("App list")}}</a>
                <a class="btn btn-normal" href="{{route("shop.talk.line.list", ['user_id' => $user->id])}}">{{__("Line List")}}</a>

                <a class="btn btn-update" href="{{route("shop.userEdit", ['id' => $user->id])}}">{{__("Edit")}}</a>

                <form method="post" action="{{route('shop.handleUserDelete')}}" class="i-b">
                    {{method_field('DELETE')}}
                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <input type="submit" class="btn btn-delete" value="{{__("Delete")}}">
                </form>

            </div>
        </div>

        @include('common.contractor.table-detail')
        <div class="lft">
            <a class="btn" href="{{route("shop.userList")}}">{{__("Back")}}</a>
        </div>
    </div>
@stop
