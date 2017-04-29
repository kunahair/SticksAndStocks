@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')


<style>




</style>



    <h1>All Users</h1>
    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

    <div class="table">
        <thead>
        <tr>
            <th>Name</th>
            <tr>
            </thead>

        <tbody>
        <tr>
            <th scope=""row"></th>
            <td>

        {{--Loop through all users (not admins) and show in the table--}}
        @foreach($users as $user)
            <tr onclick="window.document.location=' {{url('profile')}}/{{$user["id"]}}'">
                <td>{{$user["name"]}}</td>
            </tr>
            @endforeach


            </td>


        </tr>

        </tbody>



        </tr>
        </thead>




    </div>

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

@include('layouts.footer')