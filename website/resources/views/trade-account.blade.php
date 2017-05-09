<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script src="{{url('js/jQDateRangeSlider-min.js')}}"></script>
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
    <link href="{{ url('css/iThing-min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css">

    <style type="text/css">
        .stock-table-data {
            width: 50%;
        }

        .sort-header:hover {
            cursor: pointer;
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

<body class="background">

@include('layouts.navbar')
<!-- Modal -->
@include('layouts.buystock')


        <div class="bg">
        <h2 class="subheading">{{$tradeAccount["name"]}}</h2>
        <hr />
        {{--<h3>${{number_format($tradeAccount["balance"], 2)}}</h3>--}}
        </div>
<div class=" container">
        <div id="trade-account-info-box" class="col-xs-10 col-md-12 " style="padding-top: 3%">

            <div class="col-xs-1 col-md-2"></div>

            <h2 >Held Stocks</h2>

            <table class="table table-hover col-xs-10 ">
                <thead>
                    <tr>
                        <td class="col-xs-1" style="padding: 0px">Code</td>
                        <td class="col-xs-4" style="padding: 0px">Name</td>
                        <td class="col-xs-1" style="padding: 0px">Value</td>
                        <td class="col-xs-1" style="padding: 0">Price</td>
                        <td class="col-xs-2" style="padding: 0px">Growth</td>
                        <td class="col-xs-2" style="padding: 0px">Owned</td>
                        <td class="col-xs-1" style="padding: 0px">View</td>
                    </tr>
                </thead>
                <tbody>

                {{--Loop through all the Stocks currently held and display information in table--}}
                    @foreach($tradeAccount->getCurrentStock() as $stock)

                        {{--If the key of name does not exist, it is not a stock so continue--}}
                        @if(!key_exists("name", $stock))
                            @continue
                        @endif

                        <tr>
                            <td class="col-xs-1 " style="padding: 0px"><a href="{{url('/stock')}}/{{$stock["symbol"]}}"> {{$stock["symbol"]}} </a></td>
                            <td class=col-xs-4" style="padding: 0px">{{$stock["name"]}} ({{$stock["market"]}})</td>
                            <td class=col-xs-1" style="padding: 0px">${{$stock["total_cost"]}}</td>
                            <td class=col-xs-1" style="padding: 0px">${{$stock["current_price"]}}</td>
                            <td class=col-xs-2" style="padding: 0px">${{$stock["total_growth"]}} ({{$stock["total_growth_percentage"]}}%)</td>
                            <td class=col-xs-2" style="padding: 0px">{{$stock["owns"]}}</td>
                            <td class=col-xs-1" style="padding: 0px"><a href="#">view</a></td>
                        </tr>

                    @endforeach

                </tbody>
            </table>

            {{--Show Stock Average value (as per the original spec)--}}
            <div class="col-xs-12" style="padding-left: 0">
                <h4>Stock Average Value: ${{$tradeAccount->getCurrentStock()["stats"]["average_stock_value"]}}AUD</h4>
            </div>

            {{--Show the total stock value--}}
            <div class="col-xs-12" style="padding-left: 0">
                <h4>Stock Total Value: ${{$tradeAccount->getCurrentStock()["stats"]["total_stock_value"]}}AUD</h4>
            </div>



                {{--@php--}}
                    {{--//Holder for grouped transactions--}}
                    {{--$transactions = array();--}}

                    {{--//Loop through all the transactions the current trade account has--}}
                    {{--//Group all the transactions into the transactions array--}}
                    {{--foreach ($tradeAccount->transactions as $transaction)--}}
                    {{--{--}}
                        {{--//If the stock has not been assigned into transactions, add it--}}
                        {{--if(!array_key_exists($transaction->stock_id, $transactions))--}}
                        {{--{--}}
                            {{--$transactions[$transaction->stock_id] = array();--}}
                        {{--}--}}
                        {{--//Add the current transaction to its transactions group--}}
                        {{--array_push($transactions[$transaction->stock_id], $transaction);--}}
                    {{--}--}}

                    {{--$allStocksTotalValue = 0.00;--}}
                    {{--$allStocksTotalCount = 0;--}}
                    {{--$stockCount = 0;--}}

                    {{--//Loop through each transaction group--}}
                    {{--//Inner loop the individual transactions for that group--}}
                    {{--//For each individual transaction that is not in a waiting state, gather statistics--}}
                    {{--foreach ($transactions as $transactionsGroup)--}}
                    {{--{--}}
                        {{--//Stock stats and info--}}
                        {{--$stock_symbol = "";--}}
                        {{--$stock_name = "";--}}
                        {{--$stock_total_cost = 0.00;--}}
                        {{--$stock_owned = 0;--}}
                        {{--$stock_sold = 0;--}}
                        {{--$stock_total_growth = 0.00;--}}
                        {{--$stock_current_price = 0.00;--}}

                        {{--$assignOnce = 0;--}}

                        {{--foreach ($transactionsGroup as $transaction)--}}
                        {{--{--}}
                            {{--//If the current transaction is waiting, then move onto the next one--}}
                            {{--if ($transaction->waiting)--}}
                            {{--{--}}
                                {{--continue;--}}
                            {{--}--}}

                            {{--//To save memory, just capture the name, symbol and current price of stock group once--}}
                            {{--if ($assignOnce == 0)--}}
                            {{--{--}}
                                {{--$stock_symbol = $transaction->stock->stock_symbol;--}}
                                {{--$stock_name = $transaction->stock->stock_name;--}}

                                {{--$stock_current_price = $transaction->stock->current_price;--}}

                                {{--$assignOnce++;--}}
                            {{--}--}}

                            {{--//Calculate the initial cost to the user for the stock, add it to total--}}
                            {{--$stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));--}}
                            {{--//Get the amount of stock owned for this transaction, add it to total--}}
                            {{--$stock_owned += $transaction->bought;--}}
                            {{--//Get the amount of stock sold for this transaction, add it to total--}}
                            {{--$stock_sold += $transaction->sold;--}}

                            {{--//$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));--}}

                            {{--//echo '<pre>';--}}
                            {{--//print_r($transaction->price);--}}
                            {{--//echo '</pre>';--}}
                        {{--}--}}

                        {{--//If the stock owned is less than 1 (it should never hit below 0)--}}
                        {{--//Then stock is not needed as it is not working for account in current state--}}
                        {{--if ($stock_owned <= 0 || $stock_sold == $stock_owned)--}}
                        {{--{--}}
                            {{--continue;--}}
                        {{--}--}}

                        {{--//Calculate the total amount of growth that the account has for this stock (overall NOT average)--}}
                        {{--$stock_total_growth = ($stock_total_cost / ($stock_owned - $stock_sold)) - $stock_current_price;--}}

                        {{--if ($stock_total_growth > 0.00)--}}
                            {{--$stock_total_growth *= -1;--}}
                            {{--//$stock_total_growth *= ($stock_owned - $stock_sold) * -1.00;--}}

                        {{--//Get the growth as a percentage--}}
                        {{--if (($stock_total_cost / ($stock_owned - $stock_sold)) == 0.00 ||--}}
                            {{--($stock_total_cost / ($stock_owned - $stock_sold)) == 0.0 || ($stock_total_cost / ($stock_owned - $stock_sold)) == 0)--}}
                            {{--continue;--}}
                        {{--$stock_total_growth_percentage = ((($stock_current_price / ($stock_total_cost / ($stock_owned - $stock_sold))) * 100) - 100) * -1;--}}

                        {{--//Add stock information to the holding table--}}
                        {{--echo '<tr>--}}
                                    {{--<td class="col-xs-1 " style="padding: 0px"><a href="' . "../stock/". $stock_symbol . '">' . $stock_symbol . '</a></td>--}}
                                    {{--<td class=col-xs-4" style="padding: 0px">' . $stock_name . '</td>--}}
                                    {{--<td class=col-xs-1" style="padding: 0px">$' . number_format($stock_total_cost, 2) . '</td>--}}
                                    {{--<td class=col-xs-1" style="padding: 0px">$' . number_format($stock_current_price, 2) . '</td>--}}
                                    {{--<td class=col-xs-2" style="padding: 0px">$' . number_format($stock_total_growth, 2) . ' (' . number_format($stock_total_growth_percentage, 2) . '%)' . '</td>--}}
                                    {{--<td class=col-xs-2" style="padding: 0px">' . ($stock_owned - $stock_sold) . '</td>--}}
                                    {{--<td class=col-xs-1" style="padding: 0px">' . '<a href="#">view</a>' . '</td>--}}
                                 {{--</tr>';--}}

                        {{--$allStocksTotalValue += $stock_current_price * ($stock_owned - $stock_sold);--}}
                        {{--$allStocksTotalCount += ($stock_owned - $stock_sold);--}}
                        {{--$stockCount++;--}}

                    {{--}--}}

                    {{--echo '</tbody></table>';--}}

                    {{--//Show the average Stock value of this Trade Account--}}
                    {{--echo '<div class="col-xs-12" style="padding-left: 0">';--}}
                    {{----}}
                    {{--if ($allStocksTotalCount > 0)--}}
                    {{--echo '<h4>Stock Average Value: $' . number_format(($allStocksTotalValue / $allStocksTotalCount),2) . 'AUD</h4>';--}}

                    {{--echo '</div>';--}}

                    {{--//Show the total Stock value of this Trade Account--}}
                    {{--echo '<div class="col-xs-12" style="padding-left: 0">';--}}

                    {{--echo '<h4>Stock Total Value: $' . number_format($allStocksTotalValue, 2) . 'AUD</h4>';--}}

                    {{--echo '</div>';--}}
                {{--@endphp--}}




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


        <div class="col-xs-12 ">

            <h2>Transactions</h2>

            <div id="slider" style="margin: 40px;"></div>

            {{--<input name="daterange" type="text" style="width: 100%; margin-bottom: 5%" />--}}

            <script>

                //Holder for Transaction Data, this is the data that get manipulated for sorting and API gets
                var transactionData = {};
                //Holder for the state of the Current Transaction Sort Column and Direction (asc or desc)
                var transactionSort = 0;

                var startD = new Date();
                startD.setDate(startD.getDate() - 21);

                var endD = new Date();
                endD.setDate(endD.getDate() + 365 - 21);

                var defaultStart = new Date();
                defaultStart.setHours(0);
                defaultStart.setMinutes(0);
                defaultStart.setSeconds(0);
                defaultStart.setMilliseconds(0);

                var defaultEnd = new Date();
                defaultEnd.setDate(defaultEnd.getDate() + 42);

                $("#slider").dateRangeSlider({
                    bounds: {
                        min: startD,
                        max: endD
                    },
                    defaultValues:{
                        min: defaultStart,
                        max: defaultEnd
                    },
                    formatter:function(val){
                        return moment(val).format('DD/MM/YYYY');
                    }
                });

                function updateTransactionsTable()
                {
                    //Remove the contents of the transactions table body (remove all rows except heading)
                    $('#transactionsTableBody tr').remove();

                    //Loop through all the returned transaction (with stock info) objects and fill the table body
                    for(var i = 0; i < transactionData.length; i++)
                    {

                        //Add the sold or bought attribute as a positive or negative integer adding the prop quantity
                        if (transactionData[i]["sold"] > 0)
                            transactionData[i]["quantity"] = transactionData[i]["sold"] * -1;
                        else
                            transactionData[i]["quantity"] = transactionData[i]["bought"];

                        //Add the next row after the last row that has been added
                        $('#transactionsTableBody').append(
                            '<tr>' +
                            '<td class=col-xs-3" style="padding: 0px">' + transactionData[i]["stock_symbol"] + '</td>' +
                            '<td class=col-xs-3" style="padding: 0px">'+ transactionData[i]["stock_name"] + ' (' + transactionData[i]["market"] + ')' + '</td>'   +
                            '<td class=col-xs-3" style="padding: 0px"> $' + transactionData[i]["price"] +'</td>' +
                            '<td class=col-xs-3" style="padding: 0px">' + (transactionData[i]["quantity"]<0?'':'+') + transactionData[i]["quantity"] +'</td>' +
                            '<td class=col-xs-3" style="padding: 0px">' + transactionData[i]["updated_at"] + '</td>'
                            + '</tr>'
                        );
                    }
                }

                //Universal Sort Function, used for now only with Transactions table headings
                function sortAbstract(a, b, prop) {
                    //If it is greater than 0, sort asc
                    if (transactionSort > 0)
                    {
                        return (a[prop] < b[prop]) ? 1 : ((a[prop] > b[prop]) ? -1 : 0);
                    }
                    //Otherwise sort desc
                    return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);

                }

                function sortTransactionData(by)
                {
                    //Set the value of transactionSort as needed
                    //Set TS to by with -1 if this is the first sort selection
                    if (transactionSort == 0)
                        transactionSort = by * -1;
                    //If TS and BY are the same value, make TS negative to sort desc
                    else if (transactionSort == by)
                        transactionSort *= -1;
                    //If TS is less than 0 (negative) set it to positive value BY
                    else if (transactionSort < 0)
                        transactionSort = by;
                    //Default to BY multiplied by -1
                    else
                        transactionSort = by * -1;

                    //JS sort function, uses Closure to iterate through all elements
                    //Use sortAbstract to do sort, using by as selector
                    transactionData = transactionData.sort(function (a, b) {
                        //Sort by Stock Code
                        if (by == 1)
                            return sortAbstract(a, b, "stock_symbol");

                        //Sort by Stock Name
                        if (by == 2)
                            return sortAbstract(a, b, "stock_name");

                        //Sort by Stock Purchased/Sold Price
                        if (by == 3)
                            return sortAbstract(a, b, "price");

                        //Sort by Quantity Bought/Sold (Sold first as it is negative)
                        if (by == 4)
                            return sortAbstract(a, b, "quantity");

                        //Sort by Transaction Timestamp
                        if (by == 5)
                            return sortAbstract(a, b, "timestamp");


                    });

                    //Update the Transactions Table with new data
                    updateTransactionsTable();

                }

                //Function to get data from api and update the transactions page with given information
                function getTransactionsForTable(postData)
                {
                    //Call the server to give a list of transactions that are within the User selected date range
                    $.post("{{url('api/getTransactionsInDateRange')}}", postData)
                        .done(function (data) {

                            //Set Transaction Data holder to the Data retrieved from API
                            transactionData = data;

                            //Update Transaction Table with API data, defaults to date asc sort
                            updateTransactionsTable();
                        })

                        .fail(function (error) {
                            console.log(error);
                        })
                    ;
                }

                $("#slider").bind("userValuesChanged", function(e, data){

                    //Change the min time to midnight of the selected day
                    var minEpoch = moment(data.values.min);
                    minEpoch.minute(0);
                    minEpoch.hour(0);
                    minEpoch.second();

                    minEpoch = minEpoch.unix();

                    //Change the max time to just before midnight of the next day
                    var maxEpoch = moment(data.values.max);
                    maxEpoch.hour(23);
                    maxEpoch.minute(59);
                    maxEpoch.second(59);

                    maxEpoch = maxEpoch.unix();

                    //Data holder that will be sent to the server
                    var postData = {};

                    //Assign data to the holder
                    postData["start"] = minEpoch;
                    postData["end"] = maxEpoch;
                    postData["trade_account_id"] = {{$tradeAccount->id}};

                    getTransactionsForTable(postData);
                });

                //When the page is loaded, show today's transactions
                $(document).ready(function () {
                    var dateValues = $("#dateSlider").dateRangeSlider("values");

                    var minEpoch = moment(defaultStart).unix();
                    var maxEpoch = moment(defaultEnd).unix();

                    console.log(minEpoch,maxEpoch);

                    //Data holder that will be sent to the server
                    var postData = {};

                    //Assign data to the holder
                    postData["start"] = minEpoch;
                    postData["end"] = maxEpoch;
                    postData["trade_account_id"] = {{$tradeAccount->id}};

                    getTransactionsForTable(postData);
                });
            </script>

            {{--Table to show list of transactions, searched above by date and filled with jQuery/Javascript--}}
            <table class="table col-xs-12 ">
                <thead>
                    <tr>
                        <td onclick="sortTransactionData(1)" class="col-xs-1 sort-header" style="padding: 0px">Code</td>
                        <td onclick="sortTransactionData(2)" class="col-xs-4 sort-header" style="padding: 0px">Name</td>
                        <td onclick="sortTransactionData(3)" class="col-xs-2 sort-header" style="padding: 0px">Price</td>
                        <td onclick="sortTransactionData(4)" class="col-xs-3 sort-header" style="padding: 0px">Purchased/Sold</td>
                        <td onclick="sortTransactionData(5)" class="col-xs-3 sort-header" style="padding: 0px">Date</td>
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
            </table>
            </div>
        </div>
    </div>
    <br/>

@include('layouts.footer')

</body>


