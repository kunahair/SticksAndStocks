@include('layouts.header')


<body >
@include('layouts.navbar')
<div class="bg" style="padding-top:3% ">
<div class="content-box bg">

    <h1 >Leaderboard</h1>
</div>
</div>
<div class="content-box">
    <br/>
    <br/>

    <table>
        <thead>
        <tr>
            <td>Rank</td>
            <td>Name</td>
            <td>Portfolio Value</td>
        </tr>
        </thead>
        @php ( $rank = 1)
    @foreach ($users as $user)
        <tr>
            <th>{{ $rank++ }}</th>
            <th>{{ $user->name }}</th>
            <th>${{ $user->portfolio }}</th>
        </tr>
    @endforeach
    </table>

</div>



@include('layouts.footer')