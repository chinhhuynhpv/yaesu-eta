@if ( $userRequest->line_talk_group_requests->count() > 0)
    <table class="table table-borderless">
        <thead>
        <tr>
            <th scope="col">{{__("Row Num")}}</th>
            <th scope="col">{{__("Line ID")}}</th>
            <th scope="col">{{__("Line num")}}</th>
            <th scope="col">{{__("Id name")}}</th>
            <th scope="col">{{__("Group owner")}}</th>
            <th scope="col">{{__("Group select 1")}}</th>
            <th scope="col">{{__("Group select 2")}}</th>
            <th scope="col">{{__("Group select 3")}}</th>
            <th scope="col">{{__("Group select more")}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($userRequest->line_talk_group_requests as $lineGroup)
                <tr>
                    <td>{{$lineGroup->row_num}}</td>
                    <td>{{$lineGroup->voip_line_id}}</td>
                    <td>{{$lineGroup->line_num}}</td>
                    <td>{{$lineGroup->name}}</td>
                    <td>
                        {{$lineGroup->group_name}}
                    </td>
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
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="mt-3">{{__("該当の契約者は見つかりませんでした。")}}</div>
@endif
