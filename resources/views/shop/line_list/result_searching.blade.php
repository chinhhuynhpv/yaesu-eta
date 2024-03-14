@extends('shop.body')

@section('container')
    <div class="">
        <div class="row p-4">
            <div class="col-md-10">
                <h3>{{__("List line")}}</h3>
            </div>
            <div class="col-md-2">
                <form class="d-flex" action="{{Route('shop.line.search')}}" method="get">
                    @csrf
                    <div class="form-outline">
                        <input type="search" name="s" value="{{$keyword}}" placeholder="line num or sim id" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="list-talk-group p-4">
            @if(!empty($listLines))
            <table class="table list-talk-group">
                <thead>
                    <tr>
                        <th scope="col" class='text-wrap w-5'>{{__("Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Request Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Shop Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Line Id")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Terminal Num")}}</th>
                        <th scope="col" class='text-wrap w-5'>{{__("Name")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Call Type")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Priority")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Individual")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Recording")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Gps")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Status")}} </th>
                        <th scope="col" class='text-wrap w-5'>{{__("Action")}} </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $listLines as $item)
                        <tr>
                            <th scope="row"> <a href="{{Route('shop.talk.group.detail')}}?id={{$item->id}}">{{$item->id}} </a> </th>
                            <td class='text-wrap w-5 '>{{$item->request_id}}</td>
                            <td class='text-wrap w-5 '>{{$item->shop_id}}</td>
                            <td class='text-wrap w-5 '>{{$item->voip_line_id}}</td>
                            <td class='text-wrap w-5 '>{{$item->line_num}}</td>
                            <td class='text-wrap w-5 '>{{$item->voip_id_name}}</td>
                            <td class='text-wrap w-5 '>{{$item->call_type}}</td>
                            <td class='text-wrap w-5 '>{{$item->priority}}</td>
                            <td class='text-wrap w-5 '>{{$item->individual}}</td>
                            <td class='text-wrap w-5 '>{{$item->recording}}</td>
                            <td class='text-wrap w-5 '>{{$item->gps}}</td>
                            <td class='text-wrap w-5 '>{{$item->status}}</td>
                            <td class='text-wrap w-5 '>
                                <a class="btn" href="{{Route('shop.user.line.list.detail')}}?id={{$item->id}}" role="button"> {{__("view detail")}}</a>
                            </td>
                        </tr>
                    @endforeach
                    {{ $listLines->links() }}
                </tbody>
            </table>
            @else
                <div class="mt-3">{{__("該当の契約者は見つかりませんでした。")}}</div>
            @endif
        </div>
    </div>
@stop
