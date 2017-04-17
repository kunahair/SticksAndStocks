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

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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

<!-- get example data-->
<script>
    function myFunction() {
        document.getElementById("nab").innerHTML = "NAB"
    }
</script>

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

            <h2>Held Stocks</h2>

            <table class="table col-xs-10 ">
                <thead>
                <tr>
                    <td class="col-xs-1" style="padding: 0px">Code</td>
                    <td class="col-xs-4" style="padding: 0px">Name</td>
                    <td class="col-xs-2" style="padding: 0px">Value</td>
                    <td class="col-xs-2" style="padding: 0px">Growth</td>
                    <td class="col-xs-2" style="padding: 0px">Owned</td>
                    <td class="col-xs-1" style="padding: 0px">View</td>
                </tr>
                </thead>
                <tbody>


                @php
                    //Holder for grouped transactions
                    $transactions = array();

                    //Loop through all the transactions the current trade account has
                    //Group all the transactions into the transactions array
                    foreach ($tradeAccount->transactions as $transaction)
                    {
                        //If the stock has not been assigned into transactions, add it
                        if(!array_key_exists($transaction->stock_id, $transactions))
                        {
                            $transactions[$transaction->stock_id] = array();
                        }
                        //Add the current transaction to its transactions group
                        array_push($transactions[$transaction->stock_id], $transaction);
                    }

                    $allStocksTotalValue = 0.00;
                    $allStocksTotalCount = 0;
                    $stockCount = 0;

                    //Loop through each transaction group
                    //Inner loop the individual transactions for that group
                    //For each individual transaction that is not in a waiting state, gather statistics
                    foreach ($transactions as $transactionsGroup)
                    {
                        //Stock stats and info
                        $stock_symbol = "";
                        $stock_name = "";
                        $stock_total_cost = 0.00;
                        $stock_owned = 0;
                        $stock_sold = 0;
                        $stock_total_growth = 0.00;
                        $stock_current_price = 0.00;

                        $assignOnce = 0;

                        foreach ($transactionsGroup as $transaction)
                        {
                            //If the current transaction is waiting, then move onto the next one
                            if ($transaction->waiting)
                            {
                                continue;
                            }

                            //To save memory, just capture the name, symbol and current price of stock group once
                            if ($assignOnce == 0)
                            {
                                $stock_symbol = $transaction->stock->stock_symbol;
                                $stock_name = $transaction->stock->stock_name;

                                $stock_current_price = $transaction->stock->current_price;

                                $assignOnce++;
                            }

                            //Calculate the initial cost to the user for the stock, add it to total
                            $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
                            //Get the amount of stock owned for this transaction, add it to total
                            $stock_owned += $transaction->bought;
                            //Get the amount of stock sold for this transaction, add it to total
                            $stock_sold += $transaction->sold;

                            //$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));

                            //echo '<pre>';
                            //print_r($transaction->price);
                            //echo '</pre>';
                        }

                        //If the stock owned is less than 1 (it should never hit below 0)
                        //Then stock is not needed as it is not working for account in current state
                        if ($stock_owned <= 0 || $stock_sold == $stock_owned)
                        {
                            continue;
                        }

                        //Calculate the total amount of growth that the account has for this stock (overall NOT average)
                        $stock_total_growth = ($stock_total_cost / ($stock_owned - $stock_sold)) - $stock_current_price;

                        if ($stock_total_growth > 0.00)
                            $stock_total_growth *= ($stock_owned - $stock_sold) * -1.00;

                        //Add stock information to the holding table
                        echo '<tr>
                                    <td class="col-xs-1 " style="padding: 0px"><a href="' . "../stock/". $stock_symbol . '">' . $stock_symbol . '</a></td>
                                    <td class=col-xs-4" style="padding: 0px">' . $stock_name . '</td>
                                    <td class=col-xs-3" style="padding: 0px">$' . number_format($stock_total_cost, 2) . '</td>
                                    <td class=col-xs-3" style="padding: 0px">$' . number_format($stock_total_growth, 2) . '</td>
                                    <td class=col-xs-3" style="padding: 0px">' . ($stock_owned - $stock_sold) . '</td>
                                    <td class=col-xs-3" style="padding: 0px">' . '<a href="#">view</a>' . '</td>
                                 </tr>';

                        $allStocksTotalValue += $stock_current_price * ($stock_owned - $stock_sold);
                        $allStocksTotalCount += ($stock_owned - $stock_sold);
                        $stockCount++;

                    }

                    echo '</tbody></table>';

                    //Show the average Stock value of this Trade Account
                    echo '<div class="col-xs-12" style="padding-left: 0">';
                    
                    if ($allStocksTotalCount > 0)
                    echo '<h4>Stock Average Value: $' . number_format(($allStocksTotalValue / $allStocksTotalCount),2) . 'AUD</h4>';

                    echo '</div>';

                    //Show the total Stock value of this Trade Account
                    echo '<div class="col-xs-12" style="padding-left: 0">';

                    echo '<h4>Stock Total Value: $' . number_format($allStocksTotalValue, 2) . 'AUD</h4>';

                    echo '</div>';
                @endphp

                {{--<tr>--}}
                    {{--<td class=col-xs-3" id="1" style="padding: 0px"> NAB</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> National Australia Bank</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> $$</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> Growth</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> <button type="button" class="btn btn-info btn-lg" onclick="myFunction()" data-toggle="modal" data-target="#Buy">Buy</button><button type="button" class="btn btn-info btn-lg" onclick="myFunction()" data-toggle="modal" data-target="#Sell">Sell</button></td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td class="col-xs-1 " style="padding: 0px"> CBA</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> CommonWealth Bank Australia</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> $$</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> Growth</td>--}}
                    {{--<td class=col-xs-3" style="padding: 0px"> <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Buy">Buy</button><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Sell">Sell</button></td>--}}
                {{--</tr>--}}
{{-->>>>>>> origin/master--}}
                {{--</tbody>--}}
            {{--</table>--}}
        </div>
        <div class="col-xs-1 col-md-3"></div>


        <div class="col-xs-12">

            <h2>Transactions</h2>

            <input name="daterange" type="text" style="width: 100%; margin-bottom: 5%" />

            <script>
                var date = new Date();

                //Date picker library: http://www.daterangepicker.com/
                $('input[name="daterange"]').daterangepicker(
                    {
                        locale: {
                            format: 'DD-MM-YYYY'
                        },
                        startDate: date.getDate() + '/' + (date.getMonth() + 1) + '/' + ((date.getYear() - 100) + 2000),//'2013-01-01',
                        endDate: date.getDate() + '/' + (date.getMonth() + 1) + '/' + ((date.getYear() - 100) + 2000)//'2013-12-31'
                    },
                    function(start, end, label) {
                        //Get the start date in epoch time to send to the server
                        var startDate = new Date();
                        startDate.setDate(start.format('DD'));
                        var startDateInt = parseInt(startDate.getTime() / 1000);
//                        console.log(startDateInt);

                        //Get the end date in epoch time to send to the server
                        var endDate = new Date();
                        endDate.setDate(end.format('DD'));
                        var endDateInt = parseInt(endDate.getTime() / 1000);
//                        console.log(endDateInt);

                        //Data holder that will be sent to the server
                        var postData = {};

                        //Assign data to the holder
                        postData["start"] = startDateInt;
                        postData["end"] = endDateInt;
                        postData["trade_account_id"] = {{$tradeAccount->id}};

                        //Call the server to give a list of transactions that are within the User selected date range
                        $.post("{{url('api/getTransactionsInDateRange')}}", postData)
                            .done(function (data) {
//                                console.log(data);
                                //Remove the contents of the transactions table body (remove all rows except heading)
                                $('#transactionsTableBody tr').remove();

                                //Loop through all the returned transaction (with stock info) objects and fill the table body
                                for(var i = 0; i < data.length; i++)
                                {
//                                    console.log(data[i]["stock_symbol"]);
                                    //Add the next row after the last row that has been added
                                    $('#transactionsTableBody').append(
                                        '<tr>' +
                                        '<td class=col-xs-3" style="padding: 0px">' + data[i]["stock_symbol"] + '</td>' +
                                        '<td class=col-xs-3" style="padding: 0px">'+ data[i]["stock_name"] + '</td>'   +
                                        '<td class=col-xs-3" style="padding: 0px"> $' + data[i]["price"] +'</td>' +
                                        '<td class=col-xs-3" style="padding: 0px">' + data[i]["updated_at"] + '</td>'
                                        + '</tr>'
                                    );

                                    //Add the sold or bought attribute
                                    if (data[i]["sold"] > 0)
                                        $('#transactionsTableBody tr:last td:nth-child(3)').after(
                                            '<td class=col-xs-3" style="padding: 0px">-' + data[i]["sold"] + '</td>'
                                        );
                                    else
                                        $('#transactionsTableBody tr:last td:nth-child(3)').after(
                                            '<td class=col-xs-3" style="padding: 0px">+' + data[i]["bought"] + '</td>'
                                        );
                                }
                            })

                            .fail(function (error) {
                                console.log(error);
                            })
                        ;

//            alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                    });
            </script>

            {{--Table to show list of transactions, searched above by date and filled with jQuery/Javascript--}}
            <table class="table col-xs-12 ">
                <thead>
                    <tr>
                        <td class="col-xs-1" style="padding: 0px">Code</td>
                        <td class="col-xs-4" style="padding: 0px">Name</td>
                        <td class="col-xs-2" style="padding: 0px">Price</td>
                        <td class="col-xs-3" style="padding: 0px">Purchased/Sold</td>
                        <td class="col-xs-3" style="padding: 0px">Date</td>
                        {{--<td class="col-xs-3" style="padding: 0px">Buy/sell</td>--}}
                    </tr>
                </thead>

                <tbody id="transactionsTableBody">

                    {{--Loop through all the transactions that the user has and show in a table--}}
                    {{--@foreach($tradeAccount->transactions as $transaction)--}}


                        {{--<tr>--}}
                            {{--<td class=col-xs-3" style="padding: 0px"> {{$transaction->stock->stock_symbol}}</td>--}}
                            {{--<td class=col-xs-3" style="padding: 0px"> {{$transaction->stock->stock_name}}</td>--}}
                            {{--<td class=col-xs-3" style="padding: 0px"> ${{number_format($transaction->price, 2)}}</td>--}}
                            {{--@if($transaction->sold > 0)--}}
                                {{--<td class=col-xs-3" style="padding: 0px"> -{{$transaction->sold}}</td>--}}
                            {{--@else--}}
                                {{--<td class=col-xs-3" style="padding: 0px"> +{{$transaction->bought}}</td>--}}
                            {{--@endif--}}
                            {{--<td class=col-xs-3" style="padding: 0px"> {{$transaction->updated_at}}</td>--}}
{{--                            <td class="col-xs-3" style="padding: 0"> {{$transaction->stock->current_price - $transaction->price}}</td>--}}
                            {{--<td class=col-xs-3" style="padding: 0px"> <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Buy">Buy</button><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#Sell">Sell</button></td>--}}
                        {{--</tr>--}}

                    {{--@endforeach--}}
                </tbody>
            </div>
        </div>
    </div>

</body>

</html>
