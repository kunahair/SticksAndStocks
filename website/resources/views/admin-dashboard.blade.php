@include('layouts.header')

@section('title','Pineapple')


    <body>

        <style>
            .panel {
                padding: 0;
                /*margin: 48px 60px;*/
            }
            .panel :hover{
                cursor: pointer;
                background-color: #f0ad4e;
            }

            #account-info {
                margin-top: 20%;
            }
        </style>

        @include('layouts.navbar')

        <div id='content' class="container">
            <h2>Admin Dashboard</h2>
            <hr />

            {{--Quick stats--}}
            <div class="col-xs-12">
                <h4>Total Users: {{$data["usersCount"]}}</h4>
            </div>

            {{--Show list of Users with info that is editible--}}
            <div class="col-xs-12">
                <table class="table table-hover">
                    {{--Headings--}}
                    <thead>
                        <tr>
                            <td class="col-xs-1">id</td>
                            <td class="col-xs-2">name</td>
                            <td class="col-xs-3">email</td>
                            <td class="col-xs-1">admin</td>
                            <td class="col-xs-2">created</td>
                            <td class="col-xs-2">modified</td>
                            <td class="col-xs-1"></td>
                        </tr>
                    </thead>

                    {{--Loop through all users and display as table rows--}}
                    <tbody>
                    @foreach($data["users"] as $user)

                        <tr>
                            <td class="col-xs-1">{{$user->id}}</td>
                            <td class="col-xs-2">{{$user->name}}</td>
                            <td class="col-xs-3">{{$user->email}}</td>

                            {{--Display admin as a checkbox, ticked if admin--}}
                            <td class="col-xs-1">
                                @if($user->admin)
                                    <input type="checkbox" name="isAdmin" value="1" checked >
                                @else
                                    <input type="checkbox" name="isAdmin" value="0" >
                                @endif
                            </td>

                            <td class="col-xs-2">{{$user->created_at}}</td>
                            <td class="col-xs-2">{{$user->updated_at}}</td>

                            {{--Edit button, may make it a drop down menu later--}}
                            <td class="col-xs-1"><button >edit</button></td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </body>


</html>