@extends('operator.body')

@section('container')
    <div>
        <h3>{{__("Confirmation")}}</h3>
        <div>{{__("Do you want to delete :user", ['user' => $user->contract_name])}}</div>
        <div>
            <form method="post" action="{{route('admin.handleUserConfirmDelete')}}">
                @method('DELETE')
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                <input type="submit" class="btn btn-delete" value="{{__("Delete")}}">
            </form>
        </div>
    </div>
@stop
