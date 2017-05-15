@include('layouts.header')


<body class="background">
@include('layouts.navbar')
<div class="bg">

    <h1 class="subheading">Leaderboard</h1>
</div>
<div class="container ">
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