<h3 class="mt-3">{{__("Setting Line")}}</h3>

    <table class="table table-borderless" data-delete-action="{{route('shop.line.delete')}}">
        <thead>
        <tr>
            <th scope="col">{{__("Request Type")}}</th>
            <th scope="col">{{__("Line ID")}}</th>
            <th scope="col">{{__("Line number")}}</th>
            <th scope="col">{{__("Voip Id Name")}}</th>
            <th scope="col">{{__("Call method")}}</th>
            <th scope="col">{{__("Priority")}}</th>
            <th scope="col">{{__("Individual communication")}}</th>
            <th scope="col">{{__("GPS")}}</th>
            <th scope="col">{{__("Recording")}}</th>
            <th scope="col">{{__("Action")}}</th>
        </tr>
        </thead>
@if ( $lines->count() > 0)
        <tbody>
        @foreach( $lines as $lineID)
            <tr>
                <td>{{$lineID->request_type}}</td>
                <td>{{$lineID->voip_line_id}}</td>
                <td>{{$lineID->line_num}}</td>
                <td>{{$lineID->voip_id_name}}</td>
                <td>{{__($lineID->call_type)}}</td>
                <td>{{__($lineID->priority)}}</td>
                <td>{{__($lineID->individual)}}</td>
                <td>{{__($lineID->gps)}}</td>
                <td>{{__($lineID->recording)}}</td>
                <td>
                    <a class="btn btn-detail" href="{{route("shop.user.line.list.detail", ['id' => $lineID->id])}}">{{__("View Detail")}}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
@endif
    </table>
<!--
    <div class="mt-3">{{__("Lines not found.")}}</div>
-->

