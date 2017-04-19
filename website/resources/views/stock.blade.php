
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

<div class="container">

    <div class="box">
    </div>



    @section('charter')
        <div class="stock">
            <div class="col-xs-12" style="padding-left: 0">
                <h2 style='float:left; font-family: "Raleway", sans-serif;'>{{ $stock->stock_name }}</h2>
                <h4 style="font-family: 'Raleway', sans-serif; float:right;">{{ date('d/m/y') }}</h4>
            </div>

            <!--Current 1stock quick stats-->
            <div id="current-stock-price" style="padding: 0;  margin-bottom: 5%; width: 50%">
                <br />
                <div id="stock-current-price" class="col-xs-12s" style="font-size: 200%; font-weight: bold; ">{{$currentDataArray["curr_price"]["price"]}}</div>
                <div class="col-xs-12" style="padding-left: 0">
                    <text id="stock-movement">{{$currentDataArray["curr_price"]["amount"]}}</text>
                    <text id="stock-movement-percentage">&nbsp;({{$currentDataArray["curr_price"]["percentage"]}})</text>
                </div>
            </div>

            {{--Only Users who are signed in can purchase and sell stocks--}}
            @if(Auth::check())
              <!-- added a model for the buying stocks button-->
                <button type="button" class="btn btn-info btn-lg col-xs-12 col-md-8" data-toggle="modal" data-target="#userBuyForm">Open Modal</button>
                <!-- model layout-->
                <div id="userBuyForm" class="modal fade" role="dialog">
                    <div  class=" modal-content modal-dialog" >
                        <div class="modal-header">
                            <h3>Buy Stock</h3>
                            <div class="move-right">
                                <h4>Total Price: $<lable id="buyStockTotal">{{$stock->current_price}}</lable></h4></div>
                        </div>


                        {{--Get the list of Users Trade Accounts and put into a selection box--}}
                        <div class="modal-body">
                            <h4> Account:</h4>
                            <select class="form-control">
                                @foreach(Auth::user()->tradingAccounts as $tradeAccount)
                                    <option value="{{$tradeAccount->id}}" >{{$tradeAccount->name}} : ${{$tradeAccount->balance}}</option>
                                @endforeach
                            </select><br/>

                            <h4>Quantity:</h4>
                            <input class="form-control" id="stockQuantity" type="number" value="1" name="quantity" />
                        </div>
                        <div class="modal-footer">
                            <button id="buyButton" name="buyButton" >Buy</button>
                        </div>
                        {{--User messages--}}
                        <div id="buyError" class="alert alert-danger" style="display: none">There was an error</div>
                        <div id="buySuccess" class="alert alert-success" style="display: none">Stock successfully purchased</div>

                        <!-- modal layout Finished-->
                        <script>
                            //Needed to have user info calls on the server side
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            //Constant that has the current value of the current stock
                            var curr_value = {{$currentDataArray["curr_price"]["price"] }};
                            //Get list of all the Users Trade Accounts
                            var tradeAccounts = JSON.parse(htmlDecode("{{Auth::user()->tradingAccounts}}"));
                            //Get the stock ID of the current stock
                            var stock_id = parseInt("{{$stock->id}}");


                            //When User updates the quantity, update the cost
                            $('#stockQuantity').on('input', function() {
                                //Get the quantity in the field
                                var stockQTY = $('#stockQuantity').val();
                                var stockQTY = parseInt(stockQTY);

                                //If the stock is less than 1, set to 1 and return
                                if (stockQTY < 1 || isNaN(stockQTY))
                                {
                                    $('#stockQuantity').val(1);
                                    //Update the cost for the user
                                    $('#buyStockTotal').text((curr_value * 1).toFixed(2));
                                    return;
                                }

                                //Update the cost for the user
                                $('#buyStockTotal').text((curr_value * stockQTY).toFixed(2));

                            });

                            //When user buys, do some client side checking then send relevant info to the server for processing
                            $('#buyButton').click(function() {
                                //Data to be sent to server
                                var postData = {};

                                //The selected Trade account holder
                                var selectedTradeAccount = {};

                                //Get the id of the selected Trade Account
                                var selectedValue = $('select').val();

                                //Find the selected Trade Account to check and send relevant info in the POST data
                                for (var i = 0; i < tradeAccounts.length; i++)
                                {
                                    if (tradeAccounts[i]["id"] == selectedValue)
                                    {
                                        postData["TradeAccountId"] = tradeAccounts[i]["id"];
                                        selectedTradeAccount = tradeAccounts[i];
                                        break;
                                    }
                                }

                                //Make sure that the Trade account is valid (selected, not null)
                                if (postData["TradeAccountId"] == undefined || postData["TradeAccountId"] == null)
                                {
                                    $('#buyError').text("There is an error with the Trade Account that you selected");
                                    $('#buyError').css('display', 'block');
                                    return;
                                }
                                //Check that the Trade Account balance is enough to cover the purchase, show error if not
                                else if (selectedTradeAccount["balance"] < parseFloat($('#buyStockTotal').text()))
                                {
                                    $('#buyError').text("You don't have enough in your trading account balance to purchase this quantity");
                                    $('#buyError').css('display', 'block');
                                    return;
                                }

                                //Make sure there is no error being displayed
                                $('#buyError').css('display', 'none');

                                //Put stock ID into the Data bundle
                                postData["stock_id"] = stock_id;
                                //Put the quantity that is to be purchased in the Data bundle
                                postData["quantity"] = parseInt($('#stockQuantity').val());

                                //AJAX to the API to add the new purchase
                                $.post("{{ url('api/addBuyTransaction') }}", postData)
                                //If all went well, show success message
                                    .done(function(data) {
                                        $('#buySuccess').text('Stock purchased!');
                                        $('#buySuccess').css('display', 'block');
                                    })

                                    //If there are any errors, or the request fails, log it and show an error
                                    .fail(function(error){
                                        console.log(error);
                                        $('#buyError').text(error["responseText"]);
                                        $('#buyError').css('display', 'block');
                                    })
                                ;

                            });
                        </script>
                        <!-- closing div for modal-->
                    </div>
                </div>

            {{--Sell User From--}}
            <div id="userSellForm" class="col-xs-12 col-md-6" style="margin-bottom: 5%">
                <h3>Sell Stock</h3>
                {{--User messages--}}


                {{--Get the list of Users Trade Accounts and put into a selection box--}}
                <select id="sellTradeAccounts">
                    @foreach(Auth::user()->tradingAccounts as $tradeAccount)
                        <option value="{{$tradeAccount->id}}" >{{$tradeAccount->name}} : ${{$tradeAccount->balance}}</option>
                    @endforeach
                </select>
                <input id="sellStockQuantity" type="number" value="1" name="quantity" />
                <button id="sellButton" name="sellButton" >Sell</button>
                <br />
                <p>Stock Held: <text id="sellStockHeld"></text></p>

                <div id="sellError" class="alert alert-danger" style="display: none">There was an error</div>
                <div id="sellSuccess" class="alert alert-success" style="display: none">Stock successfully sold</div>
                <script>

                    //Cache for the Stock Held values to save constantly calling API, stored by Trade Account ID
                    var tradeAccountStocks = {};

                    //Listener on the Sell Trade Account Selector, gets the Stock Held count and displays it to user
                    $('#sellTradeAccounts').change(function () {

                        //Blank the Stock Held while loading
                        $('#sellStockHeld').text('');

                        //Get the newly selected Trade Account ID
                        var tradeAccountId = parseInt($('#sellTradeAccounts').val());

                        //If the Trade account has been selected before, if not get it from the API, otherwise pull from cache
                        if (tradeAccountStocks[tradeAccountId] == undefined)
                            getStockCount(parseInt($('#sellTradeAccounts').val()));
                        else
                            $('#sellStockHeld').text(tradeAccountStocks[tradeAccountId]);

                    });

                    //AJAX call to get the stock held count for the current stock view on the selected Trade Account
                    function getStockCount(tradeAccountId) {
                        var postData = {};

                        //Put stock ID into the Data bundle
                        postData["stock_id"] = stock_id;
                        postData["trade_account_id"] = tradeAccountId;

                        $.post("{{ url('api/getTradeAccountStockQuantity') }}", postData)
                        //If all good, then change the Stock Held text and make sure the error message is gone
                            .done(function (data) {
                                $('#sellStockHeld').text(data);
                                $('#sellError').css('display', 'none');
                                tradeAccountStocks[tradeAccountId] = data;
                            })
                            //If there is an error, display error message to user
                            .fail(function (error) {
                                $('#sellError').css('display', 'block').text('An error occurred getting Trade Account details');
                            })
                        ;
                    }
                    //Load up the Stocks Held on the first item in the Selection Box
                    getStockCount(parseInt($('#sellTradeAccounts').val()));

                    //When user clicks sell button, do some client side validation then post to the server to process
                    $('#sellButton').click(function () {
                        //Get the quantity the user want to sell from the input box
                        var stockQuantityToSell = parseInt($('#sellStockQuantity').val());
                        //Get the stock held by the currently selected trading account
                        //TODO: might change this to the tradeAccountStockCounter holder
                        var stockHeld = parseInt($('#sellStockHeld').text());

                        //If the quantity to sell is not valid number or is below 1, show an error message and return
                        if (isNaN(stockQuantityToSell) || stockHeld < 1)
                        {
                            $('#sellError').css('display', 'block').text("Sell quantity must be above 1");
                            return;
                        }

                        //If the stock held is not a number, then display message and return
                        if (isNaN(stockHeld) )
                        {
                            $('#sellError').css('display', 'block').text("Sell quantity must be above 1");
                            return;
                        }

                        //If the quantity to sell is below the stock held, show an error and return
                        if (stockHeld < stockQuantityToSell)
                        {
                            $('#sellError').css('display', 'block').text("Sell quantity must be equal or lower than Stock Held");
                            return;
                        }

                        var postData = {};

                        //Get the currently selected Trade Account and put into postData
                        postData["trade_account_id"] = parseInt($('#sellTradeAccounts').val());
                        //The current stock ID, put into postData
                        postData["stock_id"] = stock_id;
                        //Add the quantity to be sold to the postData
                        postData["quantity"] = stockQuantityToSell;

                        console.log(postData);

                        //AJAX to the API to add the new sell
                        $.post("{{ url('api/addSellTransaction') }}", postData)
                        //If all good, hide the error message and show success message
                            .done(function (data) {
                                $('#sellSuccess').css('display', 'block').text('Sale Successful');
                                $('#sellError').css('display', 'none');

                                window.setTimeout(function () {
                                    location.reload();
                                }, 1500);
                            })
                            //If there is an error, display error message to user
                            .fail(function (error) {
                                console.log(error);
                                $('#sellError').css('display', 'block').text(error["responseText"]);
                                $('#sellSuccess').css('display', 'none');
                            })
                        ;

                    });

                </script>

            </div>



            @endif
            <!--Table to show quick stats about stock-->
            <!--In full screen mode the table is divided into two, side by side. when on mobile they are stacked-->
            <!--<div id="stock-stats-table" style="margin-bottom: 10%;">-->
            <div class="table-responsive col-xs-12" style="margin-bottom: 3%; border: none">
                <table class="col-xs-12 col-md-6 table-hover">

                    {{--Loop through the first half of the current data array and populate the left side of the table--}}
                    @for($i = 0; $i < count($currentDataArray["curr_price"]["extraData"])/2; $i++)

                        <tr>
                            <td class="col-xs-6" style="padding: 0px">{{$currentDataArray["curr_price"]["extraData"][$i]["title"]}}</td>
                            <td class="col-xs-6" style="padding: 0px">{{$currentDataArray["curr_price"]["extraData"][$i]["value"]}}</td>
                        </tr>

                    @endfor

                </table>

                <table class="col-xs-12 col-md-6 table-hover">

                    {{--Loop through the second half of the current data array and populate the right side of the table--}}
                    @for($i = count($currentDataArray["curr_price"]["extraData"])/2; $i < count($currentDataArray["curr_price"]["extraData"]); $i++)
                        <tr>
                            <td class="col-xs-6" style="padding: 0px">{{$currentDataArray["curr_price"]["extraData"][$i]["title"]}}</td>
                            <td class="col-xs-6" style="padding: 0px">{{$currentDataArray["curr_price"]["extraData"][$i]["value"]}}</td>
                        </tr>
                    @endfor
                </table>
            </div>

        <!--<h4 style='font-family: "Raleway", sans-serif;'>{{ $stock->stock_symbol }}.AX</h4>-->


            <div class="col-xs-1 col-md-2"></div>
            <div class="col-xs-10 col-md-8" style="margin: auto">
                <!--style="width: 500px;">-->
                <canvas id='chart'></canvas>
            </div>
            <div class="col-xs-1 col-md-2"></div>
        </div>
        <div id='data'>
        </div>
    @show

    <script>

        //Convenience function to add a 0 to the front of an integer, used for date formatting
        function addZero(i)
        {
            if (i < 10) {
                i = '0' + i;
            }
            return i;
        }

        //Convenience function to convert the year that a getYear function returns, converts it to 20xx
        function fixYear(i)
        {
            return (i - 100) + 2000;
        }

        //Array that stores data to be shown in Stock Daily Graph
        var dataIn = [];

        //Blade syntax to get the stocks element that was passed by the Laravel controller, get the history JSON string
        {{--var dataAsString = '{{!! $stock->history !!}}';--}}
        {{--//Convert the history data from String to JSON--}}
        {{--var dataAsJSON = JSON.parse(dataAsString.slice(1,-1));--}}

        {{--var date = "{{date("d-m-y")}}";--}}

        {{--if (!('{{date("d-m-y")}}' in dataAsJSON)) {--}}
            {{--date = Object.keys(dataAsJSON)[0];--}}
            {{--// date = '{{date("d-m-y",strtotime("-1 days"))}}';--}}
        {{--}--}}

        {{--//Loop through each json object that contains the stocks data--}}
        {{--//Get the time and the average and plog on a graph--}}
        {{--$.each(dataAsJSON[date], function(index, value) {--}}
            {{--//Get the time value that is going to be shown on the chart--}}
            {{--var time = "{{date('Y/m/d')}} " + value.time;--}}

            {{--console.log(time);--}}
            {{--//Get the average of the stock value at time to plot on graph--}}
            {{--var average = value.average;--}}
            {{--//Add the collected time and average to the dataIn array that is to be displayed on the graph--}}
            {{--dataIn.push({x: time, y: average});--}}
        {{--});--}}

        {{--Get Stock History from DB and convert to JSON--}}
        var stockHistoriesString = htmlDecode("{{$stock->getHistory}}");
        var stockHistoriesJSON = JSON.parse(stockHistoriesString);

//      Loop through the stockHistoriesJSON and convert time into ChartJS format, and add time and average to ChartJS data
        $.each(stockHistoriesJSON, function (index, value) {
            var date = new Date();
            date.setTime(value["timestamp"] * 1000);

            //2017/04/18 14:31:21
            //Make string that ChartJS is expecting
            var time = fixYear(date.getYear()) + '/' + addZero(date.getMonth() + 1) + '/' + addZero(date.getDay()) + ' ' +
                    date.getHours() + ':' +  addZero(date.getMinutes()) + ':' + addZero(date.getSeconds());

            //Pull the average stock value from the history row
            var average = value["average"];

            //Add data to ChartJS JSON array
            dataIn.push({x: time, y: average});
        });

        //Populate the graph inside the selected canvas using the DataIn array and some settings
        var ctx = document.getElementById('chart');
        var stockValue = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    data: dataIn,
                    // pointStyle: "line",
                    lineTension: 0,
                    fill: false,
                    borderColor: '#039BE5',
                    borderWidth: 2
                }]
            },
            options: {
                animation: {
                    // easing: 'easeInOutBounce'
                },
                legend: {
                    display: false
                },
                elements: {
                    point: { radius: 0 }
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'hour',
                            displayFormats: {
                                hour: 'hh:mm a'
                            },
                            {{--min: '{{date("Y/m/d")}} 10:00',--}}
//                            max: dateToday.getYear() ' 16:00'
                        },
                        gridLines : {
                            display : false
                        }
                    }]
                }
            }
        });
    </script>

    <script type="application/javascript">
        //Method to turn HTML encoded entities into their string representation
        function htmlDecode(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        }

    </script>

</div>
</body>

</html>
