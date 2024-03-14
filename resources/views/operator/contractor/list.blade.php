@extends('operator.body')

@section('container')
    <div>
        <div class="mt-3">
            <h2>{{__("Contractor List")}}</h2>
        </div>
        <form method="get" action="{{route('admin.userList')}}" class="framed">
            @php
               $req = request();
               $shopId = $req->query('shop_id');
               $contractorId = $req->query('contractor_id');
               $contractorName = $req->query('contract_name');
               $registrationDate = $req->query('registration_date');
               $representativeName = $req->query('representative_name');
            @endphp
            <div class="mt-3">
                <div class="row">
                   <div class="col-md-6">
                       <div class="row">
                           <div class="col-md-4"><label>{{__("Shop")}}</label></div>
                           <div class="col-md-8">
                               <select class="form-control" name="shop_id">
                                   <option value="">{{__("All")}}</option>
                                   @foreach($shops as $shop)
                                       <option value="{{$shop->id}}" {{$shopId == $shop->id ? 'selected' : ''}}>{{$shop->name}}</option>
                                   @endforeach
                               </select>
                           </div>
                       </div>
                   </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4"><label>{{__("Contractor id")}}</label></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="contractor_id" value="{{$contractorId}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4"><label>{{__("Contractor name")}}</label></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="contract_name" value="{{$contractorName}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4"><label>{{__("Registration date")}}</label></div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="registration_date" value="{{$registrationDate}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4"><label>{{__("Representative's name")}}</label></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="representative_name" value="{{$representativeName}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-right">
                    <input type="reset" value="{{__("Clear")}}" class="btn btn-normal" data-btn-clear>
                    <input type="submit" value="{{__("Search")}}" class="btn btn-primary">
                </div>
            </div>
        </form>
        @if ($users->count() > 0)
            <table class="table table-borderless list list-m">
                <thead>
                    <tr>
                        <th scope="col">{{__("Shop name")}}</th>
                        <th scope="col">{{__("Contractor ID")}}</th>
                        <th scope="col">{{__("Contract Name")}}</th>
                        <th scope="col">{{__("Registration date")}}</th>
                        <th scope="col">{{__("Representative's name")}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{$user->shop->name}}</td>
                            <td>{{$user->contractor_id}}</td>
                            <td>{{$user->contract_name}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->representative_name}}</td>
                            <td>
                                <a class="btn btn-detail" href="{{route("admin.userDetail", ['id' => $user->id])}}">{{__("View")}}</a>
                                <a class="btn btn-update" href="{{route("admin.userEdit", ['id' => $user->id])}}">{{__("Change")}}</a>
                                <a class="btn btn-gradient" href="{{route("admin.lineList", ['id' => $user->id])}}">{{__("Line list")}}</a>
                                @if($user->exists_document)
                                    <a class="btn btn-light" href="{{route("admin.documentDownload", ['id' => $user->id])}}">{{__("Document Download")}}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        @else
            <div class="mt-3">{{__("Customers not found.")}}</div>
        @endif
    </div>
@stop

@include('common.scripts.clear-form')
