@extends('shop.body')

@section('container')
    <div>
        <div class="mt-3 text-center">
            <h3>{{__("Customer List")}}</h3>
        </div>
        <div class="mt-3 rht">
            <a class="btn btn-create" href="{{route("shop.userInput")}}">{{__("Create")}}</a>
        </div>
        <form method="get" action="{{route('shop.userList')}}" class="framed">
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <label>{{__("Customer id")}}</label>
                        <input class="form-control" name="contractor_id" value="{{request()->get('contractor_id')}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="">
                        <label>{{__("Customer name")}}</label>
                        <input class="form-control" name="contract_name" value="{{request()->get('contract_name')}}">
                    </div>
                </div>
                <div class="flex-rvs">
                    <input type="submit" class="btn btn-primary" value="{{__("Search")}}"/>
                </div>
            </div>
        </form>

            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">{{__("Customer id")}}</th>
                        <th scope="col">{{__("Register Date")}}</th>
                        <th scope="col">{{__("Customer name")}}</th>
                        <th scope="col">{{__("Customer name kana")}}</th>
                        <th scope="col">{{__("Action")}}</th>
                    </tr>
                </thead>
@if ($users->count() > 0)
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{$user->contractor_id}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->contract_name}}</td>
                            <td>{{$user->contract_name_kana}}</td>
                            <td>
                                <a class="btn btn-normal" href="{{route("shop.userDetail", ['id' => $user->id])}}">{{__("Detail")}}</a>
                                <a class="btn btn-update" href="{{route("shop.userEdit", ['id' => $user->id])}}">{{__("Update")}}</a>
                                <a class="btn btn-normal" href="{{route("shop.applicationList", ['user_id' => $user->id])}}">{{__("App list")}}</a>
                                <a class="btn btn-normal" href="{{route("shop.talk.line.list", ['user_id' => $user->id])}}">{{__("Line List")}}</a>
                                <a class="btn btn-light" href="{{route("shop.registerUpload", ['user_id' => $user->id])}}">{{__("Upload Document")}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
@else
<tbody>
<div class="mt-3">{{__("Customers not found.")}}</div>
</tbody>
@endif
            </table>
            {{ $users->links() }}

    </div>
@stop
