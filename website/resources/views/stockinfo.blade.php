@include('layouts.header')
<body>
<div>

@include('layouts.header-navbar')

<div class="table-responsive body-content">
    <div class=" table-hover">
        <h1> Top 20 Stock information</h1>
        <table class="font-color">
            <thead>
            <tr class="TD">
                <th>Company </th>
                <th>ASX Code</th>
                <th>Trading</th>
            </tr>
            </thead>
            <tbody class="font-color">
            <tr>
                <td><a>National Australia Bank Ltd</a></td>
                <td>NAB</td>
                <td>$50</td>
            </tr>
            <tr>
                <td><a>CommonWealth Bank Australia Ltd</a></td>
                <td>CBA</td>
                <td>$89.5</td>
            </tr>
            <tr>
                <td><a>Westpac Bank Corp</a></td>
                <td>WBC</td>
                <td>$35</td>
            </tr>
            <tr>
                <td><a>Australia and New Zealand Bank Group</a></td>
                <td>ANZ</td>
                <td>$31</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>>
</div>
<footer class="footer">
    <div class=" container">
        @include('layouts.footer')
    </div>
</footer>
</body>