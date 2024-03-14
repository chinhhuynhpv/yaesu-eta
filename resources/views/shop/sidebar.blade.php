<ul class="nav nav-pills flex-column mb-auto">

    <li class="nav-item">
    <a href="{{route('shop.top')}}" class="nav-link {{request()->routeIs('shop.top') ? 'active' : ''}}">{{__("Top Page")}}</a>
    </li>

    <li class="nav-item">
        <a href="{{route('shop.userList')}}" class="nav-link {{request()->routeIs('shop.user*') ? 'active' : ''}}">{{__("Contractor list")}}</a>
    </li>

    <li class="nav-item">
        <a href="{{route('shop.userInput')}}" class="nav-link {{request()->routeIs('shop.userInput*') ? 'active' : ''}}">{{__("Contractor create")}}</a>
    </li>

</ul>


<div id="shop-infobox">
    <!--<div class="shopheader">{{"Shop"}}</div>-->
    <div class="shopcode">{{ Auth::user()['shop_code'] }}</div>
    <div class="shopname">{{ Auth::user()['name'] }}</div>
</div>
