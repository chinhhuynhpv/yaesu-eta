@extends('operator.body')

@section('container')
    <div class="mt-3">
        <h3 class="text-center">{{__("Sim Details")}}</h3>
        <div class="rht" data-delete-url="{{route("admin.simHandleDelete")}}">
            <a  href="" data-id="{{$item->id}}" data-name="{{$item->sim_num}}" class="btn btn-delete" data-btn-delete>{{__("Delete")}}</a>
        </div>
        <table class="table table-borderless detail">
            <tbody>
                <tr>
                    <td>{{__("Sim num")}}</td>
                    <td>{{$item->sim_num}}</td>
                </tr>
                <tr>
                    <td>{{__("Career")}}</td>
                    <td>{{$item->career}}</td>
                </tr>
                <tr>
                    <td>{{__("SIM contractor")}}</td>
                    <td>{{$item->sim_contractor}}</td>
                </tr>
                <tr>
                    <td>{{__("Status")}}</td>
                    <td>{{$item->status}}</td>
                </tr>
                <tr>
                    <td>{{__("SIM opening date")}}</td>
                    <td>{{$item->sim_opening_date}}</td>
                </tr>
                <tr>
                    <td>{{__("Remark")}}</td>
                    <td class="longtext">{{$item->remark}}</td>
                </tr>
            </tbody>
        </table>
        <div class="lft">
            <a class="btn" href="{{route("admin.simList")}}">{{__("Back")}}</a>
        </div>
    </div>

    @include('modals.modal-delete')
@stop
