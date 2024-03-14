@extends('operator.body')

@section('container')
    <div>
        <div class="mt-3">
            <h2 class="text-center">{{__("Application List")}}</h2>
        </div>

        <div class="mt-3">
            <form method="get" action="{{route('admin.applicationList')}}" class="framed">
                <div class="row mt-3">
                    @php $request_number = request()->get('request_number'); @endphp
                    <div class="col-md-5">
                        <label>{{__("Request number")}}</label>
                        <input class="form-control" name="request_number" value="{{$request_number}}">
                    </div>
                </div>
                <div class="row mt-3">
                    @php $shop_id = request()->query('shop_id'); @endphp
                    <div class="col-md-5">
                        <label>{{__("Shop name")}}</label>
                        <select class="form-control" name="shop_id">
                            <option value="">{{__("All")}}</option>
                            @foreach($shops as $shop)
                                <option value="{{$shop->id}}" {{$shop_id == $shop->id ? 'selected' : ''}}>{{$shop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @php $contract_name = request()->get('contract_name'); @endphp
                    <div class="col-md-5">
                        <label>{{__("Contractor name")}}</label>
                        <input class="form-control" name="contract_name" value="{{$contract_name}}">
                    </div>
                    <div class="flex-rvs col-auto">
                        <input type="submit" class="btn btn-primary" value="{{__("Search")}}"/>
                    </div>
                </div>

            </form>
        </div>

            <table class="table table-borderless list list-m">
                <thead>
                <tr>
                    <th scope="col">{{__("Request number")}}</th>
                    <th scope="col">{{__("Shop name")}}</th>
                    <th scope="col">{{__("Contractor name")}}</th>
                    <th scope="col">{{__("Application date")}}</th>
                    <th scope="col">{{__("Application type addition")}}</th>
                    <th scope="col">{{__("Application type change")}}</th>
                    <th scope="col">{{__("Application type pause")}}</th>
                    <th scope="col">{{__("Application type discontinued")}}</th>
                    <th scope="col">{{__("Action")}}</th>
                </tr>
                </thead>
            @if ($userRequests->count() > 0)
                <tbody>
                @foreach($userRequests as $key => $request)
                    <tr>
                        <td>{{$request->request_number}}</td>
                        <td>{{$request->shop_name}}</td>
                        <td>{{$request->contract_name}}</td>
                        <td>{{$request->request_date}}</td>
                        <td>{{$request->getBooleanColumn('add_flg')}}</td>
                        <td>{{$request->getBooleanColumn('modify_flg')}}</td>
                        <td>{{$request->getBooleanColumn('pause_restart_flg')}}</td>
                        <td>{{$request->getBooleanColumn('discontinued_flg')}}</td>
                        <td>
                            <a class="btn btn-action" href="{{route("admin.applicationDetail", ['id' => $request->id])}}">{{__("View")}}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @endif
            </table>
            {{$userRequests->links()}}

        <!--
            <div class="mt-3">{{__("Applications not found.")}}</div>
        -->

    </div>
@stop
