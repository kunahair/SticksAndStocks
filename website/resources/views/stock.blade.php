
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

            {{--Only Users who are signed in can purchase stocks--}}
            @if(Auth::check())
            <div id="userBuyForm" style="margin-bottom: 5%">
                <h3>Buy Stock</h3>
                {{--User messages--}}
                <div id="buyError" class="alert alert-danger" style="display: none">There was an error</div>
                <div id="buySuccess" class="alert alert-success" style="display: none">Stock successfuly purchased</div>

                {{--Get the list of Users Trade Accounts and put into a selection box--}}
                <select>
                    @foreach(Auth::user()->tradingAccounts as $tradeAccount)
                        <option value="{{$tradeAccount->id}}" >{{$tradeAccount->name}} : ${{$tradeAccount->balance}}</option>
                    @endforeach
                </select>
                {{----}}
                <input id="stockQuantity" type="number" value="1000" name="quantity" />
                <button id="buyButton" name="buyButton" >Buy</button>
                <br />
                <p>$<lable id="buyStockTotal">170.00</lable></p>

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
                        if (stockQTY < 1)
                        {
                            $('#stockQuantity').val(1);
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
                        postData["quantity"] = parseFloat($('#buyStockTotal').text());

                        //AJAX to the API to add the new purchase
                        $.post("{{ url('api/addBuyTransaction') }}", postData)
                        //If all went well, show success message
                            .done(function(data) {
                                $('#buySuccess').text(data);
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
            </div>



            @endif
            <!--Table to show quick stats about stock-->
            <!--In full screen mode the table is divided into two, side by side. when on mobile they are stacked-->
            <!--<div id="stock-stats-table" style="margin-bottom: 10%;">-->
            <div class="table-responsive" style="margin-bottom: 3%; border: none">
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
        //Array that stores data to be shown in Stock Daily Graph
        var dataIn = [];
        //Blade syntax to get the stocks element that was passed by the Laravel controller, get the history JSON string
        var dataAsString = '{{!! $stock->history !!}}';
        //Convert the history data from String to JSON
        var dataAsJSON = JSON.parse(dataAsString.slice(1,-1));

        var date = "{{date("d-m-y")}}";

        if (!('{{date("d-m-y")}}' in dataAsJSON)) {
            date = Object.keys(dataAsJSON)[0];
            // date = '{{date("d-m-y",strtotime("-1 days"))}}';
        }

        //Loop through each json object that contains the stocks data
        //Get the time and the average and plog on a graph
        $.each(dataAsJSON[date], function(index, value) {
            //Get the time value that is going to be shown on the chart
            var time = "{{date('Y/m/d')}} " + value.time;
            //Get the average of the stock value at time to plot on graph
            var average = value.average;
            //Add the collected time and average to the dataIn array that is to be displayed on the graph
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
                            min: '{{date("Y/m/d")}} 10:00',
                            max: '{{date("Y/m/d")}} 16:00'
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
