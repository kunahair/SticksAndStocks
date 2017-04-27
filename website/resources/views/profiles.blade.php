@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

    <h1>All Users</h1>
    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

    <div class="col-xs-10 col-md-6">
        <table class="col-xs-4 table table-hover">
            {{--Loop through all users (not admins) and show in the table--}}
            @foreach($users as $user)
                <tr onclick="window.document.location=' {{url('profile')}}/{{$user["id"]}}'">
                    <td>{{$user["name"]}}</td>
                </tr>
            @endforeach

        </table>
    </div>

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

@include('layouts.footer')