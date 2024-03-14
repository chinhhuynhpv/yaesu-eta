@extends('operator.body')

@section('container')
    @php $shop = $userRequest->shop; @endphp
    @php $user = $userRequest->user; @endphp

    <div id="app_detali_box" class="mt-3 mb-3">
        <div><h2 class="text-center">{{__("Application Detail")}}</h2></div>
        <div class="row mt-3">
            <div class="col-md-4">
                お客様名：{{$userRequest->shop->owner->name}}
            </div>
            <div class="col-md-4">
                代理店名：{{$userRequest->user->contract_name}}
            </div>
            <div class="col-md-4">
                <div><span>{{__("Application number")}}: </span><span>{{$userRequest->request_number}}</span></div>
                <div><span>{{__("Application date")}}: </span><span>{{$userRequest->request_date}}</span></div>
            </div>
        </div>
        
        <h3 class="mt-3">{{__("Setting Talk Group")}}</h3>
        @include('operator.application.child-lists.talk-group-list')
        
        <h3 class="mt-3">{{__("Setting line ID")}}</h3>
        @include('operator.application.child-lists.line-id-list')
        
        <h3 class="mt-3">{{__("Setting talk group of line ID")}}</h3>
        @include('operator.application.child-lists.line-talk-group')

        <form method="post" action="{{route("admin.applicationHandleEdit")}}">
            @method('PUT')
            @csrf
            <input name="id" type="hidden" value="{{$userRequest->id}}">
            <div class="form-group">
                <label>{{__("Remarks")}}</label>
                <textarea class="form-control" name="remark" rows="3">{{$userRequest->remark}}</textarea>
            </div>
            <div class="form-group">
                <label>{{__("Precautionary statement")}}</label>
                <textarea class="form-control" name="precautionary_statement" rows="3">{{$userRequest->precautionary_statement}}</textarea>
            </div>
            <div class="submit-buttons">
                <input type="button" class="btn btn-square btn-sqr2" value="{{__('Cancel')}}" onclick="history.back()">
                <input class="btn btn-delete btn-square btn-sqr2" name=submit[dec] type="submit" value="{{__('Application remand')}}">
                <input class="btn btn-primary btn-square btn-sqr2" type="button" value="{{__('Reflect settings')}}" data-btn-conf>
                <input class="btn btn-create btn-square btn-sqr2" name=submit[complete] type="submit" value="{{__('Complete settings')}}">
            </div>
            <div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" data-confirm-approve-modal>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__("Confirmation")}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div data-title>{{__("Are you sure that you want to approve request?")}}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-squrare" data-dismiss="modal">{{__("Close")}}</button>
                            <input name="submit[reflect]" type="submit" class="btn btn-submit" value="{{__("Confirm")}}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('script')
    <script>
        $('[data-btn-conf]').click(function (ev) {
            ev.preventDefault();
            $('[data-confirm-approve-modal]').modal('show');
        })
    </script>
@append
