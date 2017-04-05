@include('layouts.header')
<body>
<div>

@include('layouts.header-navbar')

@section('layouts.content-div')
    <div class="body-content">
        <h1> Trading</h1>
        <table class="font-color">
            <tr class="TD">
                <th>Company </th>
                <th>ASX Code</th>
                <th>Trading</th>
            </tr>
            <tr>
                <td>National Australia Bank Ltd</td>
                <td>NAB</td>
                <td>50</td>
            </tr>
            <tr>
                <td>CommonWealth Bank Australia Ltd</td>
                <td>CBA</td>
                <td>89.5</td>
            </tr>
            <tr>
                <td>Westpac Bank Corp</td>
                <td>WBC</td>
                <td>35</td>
            </tr>
        </table>
    </div>

</div>
<footer class="footer">
    <div class=" container">
    </div>
</footer>
</body>