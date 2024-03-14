@extends('master')

@section('body_class', 'admin-page')

@section('header')

    <div class="header-left-box col-md-3">
@auth
        <div id="logout-box" class="">
            @php $admin = Auth::guard('admin')->user(); @endphp
            <form method="post" action="{{route('admin.logout')}}">
                @csrf
                <button type="submit" class="btn btn-logout">{{__("Logout")}}</button>
            </form>
        </div>
@endauth
    </div>

    <div class="header-center-box col-md-6 text-center">
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

    <div class="header-logo-box col-md-3 text-right">
        <img src="/image/logo_black.png" class="header-logo" alt="{{__('STR WAC Service')}}" />
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
@include('admin.sidebar')
        </diV>
      </diV>
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
