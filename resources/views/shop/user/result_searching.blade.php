@extends('shop.body')

@section('container')
    <div class="content-talk-group p-4">

        @include('alert.error')
        <h2 class="text-center">{{__("Talk List - Result Searching")}}</h2>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif
            
        <div class="row">
            <div class="col-md-12">
                <form class="d-flex" action="{{Route('shop.talk.group.search')}}" method="get">
                    @csrf
                    <div class="form-outline">
                        <input type="search" name="s" placeholder="line num or sim id" value='@if(!empty($keyword)){{$keyword}}@endif' class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        {{__(" Search")}}
                    </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-5"> 
            @if(!empty($talkGroup) && count($talkGroup) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">{{__("id")}} </th>
                            <th scope="col">{{__("Group Id")}} </th>
                            <th scope="col">{{__("Group name")}}</th>
                            <th scope="col">{{__("Priority")}}</th>
                            <th scope="col">{{__("Member display")}}</th>
                            <th scope="col">{{__("Group Manager")}}</th>
                            <th scope="col">{{__("Status")}} </th>
                            <th scope="col">{{__("Date of creation")}} </th>
                            <th scope="col">{{__(" Update date")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $talkGroup as $item)
                            <tr>
                                <th scope="row">{{$item->id}}</th>
                                <td>{{$item->group_id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->priority}}</td>
                                <td>{{$item->member_view}}</td>
                                <td>{{$item->group_responsible_person}}</td>
                                <td>{{$item->status}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->updated_at}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="mt-3">{{__("該当の契約者は見つかりませんでした。")}}</div>
            @endif
        </div>
    </div>
@stop
