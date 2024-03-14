<h3 class="mt-3">{{__("Setting Talk Group")}}</h3>

    <table class="table table-borderless" data-delete-action="{{route("shop.talk.group.delete")}}">
        <thead>
        <tr>
            <th scope="col">{{__("Request Type")}}</th>
            <th scope="col">{{__("VOIP Group ID")}}</th>
            <th scope="col">{{__("Group name")}}</th>
            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Member view")}}</th>
            <th scope="col">{{__("Responsible person")}}</th>
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
@if ( $talkGroups->count() > 0)
        <tbody>
        @foreach($talkGroups as $key => $group)
            <tr>
                <td>{{$group->request_type}}</td>
                <td>{{$group->voip_group_id}}</td>
                <td>{{$group->name}}</td>
                <td>{{__($group->priority)}}</td>
                <td>{{__($group->member_view)}}</td>
                <td>{{$group->group_responsible_person}}</td>
                <td>
                    <a class="btn btn-detail" href="{{route('shop.talk.group.detail')}}?id={{$group->id}}">{{__("View Detail")}}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
@endif
    </table>
<!--
    <div class="mt-3">{{__("TalkGroups not found.")}}</div>
-->

