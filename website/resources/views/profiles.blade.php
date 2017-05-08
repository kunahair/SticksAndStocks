@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

    <style>

        thead{
            background-color: black;
        }

    </style>

    <h1>All Users</h1>
    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

    <table class="table-hover table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            {{--Loop through all users (not admins) and show in the table--}}
            @foreach($users as $user)
                <tr onclick="window.document.location=' {{url('profile')}}/{{$user["id"]}}'" class="clickable-table-item">
                    <td>{{$user["name"]}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

@include('layouts.footer')