@extends('master')

@section('body_class', 'user-page')

@section('header')

    <div class="header-right-box col-sm-3">
@auth
        <div class="mt-3">
            @php $user = Auth::guard('user')->user(); @endphp
            <form method="post" action="{{route('user.logout')}}">
                @csrf
                <button type="submit" class="btn btn-logout">{{__("Logout")}}</button>
            </form>
        </div>
@endauth
    </div>

    <div class="header-center-box col-sm-6 text-center">
        <div class="header-customername">
@if (! isset($users) )
    @if ( isset($user) and $user )
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
        <div id="nav-inner">
@include('user.sidebar')
        </diV>
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
