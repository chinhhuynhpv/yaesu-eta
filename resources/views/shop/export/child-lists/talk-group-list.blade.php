@if ( $userRequest->talk_group_requests->count() > 0)
    <table class="list">
        <thead>
            <tr>
                <th scope="col">{{__("VOIP Group ID")}}</th>
                <th scope="col">{{__("Group name")}}</th>
                <th scope="col">{{__("Priority")}}</th>
                <th scope="col">{{__("Member display")}}</th>
                <th scope="col">{{__("Group manager")}}</th>
                <th scope="col" style="min-width:14%">......</th>
                <th scope="col" style="min-width:14%">......</th>
            </tr>
        </thead>
        <tbody>
        @foreach($userRequest->talk_group_requests as $key => $group)
            <tr>
                <td>{{$group->voip_group_id}}</td>
                <td>{{$group->name}}</td>
                <td>{{__($group->priority)}}</td>
                <td>{{__($group->member_view)}}</td>
                <td>{{$group->group_responsible_person}}</td>
                <td class="td-width-right" style="min-width:14%"></td>
                <td class="td-width-right" style="min-width:14%"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="mt-3">{{__("No talk group is requested")}}</div>
@endif
