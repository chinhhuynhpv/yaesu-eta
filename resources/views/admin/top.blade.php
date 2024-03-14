@extends('admin.body')

@section('container')
    <div class="d-flex flex-column align-items-center mt-5">
        @if (Auth::guard('admin')->user()->is_admin)
            <div class="mt-3">
                <a href="{{route('admin.planList')}}" class="btn btn-primary">{{__("Plan list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.optionList')}}" class="btn btn-primary">{{__("Option list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.incentiveList')}}" class="btn btn-primary">{{__("Incentive list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.commissionList')}}" class="btn btn-primary">{{__("Commission list")}}</a>
            </div>
        @else
            <div class="mt-3">
                <a href="{{route('admin.userList')}}" class="btn btn-primary">{{__("Contractor list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.applicationList')}}" class="btn btn-primary">{{__("Application list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.lineList')}}" class="btn btn-primary">{{__("Line list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('admin.simList')}}" class="btn btn-primary">{{__("SIM master list")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('operator.billingList')}}" class="btn btn-primary">{{__("Billing List")}}</a>
            </div>
            <div class="mt-3">
                <a href="{{route('operator.salesList')}}" class="btn btn-primary">{{__("Sales List")}}</a>
            </div>
        @endif
    </div>
@stop
