
    <table class="table table-borderless">
        <thead>
        <tr>
            <th scope="col">{{__("Row Num")}}</th>
            <th scope="col">{{__("Request Type")}}</th>
            <th scope="col">{{__("Group ID")}}</th>
            <th scope="col">{{__("Group name")}}</th>
            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Member view")}}</th>
            <th scope="col">{{__("Responsible person")}}</th>
        </tr>
        </thead>
    @if ( $userRequest->talk_group_requests->count() > 0)
        <tbody>
        @foreach($userRequest->talk_group_requests as $group)
            <tr>
                <td>{{$group->row_num}}</td>
                <td>{{$group->request_type}}</td>
                <td>{{$group->voip_group_id}}</td>
                <td>{{$group->name}}</td>
                <td>{{__($group->priority)}}</td>
                <td>{{__($group->member_view)}}</td>
                <td>{{$group->group_responsible_person}}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
    </table>
<!--
    <div class="mt-3">{{__("Talk groups not found")}}</div>
-->

