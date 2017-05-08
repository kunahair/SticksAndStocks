@include('layouts.header')

@include('layouts.navbar')

<div class="container">

    <h1>Leaderboard</h1>

    <br/>
    <br/>

    <table>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Portfolio Value</th>
        </tr>
        {{ $rank = 1 }}
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