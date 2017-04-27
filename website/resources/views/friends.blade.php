@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

    <h1>Friends</h1>
    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

    <div class="col-xs-10 col-md-6">
        <table class="col-xs-4 table table-hover">

            {{--Loop through every friend and show in the table--}}
            @foreach($friends as $friend)
                <tr onclick="window.document.location=' {{url('profile')}}/{{$friend["id"]}}'">
                    <td>{{$friend["name"]}}</td>
                </tr>
            @endforeach

        </table>
    </div>

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

@include('layouts.footer')
