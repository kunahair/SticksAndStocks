{{--/**--}}
{{--* Created by: Paul Davidson.--}}
{{--* Authors: Paul Davidson, Josh Gerlach and Abnezer Yhannes--}}
{{--*/--}}

@include('layouts.header')

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    {{--<script--}}
            {{--src="https://code.jquery.com/jquery-3.2.1.min.js"--}}
            {{--integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="--}}
            {{--crossorigin="anonymous"></script>--}}
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script src="{{url('js/jQDateRangeSlider-min.js')}}"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <link href="{{ url('css/iThing-min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css">

    <style type="text/css">
        .sort-header:hover {
            cursor: pointer;
        }
    </style>

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



        <div class="bg content-box">
        <h2 class="heading">{{$tradeAccount["name"]}}</h2>
        {{--<h3>${{number_format($tradeAccount["balance"], 2)}}</h3>--}}
        </div>
<div class=" content-box">
        <div id="trade-account-info-box" class="col-xs-10 col-md-12 " >



            <h2 >Held Stocks</h2>
            <div class="table-responsive">
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
                            <td class=col-xs-1" style="padding: 0px"><a href="{{url('/stock')}}/{{$stock["symbol"]}}">view</a></td>
                        </tr>

                    @endforeach

                </tbody>
            </table>
            </div>
            {{--Show Stock Average value (as per the original spec)--}}
            <div class="col-xs-12" style="padding-left: 0">
                <h4>Stock Average Value: ${{$tradeAccount->getCurrentStock()["stats"]["average_stock_value"]}}AUD</h4>
            </div>

            {{--Show the total stock value--}}
            <div class="col-xs-12" style="padding-left: 0">
                <h4>Stock Total Value: ${{$tradeAccount->getCurrentStock()["stats"]["total_stock_value"]}}AUD</h4>
            </div>


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
                        {
                            transactionData[i]["quantity"] = transactionData[i]["sold"] * -1;
                        }
                        else
                        {
                            transactionData[i]["quantity"] = transactionData[i]["bought"];
                        }


                        //Add the next row after the last row that has been added
                        $('#transactionsTableBody').append(
                            '<tr bgcolor="' + (transactionData[i]["quantity"]<0?'#f2a473':'#83e2bc')  + '">' +
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
            <div class="table-responsive">
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
                </tbody>
            </table>
            </div>
            </div>
        </div>
    </div>
    <br/>

@include('layouts.footer')



