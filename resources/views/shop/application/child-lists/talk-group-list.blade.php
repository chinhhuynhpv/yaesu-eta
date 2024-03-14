
    <table class="table table-borderless" data-delete-action="{{route("shop.talk.group.delete")}}">
        <thead>
        <tr>
            <th scope="col">{{__("Row Num")}}</th>
            <th scope="col">{{__("Request Type")}}</th>
            <th scope="col">{{__("VOIP Group ID")}}</th>
            <th scope="col">{{__("Group name")}}</th>
            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Member view")}}</th>
            <th scope="col">{{__("Responsible person")}}</th>
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
    @if ( $userRequest->talk_group_requests->count() > 0)
        <tbody>
        @php $addedGroupIds = $userRequest->getGroupIdsAddedToLine(); @endphp
        @foreach($userRequest->talk_group_requests as $group)
            <tr>
                <td>{{$group->row_num}}</td>
                <td>{{__($group->request_type)}}</td>
                <td>{{$group->voip_group_id}}</td>
                <td>{{$group->name}}</td>
                <td>{{__($group->priority)}}</td>
                <td>{{__($group->member_view)}}</td>
                <td>{{$group->group_responsible_person}}</td>
                <td>
                    @if ($status != '2')
                        <a class="btn btn-update"
                           href="{{route('shop.talk.group.update', ['id' => $group->id])}}">{{__("Update")}}</a>
                        @if (in_array($group->voip_group_id, $addedGroupIds))
                            <button class="btn btn-delete" disabled>{{__("Delete")}}</button>
                        @else
                            <a class="btn btn-delete" data-name="{{$group->name}}" data-id="{{$group->id}}"
                               data-delete>{{__("Delete")}}</a>
                        @endif
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
           href="{{route('shop.talk.group.add', ['request_id' => $userRequest->id, 'user_id' => $userRequest->user_id])}}">{{__("Add New")}}</a>
        <a class="btn btn-exist"
           href="{{route("shop.talk.group.list", ['request_id' => $userRequest->id])}}">{{__("Add Exist")}}</a>
    @else
        <button class="btn btn-add mr-2" disabled>{{__("Add New")}}</button>
        <button class="btn btn-exist" disabled>{{__("Add Exist")}}</button>
    @endif
</div>
