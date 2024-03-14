@extends('shop.body')

@section('container')
    @php $user = $userRequest->user; @endphp

    <div class="mt-5 mb-5">
        <div>
            <h2>{{ $userRequest->id ? __("message.update_application") : __("message.create_application")}}</h2>
        </div>
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>

        @include('alert.validate')
        <form method="post" action="{{route('shop.handleApplicationInput')}}">
            @csrf
            @if ($userRequest->id)
                <input type="hidden" name="id" value="{{$userRequest->id}}">
                @method('put')
            @endif
            <input type="hidden" name="user_id" value="{{$userRequest->user->id}}">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{__("message.application_number")}}</label>
                <div class="col-md-10">
                    <input class="form-control" name="request_number" type="text"  value="{{$userRequest->request_number}}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{__("message.application_date")}}</label>
                <div class="col-md-10">
                    <input class="form-control" name="request_date" type="date"  value="{{$userRequest->getRawValue('request_date')}}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <span>{{__("message.application_type_addition")}}</span>
                    <input name="add_flg" type="checkbox" value="1" {{$userRequest->add_flg ? 'checked' : ''}}>
                </div>
                <div class="col-md-3">
                    <span>{{__("Application type change")}}</span>
                    <input name="modify_flg" type="checkbox" value="1" {{$userRequest->modify_flg ? 'checked' : ''}}>
                </div>
                <div class="col-md-3">
                    <span>{{__("Application type pause")}}</span>
                    <input name="pause_restart_flg" type="checkbox" value="1" {{$userRequest->pause_restart_flg ? 'checked' : ''}}>
                </div>
                <div class="col-md-3">
                    <span>{{__("Application type discontinued")}}</span>
                    <input name="discontinued_flg" type="checkbox" value="1" {{$userRequest->discontinued_flg ? 'checked' : ''}}>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Remarks")}}</label>
                <textarea class="form-control" name="remark" rows="3">{{$userRequest->remark}}</textarea>
            </div>
            <div class="form-group">
                <label>{{__("Precautionary statement")}}</label>
                <textarea class="form-control" name="precautionary_statement" rows="3">{{$userRequest->precautionary_statement}}</textarea>
            </div>
            <div class="ctr">
                <a class="btn btn-cancel btn-square" href="{{route('shop.applicationList', ['user_id' => $userRequest->user->id])}}">{{__("Cancel")}}</a>
                <input type="submit" class="btn btn-submit btn-square" value="{{__("Submit")}}">
            </div>
        </form>
    </div>
@stop
