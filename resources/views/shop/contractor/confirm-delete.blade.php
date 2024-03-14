@extends('shop.body')

@section('container')
    <div class="ctr">
        <h2>{{__("Confirmation")}}</h2>
        <div>{{__("Do you want to delete :user", ['user' => $user->contract_name])}}</div>
        <div>
            <form method="post" action="{{route('shop.handleUserConfirmDelete')}}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                <div class="submit-buttons">
                    <a class="btn btn-square" href="{{route("shop.userList")}}">{{__("Back")}}</a>
                    <input type="submit" class="btn btn-delete btn-square" value="{{__("Delete")}}">
                </div>
            </form>
        </div>
    </div>
@stop
