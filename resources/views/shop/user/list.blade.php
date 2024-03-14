@extends('shop.body')

@section('container')
@php $user = $userRequest->user; @endphp

    <div class="">

        <div>
            <h2>{{__('Exist Talk List')}}</h2>
        </div>
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>

        <div class="list-talk-group p-4">
            <table class="table list-talk-group">
                <thead class='bg-light p-3'>
                    <tr>
                        <th scope="col">{{__("id")}}</th>
                        <th scope="col">{{__("VOIP Group ID")}}</th>
                        <th scope="col">{{__("Group name")}}</th>
                        <th scope="col">{{__("Priority")}}</th>
                        <th scope="col">{{__("Member display")}}</th>
                        <th scope="col">{{__("Group Manager")}}</th>
                        <th scope="col">{{__("Status")}}</th>
                        <!--
                        <th scope="col">{{__("Date of creation")}}</th>
                        <th scope="col">{{__("Update date")}}</th>
-->
                        <th scope="col">{{__("View detail")}}</th>
                    </tr>
                </thead>
                <tbody>
                 
                    @foreach( $listTalkGroup as $item)
                        <tr>
                            <td scope="row"> <a href="{{Route('shop.talk.group.detail')}}?id={{$item->id}}">{{$item->id}} </a> </td>
                            <td>{{$item->voip_group_id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->priority}}</td>
                            <td>{{$item->member_view}}</td>
                            <td>{{$item->group_responsible_person}}</td>
                            <td>{{$item->status}}</td>
                            <!--
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
-->
                            <td>
                                <a class="btn btn-detail" href="{{Route('shop.talk.group.detail')}}?id={{$item->id}}">{{__("View detail")}}</a>

                                @if (!empty($userRequest) && !in_array($item->voip_group_id, $customAddedGroups))
                                    <a class="btn btn-add" data-id="{{$item->id}}" data-add-existed-group>{{__("Add to app")}}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{ $listTalkGroup->links() }}
                </tbody>
            </table>
        </div>
    </div>
    @if (!empty($userRequest))
        <form method="post" action="{{route('shop.handleAddExistedGroup')}}" data-handle-form>
            @csrf
            <input name="id" type="hidden">
            <input name="request_id" type="hidden" value="{{$userRequest->id}}">
        </form>
    @endif
@stop

@section('script')
    @if (!empty($userRequest))
        <script>
            $('[data-add-existed-group]').click(function(e) {
                const $this = $(this);
                const id = $this.data('id');

                const $form = $('[data-handle-form]');
                $form.find('input[name="id"]').val(id);
                $form.submit();
            })
        </script>
    @endif
@stop
