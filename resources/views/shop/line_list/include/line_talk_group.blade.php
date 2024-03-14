<h3 class="mt-3">{{__("Line Talk Group")}}</h3>

    <table class="table table-borderless" data-action="/">
        <thead>
        <tr>
            <th scope="col">{{__("Line ID")}}</th>
            <th scope="col">{{__("Line Num")}}</th>
            <th scope="col">{{__("Voip Id Name")}}</th>
            <th scope="col">{{__("Main Group Name")}}</th>
            <!--th scope="col">{{__("Number")}}</th>-->
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
@if ( $lineTalkGroups->count() > 0)
        <tbody>
        @foreach($lineTalkGroups as $key => $mainLineTalkGroup)
            <tr>
                <td>{{$mainLineTalkGroup->voip_line_id}}</td>
                <td>{{$mainLineTalkGroup->line_num}}</td>
                <td>{{$mainLineTalkGroup->voip_id_name}}</td>
                <td>
                    {{$mainLineTalkGroup->name}}
                </td>
                <!--
                <td>
                    {{$mainLineTalkGroup->number}}
                </td>
-->
                <td>
                    <a class="btn btn-detail"  href="{{Route('shop.line.talk.detail')}}?id={{$mainLineTalkGroup->ilt}}">{{__("View Detail")}}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
@endif
    </table>
<!--
    <div class="mt-3">{{__("Line TalkGroups not found.")}}</div>
-->
