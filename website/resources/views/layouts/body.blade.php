<body>

<div class="">
    <div class="top-center ">
        <div class="title m-b-md ">
            {{--Pineapple logo--}}
            <div id="logo">
                <a href="{{url('/')}}"> <img src="img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align=""></a>
            </div>
            Pineapple
        </div>

        {{--Buttons that can be shown below the Pineapple Logo, at this stage do not go anywhere so dont show them--}}
        {{--todo: Make links that actually go to proper locations--}}
        {{--<div class="links">--}}
            {{--<a href="">How to Play</a>--}}
            {{--<a href="">Stock Market</a>--}}
            {{--<a href="">Trends</a>--}}
        {{--</div>--}}
    </div>
    <div class="top-right">
        <div class="">

            {{--Does a check to see if it is a client or a signed in User--}}
            {{--If it is a client, show the login and register buttons--}}
            @if(!Auth::check())
            <div class="col-md-4">
                <button type="button" class="btn button btn-lg" onclick="location.href='{{ url('login') }}'">Login</button>
            </div>
            <div class="col-md-3 col-md-offset-3">
                <button type="button" class="btn button btn-lg" onclick="location.href='{{ url('register') }}'">Register</button>
            </div>
            @else
                {{--Otherwise, assume it is a User/Admin who is logged in an show a Dashboard button--}}
                <div class="col-md-3 col-md-offset-3">
                    <button type="button" class="btn button btn-lg" onclick="location.href='{{ url('dashboard') }}'">Dashboard</button>
                </div>
            @endif
        </div>
    </div>

</div>

{{--Load the Three.JS library for the changing background--}}
<div>
    @include('layouts.background')
</div>

<div class="content">

</div>

{{--Add the footer--}}
@include('layouts.footer')


