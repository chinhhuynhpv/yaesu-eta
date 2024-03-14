@extends('shop.body')

@section('container')
    <div>

        <h2>{{__("Upload Document")}}</h2>

            
        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        @include('alert.validate')
        <div id="register-upload" class="">
            <form method="post" enctype="multipart/form-data" action="{{route('shop.handleUpload')}}">
                @csrf
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <div class="form-group">
                    <input class="form-control" type="file" name="document" required>
                </div>
                <div class="submit-buttons">
                    <a class="btn btn-square" href="{{route('shop.userList', ['id' => $user->id])}}">{{__("Back")}}</a>
                    <input class="btn btn-submit" type="submit" value="{{__("Upload")}}" name="submit">
                </div>
            </form>
        </div>
    </div>
@stop
