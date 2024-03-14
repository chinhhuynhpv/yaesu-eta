<ul class="nav nav-pills flex-column mb-auto">

@if ( ! Auth::guard('admin')->user()->is_admin)
    <!-- operator -->

    <li class="nav-item">
        <a href="{{route('admin.applicationList')}}" class="nav-link {{request()->routeIs('admin.opapplicationtion*') ? 'active' : ''}}">{{__("Application list")}}</a>
    </li>

    <li>
    <hr class="nav-hr" size="1" />
    </liv>

    <li class="nav-item">
        <a href="{{route('admin.userList')}}" class="nav-link {{request()->routeIs('admin.user*') ? 'active' : ''}}">{{__("Contractor list")}}</a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.lineList')}}" class="nav-link {{request()->routeIs('admin.line*') ? 'active' : ''}}">{{__("Line list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.simList')}}" class="nav-link {{request()->routeIs('admin.sim*') ? 'active' : ''}}">{{__("SIM master list")}}</a>
    </li>

    <li>
    <hr class="nav-hr" size="1" />
    </liv>

    <li class="nav-item">
        <a href="{{route('operator.billingList')}}" class="nav-link {{request()->routeIs('operator.billing*') ? 'active' : ''}}">{{__("Billing list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('operator.salesList')}}" class="nav-link {{request()->routeIs('operator.sales*') ? 'active' : ''}}">{{__("Sales list")}}</a>
    </li>

@else
    <!-- admin -->
    <li class="nav-item">
        <a href="{{route('admin.planList')}}" class="nav-link {{request()->routeIs('admin.plan*') ? 'active' : ''}}">{{__("Plan list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.optionList')}}" class="nav-link {{request()->routeIs('admin.option*') ? 'active' : ''}}">{{__("Option list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.incentiveList')}}" class="nav-link {{request()->routeIs('admin.incentive*') ? 'active' : ''}}">{{__("Incentive list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.commissionList')}}" class="nav-link {{request()->routeIs('admin.commission*') ? 'active' : ''}}">{{__("Commission list")}}</a>
    </li>
@endif

</ul>


<div id="admin-infobox">
    @if ( ! Auth::guard('admin')->user()->is_admin)
        <div class="admintype">{{__("Operator")}}</div>
    @else
        <div class="admintype">{{__("Administrator")}}</div>
    @endif
    <!--<div class="adminheader">{{"Administrator"}}</div>-->
    <div class="adminname">{{ Auth::user()['name'] }}</div>
</div>
