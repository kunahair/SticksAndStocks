
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
