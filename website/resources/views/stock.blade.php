
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

    <!--<link rel="stylesheet" href="style.css"/>-->
    <style>
        .box{
            background-color: white;
            width:500px ;
            padding: 10px;
        }
        .box:hover{
            box-shadow: 4px 4px 2px #888888;
        }
    </style>
</head>

<body>
<div class="box">
@section('charter')
<div class="stock">
    <h2 style='float:left; font-family: "Raleway", sans-serif;'>National Australia Bank Ltd.</h2>
    <h4 style="font-family: 'Raleway', sans-serif; float:right; margin-right: 1400px;">22/03/2017</h4>
    <br/>
    <br/>
    <br/>

    <h4 style='font-family: "Raleway", sans-serif;'>ASX: NAB</h4>


    <div style="width: 500px;">
        <canvas id='chart'></canvas>
    </div>
</div>
<div id='data'>
</div>
@show
</div>

<script>
    var values = {}
    var dataIn = []

    $.each({{ stock }}, function(index, value) {
        var time = "2017/03/22 " + value.time;
        var average = value.average;
        dataIn.push({x: time, y: average});
    });

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
                        min: '2017/03/22 10:00',
                        max: '2017/03/22 16:00'
                    },
                    gridLines : {
                        display : false
                    }
                }]
            }
        }
    });
</script>


</body>

</html>
