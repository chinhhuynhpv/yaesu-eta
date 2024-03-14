@extends('master')

@section('body_class', 'shop-page')

@section('header')


    <div class="header-left-box col-sm-3">
@auth
        <div id="logout-box" class="">
        @php $sshop = Auth::guard('shop')->user(); @endphp
            <form method="post" action="{{route('shop.logout')}}">
                @csrf
                <button type="submit" class="btn btn-logout">{{__("Logout")}}</button>
            </form>
        </div>
@endauth
    </div>


    <div class="header-center-box col-sm-6 text-center">
        <div class="header-customername">
@if (! isset($users) )
    @if ( isset($user) and $user and isset($user->contract_name) )
            <span class="dsp-ib customername">
            {{$user->contract_name}}       
            </span>
            <span class="dsp-ib sama">{{__('Customer Sama')}}</span>
    @endif    
@endif
        </div>
    </div>

    <div class="header-logo-box col-sm-3 text-right">
        <img src="/image/logo_white.png" class="header-logo" alt="{{__('STR WAC Service')}}" />
    </div>

@stop

@section('sidebar')
    <nav id="left-sidebar" class="col-md-2">
      <div id="nav-frame">

        <div id="sidebartab-btn">
          <div id="tohide">←</div>
          <div id="toshow" class="hide">→</div>
        </div>

        <div id="nav-inner">
@include('shop.sidebar')
        </div>
      </div>
    </nav>
@stop

@section('content')
    <main class="col-md-10">

@include('alert.error')
@include('alert.success')

@yield('container')

        <div id="inner_footer" class="text-center">
            <hr />
            <div>Yaesu Musen Co., Ltd. {{__('STR WAC Service')}} all rights reserved.</div>
        </div>

    </main>
@stop

