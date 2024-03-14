<h3 class="real mt-3">{{__("Real Talk Groups")}}</h3>

    <table class="table table-borderless list list-m" data-delete-action="">
        <thead>
        <tr>
            <th scope="col">{{__("Shop name")}}</th>
            <th scope="col">{{__("Customer name")}}</th>

            <th scope="col">{{__("VOIP Group ID")}}</th>
            <th scope="col">{{__("Group name")}}</th>
            <!--
            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Member view")}}</th>
            <th scope="col">{{__("Responsible person")}}</th>
-->
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>

    @if ( $talkGroups->count() > 0)
        <tbody>
        @foreach($talkGroups as $key => $group)
            <tr class='bg-light'>
                <td>{{$group->shop_name}}</td>
                <td>{{$group->contract_name}}</td>
                <td>{{$group->voip_group_id}}</td>
                <td>{{$group->name}}</td>
                <!--
                <td>{{__($group->priority)}}</td>
                <td>{{__($group->member_view)}}</td>
                <td>{{$group->group_responsible_person}}</td>
                -->
                <td>
                    @if(empty($userId))
                        <a class="btn btn-update" href="{{Route('operator.talkGroupUpdate',['id'=>$group->id])}}">{{__("Update")}}</a>
                    @else
                        <a class="btn btn-update" href="{{Route('operator.talkGroupUpdate',['id'=>$group->id, 'user_id'=> $userId])}}">{{__("Update")}}</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @endif
    </table>
<!--
    <div class="mt-3">{{__("Setting talk groups not found.")}}</div>
-->

