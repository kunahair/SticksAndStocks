
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

<div class="container">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="/img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align="">
                <text style="font-size: 300%;">Pineapple</text>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <form class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">View Portfolio</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">View Profile</a></li>
                            <li><a href="#">Account Dashboard</a></li>
                            <li><a href="#"></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <nav>

    </nav>


    <div class="box">
    </div>

    @section('charter')
        <div class="stock">
            <h2 style='float:left; font-family: "Raleway", sans-serif;'>{{ $stock->stock_name }}</h2>
            <h4 style="font-family: 'Raleway', sans-serif; float:right;">{{ date('d/m/y') }}</h4>
            <br/>
            <br/>
            <br/>

            <!--Current 1stock quick stats-->
            <div id="current-stock-price" style="padding: 0px;  margin-bottom: 3%; width: 50%">
                <text id="stock-current-price" style="font-size: 200%; font-weight: bold;"></text> <br />
                <text id="stock-movement"></text><text id="stock-movement-percentage"> (+4.00%)</text>
            </div>

            <!--Table to show quick stats about stock-->
            <!--In full screen mode the table is divided into two, side by side. when on mobile they are stacked-->
            <!--<div id="stock-stats-table" style="margin-bottom: 10%;">-->
            <div class="table-responsive" style="margin-bottom: 3%; border: none">
                <table class="col-xs-12 col-md-6 table-hover">
                    <tr class="danger">
                        <td class="col-xs-6" style="padding: 0px">Previous Close</td>
                        <td id="stock-previous-close" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Open</td>
                        <td id="stock-open" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Bid</td>
                        <td id="stock-bid"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Ask</td>
                        <td id="stock-ask" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Days's Range</td>
                        <td id="stock-days-range" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">52 Week Range</td>
                        <td id="stock-year-week-range" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Volume</td>
                        <td id="stock-volume" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                </table>

                <table class="col-xs-12 col-md-6 table-hover">
                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Average Volume</td>
                        <td id="stock-avg-volume" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Market Capitalisation</td>
                        <td id="stock-market-cap" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Beta</td>
                        <td id="stock-beta" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">PE Ratio</td>
                        <td id="stock-pe-ratio" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Dividend</td>
                        <td id="stock-dividend" class="col-xs-6" style="padding: 0px"></td>
                    </tr>

                    <tr>
                        <td class="col-xs-6" style="padding: 0px">Yield</td>
                        <td id="stock-yield" class="col-xs-6" style="padding: 0px"></td>
                    </tr>
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

        //Loop through each json object that contains the stocks data
        //Get the time and the average and plog on a graph
        $.each(dataAsJSON['{{date('d-m-y')}}'], function(index, value) {
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
                    borderWidth: 2,
                    lineTension: 0
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
        function htmlDecode(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        }
        $(document).ready(function () {
            //Blade syntax to get the stocks element that was passed by the Laravel controller, get the current data JSON string
            var dataAsString = '{{!! $current !!}}';
            //Convert the current data from String to JSON
            var dataAsJSON = JSON.parse(dataAsString.slice(1, -1));

            //Data to load for current stats
            var tableData = dataAsJSON;

            //Updated the top section of data
            $("text#stock-current-price").text(tableData.curr_price.price + "AUD");
            $("#stock-movement").text(tableData.curr_price.amount);
            $("#stock-movement-percentage").text(" (" + tableData.curr_price.percentage + ")");

            //Load table 1
            $("#stock-previous-close").text(tableData.curr_price.extraData.Previousclose);
            $("#stock-open").text(tableData.curr_price.extraData.Open);
            $("#stock-bid").text(tableData.curr_price.extraData.Bid);
            $("#stock-ask").text(tableData.curr_price.extraData.Ask);
            $("#stock-days-range").text(tableData.curr_price.extraData.Daysrange);
            $("#stock-year-week-range").text(tableData.curr_price.extraData.Yearweekrange);
            $("#stock-volume").text(tableData.curr_price.extraData.Volume);

            //Load table 2
            $("#stock-avg-volume").text(tableData.curr_price.extraData.Avgvolume);
            $("#stock-market-cap").text(tableData.curr_price.extraData.Marketcap);
            $("#stock-beta").text(tableData.curr_price.extraData.Beta);
            $("#stock-pe-ratio").text(tableData.curr_price.extraData.PEratio);
            $("#stock-dividend").text(tableData.curr_price.extraData.Dividend);
            $("#stock-yield").text(tableData.curr_price.extraData.Yield);
        });


    </script>

</div>
</body>

</html>
