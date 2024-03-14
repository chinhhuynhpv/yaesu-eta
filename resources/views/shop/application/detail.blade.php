@extends('shop.body')

@section('container')
    @php $user = $userRequest->user; @endphp
    @php $status = $userRequest->getRawValue('status'); @endphp
    <div class="mt-3 mb-3" id="app_detali_box">
        <div>
            <h2>{{__('Application Detail')}}</h2>
        </div>
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>

        <div class="button-box">
            @if($status != '2')
                <a class="btn btn-add" href="{{route('shop.talk.group.add', ['request_id' => $userRequest->id, 'user_id' => $userRequest->user_id])}}">{{__("Add New Talk Group")}}</a>
                <a class="btn btn-exist" href="{{route("shop.talk.group.list", ['request_id' => $userRequest->id])}}">{{__("Add Existed Talk Group")}}</a>
                <a class="btn btn-add" href="{{Route('shop.line.add')}}?user_id={{$userRequest->user_id}}&&request_id={{$userRequest->id}}">{{__("Add New Line ID")}}</a>
                <a class="btn btn-exist" href="{{route("shop.existedList", ['request_id' => $userRequest->id])}}">{{__("Add Existed Line")}}</a>
                <a class="btn btn-add" href="{{route('shop.line.talk.group.add')}}?user_id={{$userRequest->user_id}}&&request_id={{$userRequest->id}}">{{__("Setting Group Of Line ID")}}</a>
            @else
                <button class="btn btn-add" disabled>{{__("Add New Talk Group")}}</button>
                <button class="btn btn-add" disabled>{{__("Add Existed Talk Group")}}</button>
                <button class="btn btn-add" disabled>{{__("Add New Line ID")}}</button>
                <button class="btn btn-add" disabled>{{__("Add Existed Line")}}</button>
                <button class="btn btn-setting" disabled>{{__("Setting Group Of Line ID")}}</button>
            @endif
        </div>
        
        <h3 class="mt-3">{{__("Setting Talk Group")}}</h3>
        @include('shop.application.child-lists.talk-group-list')

        <h3 class="mt-3">{{__("Setting line ID")}}</h3>
        @include('shop.application.child-lists.line-id-list')

        <h3 class="mt-3">{{__("Setting talk group of line ID")}}</h3>
        @include('shop.application.child-lists.line-talk-group')

        <form method="post" action="{{route("shop.handleApplicationEdit")}}">
            @method('PUT')
            @csrf
            <input name="id" type="hidden" value="{{$userRequest->id}}">
            <div class="form-group">
                <label>{{__("Reference")}}</label>
                <textarea class="form-control" name="precautionary_statement" rows="3">{{$userRequest->precautionary_statement}}</textarea>
            </div>
            <div class="text-center">
                <input type="button" class="btn btn-square" value="{{__('Cancel')}}" onclick="history.back()">
                <input class="btn btn-save" name=submit[temp] type="submit" value="{{__("Temporary save")}}" {{$status == '2' || $status == '3' ? 'disabled' : ''}}>
                <input class="btn btn-applicate" name=submit[app] type="submit" value="{{__("Applicate")}}">
            </div>
        </form>
    </div>
    @include('shop.application.modals.delele-modal')
@stop

@section('script')
    <script>
        $('[data-delete]').click(function(e) {
            const $this = $(this);
            const action = $this.closest('table').data('delete-action');
            const id = $this.data('id');
            const name = $this.data('name');

            const $modal = $('[data-delete-modal]');
            $modal.find('form').attr('action', action);
            $modal.find('input[name="id"]').val(id);

            const $title = $modal.find('[data-title]');
            $title.text($title.text().replace(':name', name));

            $('[data-delete-modal]').modal('show');
        })
    </script>
@stop
