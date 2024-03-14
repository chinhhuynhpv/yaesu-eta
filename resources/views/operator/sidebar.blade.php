<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{route('admin.userList')}}" class="nav-link {{request()->routeIs('admin.user*') ? 'active' : ''}}">{{__("Contractor list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.applicationList')}}" class="nav-link {{request()->routeIs('admin.application*') ? 'active' : ''}}">{{__("Application list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.lineList')}}" class="nav-link {{request()->routeIs('admin.line*') ? 'active' : ''}}">{{__("Line list")}}</a>
    </li>

<!--
    <li class="nav-item">
        <a href="{{route('admin.simList')}}" class="nav-link {{request()->routeIs('admin.sim*') ? 'active' : ''}}">{{__("SIM master list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.billingData')}}" class="nav-link {{request()->routeIs('admin.billingData*') ? 'active' : ''}}">{{__("Billing data")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.saleData')}}" class="nav-link {{request()->routeIs('admin.saleData*') ? 'active' : ''}}">{{__("Sale data")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('operator.billingList')}}" class="nav-link {{request()->routeIs('operator.billingList*') ? 'active' : ''}}">{{__("Billing list")}}</a>
    </li>
    <li class="nav-item">
        <a href="{{route('operator.salesList')}}" class="nav-link {{request()->routeIs('operator.salesList*') ? 'active' : ''}}">{{__("Sales list")}}</a>
    </li>
-->
</ul>

<div class="mt-3">
    @php $sadmin = Auth::guard('admin')->user(); @endphp
    <form method="post" action="{{route('admin.logout')}}">
        @csrf
        <button type="submit" class="btn btn-logout">{{__("Logout")}}</button>
    </form>
</div>
