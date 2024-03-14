<h3 class="real mt-3">{{__("Real Lines")}}</h3>

    <table class="table table-borderless list list-m" data-delete-action="">
        <thead>
        <tr>
        <th scope="col">{{__("Shop name")}}</th>
            <th scope="col">{{__("Customer name")}}</th>
            <th scope="col">{{__("Line ID")}}</th>
            <th scope="col">{{__("Line number")}}</th>
            <th scope="col">{{__("Id name")}}</th>
        <!--
            <th scope="col">{{__("Call method")}}</th>

            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Individual communication")}}</th>
            <th scope="col">{{__("GPS")}}</th>
            <th scope="col">{{__("Recording")}}</th>
        -->
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
    @if ( $lines->count() > 0)
        <tbody>
        @foreach( $lines as $key => $lineID)
            <tr>
                <td>{{$lineID->shop_name}}</td>
                <td>{{$lineID->contract_name}}</td>
                <td>{{$lineID->voip_line_id}}</td>
                <td>{{$lineID->line_num}}</td>
                <td>{{$lineID->voip_id_name}}</td>
            <!--
                <td>{{__($lineID->call_type)}}</td>

                <td>{{$lineID->priority}}</td>
                <td>{{$lineID->individual}}</td>
                <td>{{$lineID->recording}}</td>
                <td>{{$lineID->gps}}</td>
            -->
                <td>
                    @if(empty($userId))
                        <a class="btn btn-update" href="{{route('operator.lineUpdate',['id'=>$lineID->id])}}">{{__("Update")}}</a>
                    @else
                        <a class="btn btn-update" href="{{route('operator.lineUpdate',['id'=>$lineID->id, 'user_id'=>$userId])}}">{{__("Update")}}</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @endif
    </table>

<!--
    <div class="mt-3">{{__("Lines not found")}}</div>
-->

