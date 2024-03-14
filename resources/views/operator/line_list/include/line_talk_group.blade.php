<h3 class='real mt-3'>{{__("Real Lines and Talk Groups")}}</h3>

    <table class="table table-borderless list list-m" data-action="">
        <thead>
        <tr>
            <th scope="col">{{__("Shop name")}}</th>
            <th scope="col">{{__("Customer name")}}</th>
            <th scope="col">{{__("Line Id")}}</th>
            <th scope="col">{{__("line Num")}}</th>
            <th scope="col">{{__("Voip Id Name")}}</th>
            <!--
            <th scope="col">{{__("Group Owner")}}</th>
            <th scope="col">{{__("Number")}}</th>
            -->
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
    @if ( $lineTalkGroups->count() > 0)
        <tbody>
        @foreach($lineTalkGroups as $key => $lineID)
            <tr class='bg-light'>
                <td>{{$lineID->shop_name}}</td>
                <td>{{$lineID->contract_name}}</td>
                <td>{{$lineID->voip_line_id}}</td>
                <td>{{$lineID->line_num}}</td>
                <td>{{$lineID->voip_id_name}}</td>
                <!--
                <td>{{$lineID->name}}</td>
                <td>{{$lineID->number}}</td>
-->
                <td>
                    @if(empty($userId))
                        <a class="btn btn-update"  href="{{Route('operator.lineTalkGroupUpdate', ['id'=>$lineID->ilt])}}">{{__("Update")}}</a>
                    @else
                        <a class="btn btn-update"  href="{{Route('operator.lineTalkGroupUpdate', ['id'=>$lineID->ilt, 'user_id'=>$userId])}}">{{__("Update")}}</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @endif
    </table>
<!--
    <div class="mt-3">{{__("Tack groups not found.")}}</div>
-->
