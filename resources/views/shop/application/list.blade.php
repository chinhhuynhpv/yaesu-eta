@extends('shop.body')

@section('container')
    <div>
        <div>
            <h2>{{__('message.application_list')}}</h2>
        </div>
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>

        <div class="mt-3">
            <form method="get" action="{{route('shop.applicationList', ['user' => $user->id])}}" class="framed">
                <div class="row">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="col-md-4">
                        <label>{{__("Request number")}}</label>
                        <input class="form-control" name="s" value="{{request()->get('s')}}">
                    </div>
                    <div class="col-md-2">
                        <label>{{__("Status")}}</label>
                        <select name="status" class="form-control">
                            @php $status = trim(strtolower((string) request()->get('status'))); @endphp
                            <option value="">{{__('all')}}</option>
                            @foreach ($possibleStatuses as $key => $pStatus)
                                <option value="{{$pStatus}}" {{$status == $pStatus ? 'selected' : ''}}>{{__($pStatus)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-rvs">
                        <input type="submit" class="btn btn-primary" value="{{__('message.search')}} "/>
                    </div>
                </div>
            </form>
        </div>
        <div class="rht mt-3">
            <div class="">
                @if ($countApplicationsInProccessing)
                    <button class="btn btn-create" disabled>{{__('message.create')}}</button>
                @else
                    <a class="btn btn-create" href="{{route("shop.applicationInput", ['user_id' => $user->id])}}">{{__('message.create')}}</a>
                @endif
            </div>
        </div>
        @if ($userRequests->count() > 0)
            <table class="table table-borderless">
                <thead>
                <tr>
                    <th scope="col">{{__("Request number")}}</th>
                    <th scope="col">{{__("Status")}}</th>
                    <th scope="col">{{__("Application type addition")}}</th>
                    <th scope="col">{{__("Application type change")}}</th>
                    <th scope="col">{{__("Application type pause")}}</th>
                    <th scope="col">{{__("Application type discontinued")}}</th>
                    <th scope="col">{{__("Action")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userRequests as $key => $request)
                    <tr>
                        <td>{{$request->request_number}}</td>
                        <td>{{$request->status}}</td>
                        <td>{{$request->getBooleanColumn('add_flg')}}</td>
                        <td>{{$request->getBooleanColumn('modify_flg')}}</td>
                        <td>{{$request->getBooleanColumn('pause_restart_flg')}}</td>
                        <td>{{$request->getBooleanColumn('discontinued_flg')}}</td>
                        <td>
                            @if ($request->getRawValue('status') != '2')
                                <a class="btn btn-update" href="{{route("shop.applicationEdit", ['id' => $request->id])}}">{{__("Change")}}</a>
                            @endif
                            <a class="btn btn-update" href="{{route("shop.applicationDetail", ['id' => $request->id])}}">{{__("Setting")}}</a>
                            <a class="btn btn-action" href="{{route("shop.exportRegisterDocument", ['id' => $request->id])}}">{{__("Register Document")}}</a>
                            <a class="btn btn-action" href="{{route("shop.exportSettingDocument", ['id' => $request->id])}}">{{__("Setting Document")}}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        <!--
            <div class="text-right">
                @if ($countApplicationsInProccessing)
                    <button class="btn btn-create" disabled>{{__('message.create')}}</button>
                @else
                    <a class="btn btn-create" href="{{route("shop.applicationInput", ['user_id' => $user->id])}}">{{__('message.create')}}</a>
                @endif
            </div>
-->
            {{$userRequests->links()}}
        @else
            <div class="mt-3">{{__("No application was found")}}</div>
        @endif
        <div class="lft"><a class="btn btn-normal" href="{{route("shop.userList")}}">{{__('message.back')}}</a></div>
    </div>
@stop
