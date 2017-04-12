<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <!--Bootstrap CSS-->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous"
    >

    <!--Bootstrap Javascript-->
    <!-- Latest compiled and minified JavaScript -->
    <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"
    ></script>

    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css">

    <style type="text/css">
        .stock-table-data {
            width: 50%;
        }
    </style>

    <!--<link rel="stylesheet" href="style.css"/>-->
    <!--<style>
        .box{
            background-color: white;
            width:500px ;
            padding: 10px;
        }
        .box:hover{
            box-shadow: 4px 4px 2px #888888;
        }
    </style>-->
    {{ !date_default_timezone_set('Australia/Melbourne') }}
</head>

<body>

@include('layouts.navbar')
<!-- Modal -->
@include('layouts.buystock')

    <div class=" container">
        <h2>{{$tradeAccount["name"]}}</h2>
        <hr />
        <h3>${{$tradeAccount["balance"]}}</h3>

        <div id="trade-account-info-box" class="col-xs-10 col-md-12" style="padding-top: 3%">

            <div class="col-xs-1 col-md-2"></div>

            <table class="table col-xs-9 ">
                <thead>
                <tr>
                    <td class="col-xs-3" style="padding: 0px">Code</td>
                    <td class="col-xs-3" style="padding: 0px">Name</td>
                    <td class="col-xs-3" style="padding: 0px">Price</td>
                    <td class="col-xs-3" style="padding: 0px">Growth</td>
                    <td class="col-xs-3" style="padding: 0px">Buy/sell</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class=col-xs-3" style="padding: 0px"> NAB</td>
                    <td class=col-xs-3" style="padding: 0px"> National Australia Bank</td>
                    <td class=col-xs-3" style="padding: 0px"> $$</td>
                    <td class=col-xs-3" style="padding: 0px"> <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Buy">Buy</button><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Sell">Sell</button></td>
                </tr>
                <tr>
                    <td class="col-xs-1 " style="padding: 0px"> CBA</td>
                    <td class=col-xs-3" style="padding: 0px"> CommonWealth Bank Australia</td>
                    <td class=col-xs-3" style="padding: 0px"> $$</td>
                    <td class=col-xs-3" style="padding: 0px"> <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Buy">Buy</button><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Sell">Sell</button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-1 col-md-3"></div>
    </div>

</body>

</html>