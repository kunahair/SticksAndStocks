@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

<style>

    thead{
        background-color: black;
    }

</style>



<h1>Friends</h1>
    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

        <table class="col-xs-4 table table-hover table-bordered">

            <thead>
            <tr>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>

            {{--Loop through every friend and show in the table--}}
            @foreach($friends as $friend)
                <tr onclick="window.document.location=' {{url('profile')}}/{{$friend["id"]}}'">
                    <td>{{$friend["name"]}}</td>
                </tr>
            @endforeach

            <tbody>

        </table>
    

    {{--Padding--}}
    <div class="col-xs-1 col-md-3"></div>

@include('layouts.footer')
