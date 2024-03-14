@extends('shop.body')

@section('container')
    <div class="">

                <h2>{{__("Exist line list")}}</h2>
            </div>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        <div class="list-talk-group p-4">
            <table class="table list-talk-group">
                <thead>
                    <tr>
                        <th scope="col" class='text-wrap w-5'>{{__("id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Request Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Shop Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Line Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Terminal Num")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Name")}}</th>
<!--
                        <th scope="col" class='text-wrap w-5'>{{__("Call Type")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Priority")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Individual")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Recordingl")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Gps")}}</th>
-->
                        <th scope="col" class='text-wrap w-5'>{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $listLines as $item)
                        <tr>
                            <th scope="row"> <a href="{{Route('shop.talk.group.detail')}}?id={{$item->id}}">{{$item->id}}</a></th>
                            <td class='text-wrap w-5'>{{$item->request_id}}</td>
                            <td class='text-wrap w-5'>{{$item->shop_id}}</td>
                            <td class='text-wrap w-5'>{{$item->voip_line_id}}</td>
                            <td class='text-wrap w-5'>{{$item->line_num}}</td>
                            <td class='text-wrap w-5'>{{$item->voip_id_name}}</td>
<!--
                            <td class='text-wrap w-5'>{{$item->call_type}}</td>
                            <td class='text-wrap w-5'>{{$item->priority}}</td>
                            <td class='text-wrap w-5'>{{$item->individual}}</td>
                            <td class='text-wrap w-5'>{{$item->recording}}</td>
                            <td class='text-wrap w-5'>{{$item->gps}}</td>
-->
                            <td class='text-wrap w-5'>
                                @if ($userRequest && !in_array($item->voip_line_id, $customAddLines))
                                    <a class="btn btn-create" data-id="{{$item->id}}" data-add-existed-line>{{__("Add to the app")}}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{ $listLines->links() }}
                </tbody>
            </table>
        </div>
    </div>
    @if (!empty($userRequest))
        <form method="post" action="{{route('shop.handleAddExistedLine')}}" data-handle-form>
            @csrf
            <input name="id" type="hidden">
            <input name="request_id" type="hidden" value="{{$userRequest->id}}">
        </form>
    @endif
@stop

@section('script')
    @if (!empty($userRequest))
        <script>
            $('[data-add-existed-line]').click(function(e) {
                const $this = $(this);
                const id = $this.data('id');

                const $form = $('[data-handle-form]');
                $form.find('input[name="id"]').val(id);
                $form.submit();
            })
        </script>
    @endif
@stop
