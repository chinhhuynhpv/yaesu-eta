
<table class="table table-borderless" data-delete-action="{{route("shop.handleTalkGroupDelete")}}">
    <thead>
    <tr>
        <th scope="col">{{__("Row Num")}}</th>
        <th scope="col">{{__("Line ID")}}</th>
        <th scope="col">{{__("Line number")}}</th>
        <th scope="col">{{__("Main Group name")}}</th>
    <!--<th scope="col">{{__("Group owner")}}</th>-->
        <th scope="col">{{__("Group select 1")}}</th>
        <th scope="col">{{__("Group select 2")}}</th>
        <th scope="col">{{__("Group select 3")}}</th>
        <th scope="col">{{__("Group select more")}}</th>
        <th scope="col">{{__("Action")}}</th>
    </tr>
    </thead>
    @if ( $userRequest->line_talk_group_requests->count() > 0)
        <tbody>
        @foreach ($userRequest->line_talk_group_requests as $lineGroup)
            <tr>
                <td>{{$lineGroup->row_num}}</td>
                <td>{{$lineGroup->line_id}}</td>
                <td>{{$lineGroup->line_num}}</td>
                <td>{{$lineGroup->name}}</td>
            <!--
                    <td>
                        {{$lineGroup->group_name}}
                </td>
-->
                <td>
                    @if ($lineGroup->additional_groups->count() > 0)
                        {{$lineGroup->additional_groups[0]->group_name}}
                    @endif
                </td>
                <td>
                    @if ($lineGroup->additional_groups->count() > 1)
                        {{$lineGroup->additional_groups[1]->group_name}}
                    @endif
                </td>
                <td>
                    @if ($lineGroup->additional_groups->count() > 2)
                        {{$lineGroup->additional_groups[2]->group_name}}
                    @endif
                </td>
                <td>
                    @if ($lineGroup->additional_groups->count() > 3)
                        @foreach ($lineGroup->additional_groups as $reqAdd)
                            @if ($loop->index > 2)
                                {{$reqAdd->group_name}} <br>
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @if ($status != '2')
                        <a class="btn btn-update"
                           href="{{route("shop.line.talk.group.update", ['id' => $lineGroup->id])}}">{{__("Update")}}</a>
                        <a class="btn btn-delete" data-id="{{$lineGroup->id}}" data-name="{{$lineGroup->name}}"
                           data-delete>{{__("Delete")}}</a>
                    @else
                        <button class="btn btn-update" disabled>{{__("Update")}}</button>
                        <button class="btn btn-delete" disabled>{{__("Delete")}}</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>
<div class="mt-3 rht">
    @if ($status != '2')
        <a class="btn btn-add mr-2"
           href="{{route('shop.line.talk.group.add')}}?user_id={{$userRequest->user_id}}&&request_id={{$userRequest->id}}">{{__("Add New")}}</a>
    @else
        <button class="btn btn-add" disabled>{{__("Add New")}}</button>
    @endif
</div>
