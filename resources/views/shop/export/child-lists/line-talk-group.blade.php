@if ( $userRequest->line_talk_group_requests->count() > 0)
    <table class="list">
        <thead>
        <tr>
            <th scope="col">{{__("Line number")}}</th>
            <th scope="col">{{__("Id name")}}</th>
            <th scope="col">{{__("Main Group")}}</th>
            <th scope="col">{{__("Group select 1")}}</th>
            <th scope="col">{{__("Group select 2")}}</th>
            <th scope="col">{{__("Group select 3")}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($userRequest->line_talk_group_requests as $key => $lineGroup)
                <tr>
                    <td>{{substr($lineGroup->line_num, -4)}}</td>
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
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="mt-3">{{__("No line talk group is requested")}}</div>
@endif
