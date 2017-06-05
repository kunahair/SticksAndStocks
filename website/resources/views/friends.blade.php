{{--/**--}}
{{--* Created by: Josh Gerlach.--}}
{{--* Authors: Josh Gerlach and Abnezer Yhannes--}}
{{--*/--}}
@include('layouts.header')
@include('layouts.navbar')

@section('title','Pineapple')

<style>

   /* thead{
        background-color: black;
    }*/

</style>
<div class="bg"style="padding-top: 3%; ">
<div class="content-box" >
    <h1>Friends</h1>
</div>
</div>
<div class="content-box ">


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
</div>
@include('layouts.footer')
