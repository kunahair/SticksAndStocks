@extends("master")
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
    var input = {
        "NAB.AX": [
            {
                "time": "10:06:40",
                "average": 31.92,
                "close": 31.92,
                "high": 31.94,
                "low": 31.9,
                "open": 31.91,
                "volume": 215500
            },
            {
                "time": "10:07:01",
                "average": 31.9,
                "close": 31.86,
                "high": 31.94,
                "low": 31.86,
                "open": 31.925,
                "volume": 116500
            },
            {
                "time": "10:08:04",
                "average": 31.88,
                "close": 31.89,
                "high": 31.9,
                "low": 31.85,
                "open": 31.87,
                "volume": 22700
            },
            {
                "time": "10:09:00",
                "average": 31.87,
                "close": 31.88,
                "high": 31.89,
                "low": 31.85,
                "open": 31.86,
                "volume": 17700
            },
            {
                "time": "10:10:00",
                "average": 31.89,
                "close": 31.87,
                "high": 31.9,
                "low": 31.87,
                "open": 31.87,
                "volume": 15400
            },
            {
                "time": "10:11:58",
                "average": 31.88,
                "close": 31.87,
                "high": 31.89,
                "low": 31.87,
                "open": 31.88,
                "volume": 3700
            },
            {
                "time": "10:12:00",
                "average": 31.89,
                "close": 31.87,
                "high": 31.9,
                "low": 31.87,
                "open": 31.89,
                "volume": 11200
            },
            {
                "time": "10:14:12",
                "average": 31.87,
                "close": 31.86,
                "high": 31.875,
                "low": 31.86,
                "open": 31.86,
                "volume": 15400
            },
            {
                "time": "10:15:10",
                "average": 31.83,
                "close": 31.81,
                "high": 31.85,
                "low": 31.81,
                "open": 31.85,
                "volume": 78200
            },
            {
                "time": "10:16:52",
                "average": 31.78,
                "close": 31.77,
                "high": 31.8,
                "low": 31.76,
                "open": 31.8,
                "volume": 30200
            },
            {
                "time": "10:17:37",
                "average": 31.79,
                "close": 31.78,
                "high": 31.79,
                "low": 31.78,
                "open": 31.79,
                "volume": 5500
            },
            {
                "time": "10:18:21",
                "average": 31.79,
                "close": 31.78,
                "high": 31.8,
                "low": 31.78,
                "open": 31.8,
                "volume": 15100
            },
            {
                "time": "10:19:07",
                "average": 31.77,
                "close": 31.75,
                "high": 31.79,
                "low": 31.75,
                "open": 31.77,
                "volume": 13300
            },
            {
                "time": "10:20:06",
                "average": 31.72,
                "close": 31.71,
                "high": 31.74,
                "low": 31.69,
                "open": 31.74,
                "volume": 40200
            },
            {
                "time": "10:21:15",
                "average": 31.75,
                "close": 31.77,
                "high": 31.77,
                "low": 31.73,
                "open": 31.73,
                "volume": 8700
            },
            {
                "time": "10:22:58",
                "average": 31.75,
                "close": 31.75,
                "high": 31.77,
                "low": 31.73,
                "open": 31.76,
                "volume": 13200
            },
            {
                "time": "10:23:00",
                "average": 31.72,
                "close": 31.71,
                "high": 31.745,
                "low": 31.7,
                "open": 31.74,
                "volume": 58500
            },
            {
                "time": "10:24:04",
                "average": 31.73,
                "close": 31.72,
                "high": 31.74,
                "low": 31.71,
                "open": 31.73,
                "volume": 15600
            },
            {
                "time": "10:25:12",
                "average": 31.73,
                "close": 31.74,
                "high": 31.74,
                "low": 31.72,
                "open": 31.73,
                "volume": 2400
            },
            {
                "time": "10:26:05",
                "average": 31.76,
                "close": 31.76,
                "high": 31.77,
                "low": 31.745,
                "open": 31.745,
                "volume": 10700
            },
            {
                "time": "10:27:13",
                "average": 31.75,
                "close": 31.76,
                "high": 31.76,
                "low": 31.74,
                "open": 31.75,
                "volume": 3400
            },
            {
                "time": "10:28:22",
                "average": 31.77,
                "close": 31.77,
                "high": 31.78,
                "low": 31.75,
                "open": 31.77,
                "volume": 4700
            },
            {
                "time": "10:29:52",
                "average": 31.77,
                "close": 31.78,
                "high": 31.78,
                "low": 31.75,
                "open": 31.76,
                "volume": 6200
            },
            {
                "time": "10:30:12",
                "average": 31.75,
                "close": 31.73,
                "high": 31.765,
                "low": 31.73,
                "open": 31.76,
                "volume": 10600
            },
            {
                "time": "10:31:02",
                "average": 31.74,
                "close": 31.73,
                "high": 31.75,
                "low": 31.73,
                "open": 31.74,
                "volume": 5700
            },
            {
                "time": "10:32:11",
                "average": 31.75,
                "close": 31.75,
                "high": 31.75,
                "low": 31.74,
                "open": 31.74,
                "volume": 3900
            },
            {
                "time": "10:33:56",
                "average": 31.76,
                "close": 31.75,
                "high": 31.76,
                "low": 31.75,
                "open": 31.755,
                "volume": 2100
            },
            {
                "time": "10:34:14",
                "average": 31.77,
                "close": 31.77,
                "high": 31.77,
                "low": 31.76,
                "open": 31.76,
                "volume": 7300
            },
            {
                "time": "10:35:11",
                "average": 31.77,
                "close": 31.76,
                "high": 31.78,
                "low": 31.76,
                "open": 31.775,
                "volume": 9800
            },
            {
                "time": "10:36:54",
                "average": 31.78,
                "close": 31.785,
                "high": 31.785,
                "low": 31.77,
                "open": 31.77,
                "volume": 4300
            },
            {
                "time": "10:37:05",
                "average": 31.78,
                "close": 31.78,
                "high": 31.79,
                "low": 31.77,
                "open": 31.79,
                "volume": 13800
            },
            {
                "time": "10:38:59",
                "average": 31.79,
                "close": 31.795,
                "high": 31.8,
                "low": 31.77,
                "open": 31.77,
                "volume": 20100
            },
            {
                "time": "10:39:17",
                "average": 31.79,
                "close": 31.77,
                "high": 31.815,
                "low": 31.77,
                "open": 31.8,
                "volume": 22100
            },
            {
                "time": "10:40:39",
                "average": 31.78,
                "close": 31.785,
                "high": 31.785,
                "low": 31.77,
                "open": 31.775,
                "volume": 17600
            },
            {
                "time": "10:41:11",
                "average": 31.78,
                "close": 31.78,
                "high": 31.785,
                "low": 31.77,
                "open": 31.78,
                "volume": 19000
            },
            {
                "time": "10:42:19",
                "average": 31.79,
                "close": 31.79,
                "high": 31.795,
                "low": 31.775,
                "open": 31.775,
                "volume": 8300
            },
            {
                "time": "10:43:12",
                "average": 31.79,
                "close": 31.8,
                "high": 31.8,
                "low": 31.78,
                "open": 31.78,
                "volume": 1900
            },
            {
                "time": "10:44:36",
                "average": 31.79,
                "close": 31.8,
                "high": 31.8,
                "low": 31.785,
                "open": 31.79,
                "volume": 10800
            },
            {
                "time": "10:45:05",
                "average": 31.8,
                "close": 31.81,
                "high": 31.81,
                "low": 31.78,
                "open": 31.79,
                "volume": 15100
            },
            {
                "time": "10:46:38",
                "average": 31.82,
                "close": 31.82,
                "high": 31.82,
                "low": 31.82,
                "open": 31.82,
                "volume": 6700
            },
            {
                "time": "10:47:03",
                "average": 31.82,
                "close": 31.82,
                "high": 31.83,
                "low": 31.805,
                "open": 31.81,
                "volume": 9900
            },
            {
                "time": "10:49:58",
                "average": 31.81,
                "close": 31.81,
                "high": 31.81,
                "low": 31.81,
                "open": 31.81,
                "volume": 1000
            },
            {
                "time": "10:50:04",
                "average": 31.82,
                "close": 31.83,
                "high": 31.83,
                "low": 31.8,
                "open": 31.805,
                "volume": 13200
            },
            {
                "time": "10:51:30",
                "average": 31.84,
                "close": 31.84,
                "high": 31.84,
                "low": 31.84,
                "open": 31.84,
                "volume": 2100
            },
            {
                "time": "10:52:13",
                "average": 31.84,
                "close": 31.84,
                "high": 31.85,
                "low": 31.835,
                "open": 31.835,
                "volume": 9500
            },
            {
                "time": "10:53:06",
                "average": 31.85,
                "close": 31.84,
                "high": 31.85,
                "low": 31.84,
                "open": 31.85,
                "volume": 3400
            },
            {
                "time": "10:54:06",
                "average": 31.86,
                "close": 31.85,
                "high": 31.86,
                "low": 31.85,
                "open": 31.85,
                "volume": 7100
            },
            {
                "time": "10:55:28",
                "average": 31.86,
                "close": 31.85,
                "high": 31.87,
                "low": 31.84,
                "open": 31.86,
                "volume": 11200
            },
            {
                "time": "10:56:06",
                "average": 31.87,
                "close": 31.87,
                "high": 31.87,
                "low": 31.86,
                "open": 31.86,
                "volume": 15900
            },
            {
                "time": "10:57:46",
                "average": 31.86,
                "close": 31.86,
                "high": 31.865,
                "low": 31.86,
                "open": 31.865,
                "volume": 11500
            },
            {
                "time": "10:58:15",
                "average": 31.87,
                "close": 31.87,
                "high": 31.87,
                "low": 31.87,
                "open": 31.87,
                "volume": 14400
            },
            {
                "time": "10:59:34",
                "average": 31.87,
                "close": 31.865,
                "high": 31.88,
                "low": 31.865,
                "open": 31.88,
                "volume": 4800
            },
            {
                "time": "11:00:01",
                "average": 31.86,
                "close": 31.86,
                "high": 31.87,
                "low": 31.85,
                "open": 31.87,
                "volume": 16400
            },
            {
                "time": "11:01:10",
                "average": 31.86,
                "close": 31.87,
                "high": 31.87,
                "low": 31.855,
                "open": 31.855,
                "volume": 3900
            },
            {
                "time": "11:02:12",
                "average": 31.87,
                "close": 31.88,
                "high": 31.88,
                "low": 31.865,
                "open": 31.865,
                "volume": 10000
            },
            {
                "time": "11:03:00",
                "average": 31.87,
                "close": 31.87,
                "high": 31.875,
                "low": 31.86,
                "open": 31.875,
                "volume": 8800
            },
            {
                "time": "11:04:04",
                "average": 31.88,
                "close": 31.87,
                "high": 31.885,
                "low": 31.87,
                "open": 31.88,
                "volume": 9000
            },
            {
                "time": "11:05:03",
                "average": 31.85,
                "close": 31.845,
                "high": 31.865,
                "low": 31.84,
                "open": 31.86,
                "volume": 43800
            },
            {
                "time": "11:06:00",
                "average": 31.84,
                "close": 31.85,
                "high": 31.85,
                "low": 31.835,
                "open": 31.85,
                "volume": 35100
            },
            {
                "time": "11:07:03",
                "average": 31.84,
                "close": 31.835,
                "high": 31.85,
                "low": 31.835,
                "open": 31.84,
                "volume": 16000
            },
            {
                "time": "11:08:09",
                "average": 31.82,
                "close": 31.82,
                "high": 31.83,
                "low": 31.815,
                "open": 31.83,
                "volume": 26200
            },
            {
                "time": "11:09:17",
                "average": 31.82,
                "close": 31.815,
                "high": 31.83,
                "low": 31.815,
                "open": 31.83,
                "volume": 6800
            },
            {
                "time": "11:10:01",
                "average": 31.81,
                "close": 31.82,
                "high": 31.82,
                "low": 31.805,
                "open": 31.81,
                "volume": 6700
            },
            {
                "time": "11:11:20",
                "average": 31.82,
                "close": 31.82,
                "high": 31.83,
                "low": 31.81,
                "open": 31.815,
                "volume": 3600
            },
            {
                "time": "11:12:52",
                "average": 31.81,
                "close": 31.79,
                "high": 31.82,
                "low": 31.79,
                "open": 31.815,
                "volume": 16400
            },
            {
                "time": "11:13:59",
                "average": 31.8,
                "close": 31.8,
                "high": 31.81,
                "low": 31.795,
                "open": 31.8,
                "volume": 8600
            },
            {
                "time": "11:14:00",
                "average": 31.8,
                "close": 31.8,
                "high": 31.8,
                "low": 31.79,
                "open": 31.795,
                "volume": 6000
            },
            {
                "time": "11:15:58",
                "average": 31.78,
                "close": 31.79,
                "high": 31.79,
                "low": 31.775,
                "open": 31.79,
                "volume": 23200
            },
            {
                "time": "11:16:58",
                "average": 31.78,
                "close": 31.775,
                "high": 31.785,
                "low": 31.77,
                "open": 31.78,
                "volume": 5300
            },
            {
                "time": "11:17:03",
                "average": 31.78,
                "close": 31.77,
                "high": 31.78,
                "low": 31.77,
                "open": 31.77,
                "volume": 4800
            },
            {
                "time": "11:18:01",
                "average": 31.78,
                "close": 31.78,
                "high": 31.79,
                "low": 31.76,
                "open": 31.76,
                "volume": 15400
            },
            {
                "time": "11:19:47",
                "average": 31.79,
                "close": 31.785,
                "high": 31.79,
                "low": 31.78,
                "open": 31.79,
                "volume": 1400
            },
            {
                "time": "11:20:14",
                "average": 31.8,
                "close": 31.79,
                "high": 31.8,
                "low": 31.79,
                "open": 31.79,
                "volume": 3100
            },
            {
                "time": "11:21:09",
                "average": 31.79,
                "close": 31.785,
                "high": 31.79,
                "low": 31.785,
                "open": 31.785,
                "volume": 1100
            },
            {
                "time": "11:22:00",
                "average": 31.78,
                "close": 31.78,
                "high": 31.785,
                "low": 31.775,
                "open": 31.78,
                "volume": 13900
            },
            {
                "time": "11:23:12",
                "average": 31.78,
                "close": 31.77,
                "high": 31.79,
                "low": 31.765,
                "open": 31.785,
                "volume": 29100
            },
            {
                "time": "11:24:02",
                "average": 31.77,
                "close": 31.78,
                "high": 31.78,
                "low": 31.76,
                "open": 31.775,
                "volume": 15300
            },
            {
                "time": "11:25:31",
                "average": 31.8,
                "close": 31.79,
                "high": 31.8,
                "low": 31.79,
                "open": 31.79,
                "volume": 6900
            },
            {
                "time": "11:26:00",
                "average": 31.79,
                "close": 31.78,
                "high": 31.79,
                "low": 31.78,
                "open": 31.78,
                "volume": 10700
            },
            {
                "time": "11:27:01",
                "average": 31.78,
                "close": 31.775,
                "high": 31.79,
                "low": 31.775,
                "open": 31.79,
                "volume": 2700
            },
            {
                "time": "11:28:35",
                "average": 31.78,
                "close": 31.78,
                "high": 31.785,
                "low": 31.78,
                "open": 31.78,
                "volume": 3400
            },
            {
                "time": "11:29:07",
                "average": 31.79,
                "close": 31.79,
                "high": 31.79,
                "low": 31.785,
                "open": 31.785,
                "volume": 5400
            },
            {
                "time": "11:30:03",
                "average": 31.8,
                "close": 31.8,
                "high": 31.81,
                "low": 31.79,
                "open": 31.8,
                "volume": 6000
            },
            {
                "time": "11:31:02",
                "average": 31.8,
                "close": 31.8,
                "high": 31.81,
                "low": 31.79,
                "open": 31.81,
                "volume": 19000
            },
            {
                "time": "11:32:12",
                "average": 31.8,
                "close": 31.81,
                "high": 31.81,
                "low": 31.785,
                "open": 31.79,
                "volume": 16100
            },
            {
                "time": "11:33:40",
                "average": 31.81,
                "close": 31.81,
                "high": 31.815,
                "low": 31.8,
                "open": 31.815,
                "volume": 15200
            },
            {
                "time": "11:35:27",
                "average": 31.82,
                "close": 31.82,
                "high": 31.825,
                "low": 31.82,
                "open": 31.82,
                "volume": 16700
            },
            {
                "time": "11:36:09",
                "average": 31.81,
                "close": 31.805,
                "high": 31.82,
                "low": 31.805,
                "open": 31.815,
                "volume": 12200
            },
            {
                "time": "11:37:02",
                "average": 31.81,
                "close": 31.81,
                "high": 31.82,
                "low": 31.805,
                "open": 31.81,
                "volume": 8700
            },
            {
                "time": "11:38:12",
                "average": 31.81,
                "close": 31.81,
                "high": 31.81,
                "low": 31.805,
                "open": 31.805,
                "volume": 12000
            },
            {
                "time": "11:40:01",
                "average": 31.83,
                "close": 31.82,
                "high": 31.83,
                "low": 31.82,
                "open": 31.82,
                "volume": 3200
            },
            {
                "time": "11:41:17",
                "average": 31.82,
                "close": 31.82,
                "high": 31.82,
                "low": 31.81,
                "open": 31.81,
                "volume": 600
            },
            {
                "time": "11:42:58",
                "average": 31.8,
                "close": 31.81,
                "high": 31.815,
                "low": 31.79,
                "open": 31.815,
                "volume": 26400
            },
            {
                "time": "11:43:03",
                "average": 31.8,
                "close": 31.79,
                "high": 31.8,
                "low": 31.79,
                "open": 31.8,
                "volume": 4600
            },
            {
                "time": "11:44:06",
                "average": 31.81,
                "close": 31.81,
                "high": 31.81,
                "low": 31.8,
                "open": 31.8,
                "volume": 6000
            },
            {
                "time": "11:45:00",
                "average": 31.81,
                "close": 31.795,
                "high": 31.82,
                "low": 31.79,
                "open": 31.8,
                "volume": 20100
            },
            {
                "time": "11:46:40",
                "average": 31.79,
                "close": 31.8,
                "high": 31.8,
                "low": 31.78,
                "open": 31.79,
                "volume": 7200
            },
            {
                "time": "11:47:25",
                "average": 31.79,
                "close": 31.79,
                "high": 31.79,
                "low": 31.785,
                "open": 31.79,
                "volume": 5300
            },
            {
                "time": "11:48:34",
                "average": 31.79,
                "close": 31.78,
                "high": 31.8,
                "low": 31.78,
                "open": 31.8,
                "volume": 10500
            },
            {
                "time": "11:49:00",
                "average": 31.78,
                "close": 31.78,
                "high": 31.79,
                "low": 31.77,
                "open": 31.785,
                "volume": 40100
            },
            {
                "time": "11:50:14",
                "average": 31.79,
                "close": 31.79,
                "high": 31.79,
                "low": 31.79,
                "open": 31.79,
                "volume": 2400
            },
            {
                "time": "11:51:00",
                "average": 31.78,
                "close": 31.76,
                "high": 31.79,
                "low": 31.76,
                "open": 31.78,
                "volume": 26400
            },
            {
                "time": "11:52:06",
                "average": 31.77,
                "close": 31.76,
                "high": 31.77,
                "low": 31.76,
                "open": 31.77,
                "volume": 5900
            },
            {
                "time": "11:53:22",
                "average": 31.77,
                "close": 31.77,
                "high": 31.77,
                "low": 31.76,
                "open": 31.77,
                "volume": 7300
            },
            {
                "time": "11:54:25",
                "average": 31.76,
                "close": 31.76,
                "high": 31.76,
                "low": 31.76,
                "open": 31.76,
                "volume": 0
            },
            {
                "time": "11:55:07",
                "average": 31.77,
                "close": 31.77,
                "high": 31.77,
                "low": 31.765,
                "open": 31.77,
                "volume": 4500
            },
            {
                "time": "11:56:00",
                "average": 31.76,
                "close": 31.76,
                "high": 31.77,
                "low": 31.755,
                "open": 31.765,
                "volume": 14700
            },
            {
                "time": "11:57:20",
                "average": 31.76,
                "close": 31.755,
                "high": 31.77,
                "low": 31.75,
                "open": 31.77,
                "volume": 3300
            },
            {
                "time": "11:58:04",
                "average": 31.76,
                "close": 31.76,
                "high": 31.76,
                "low": 31.755,
                "open": 31.76,
                "volume": 9100
            },
            {
                "time": "11:59:04",
                "average": 31.76,
                "close": 31.75,
                "high": 31.76,
                "low": 31.75,
                "open": 31.75,
                "volume": 3100
            },
            {
                "time": "12:00:06",
                "average": 31.76,
                "close": 31.76,
                "high": 31.77,
                "low": 31.755,
                "open": 31.755,
                "volume": 13100
            },
            {
                "time": "12:01:52",
                "average": 31.77,
                "close": 31.765,
                "high": 31.765,
                "low": 31.765,
                "open": 31.765,
                "volume": 300
            },
            {
                "time": "12:02:12",
                "average": 31.77,
                "close": 31.765,
                "high": 31.77,
                "low": 31.76,
                "open": 31.76,
                "volume": 5600
            },
            {
                "time": "12:03:47",
                "average": 31.76,
                "close": 31.75,
                "high": 31.77,
                "low": 31.75,
                "open": 31.77,
                "volume": 5200
            },
            {
                "time": "12:04:13",
                "average": 31.75,
                "close": 31.74,
                "high": 31.76,
                "low": 31.74,
                "open": 31.76,
                "volume": 31500
            },
            {
                "time": "12:05:00",
                "average": 31.74,
                "close": 31.73,
                "high": 31.75,
                "low": 31.73,
                "open": 31.745,
                "volume": 13500
            },
            {
                "time": "12:06:03",
                "average": 31.74,
                "close": 31.74,
                "high": 31.74,
                "low": 31.735,
                "open": 31.74,
                "volume": 1400
            },
            {
                "time": "12:07:01",
                "average": 31.75,
                "close": 31.76,
                "high": 31.76,
                "low": 31.74,
                "open": 31.75,
                "volume": 4200
            },
            {
                "time": "12:08:32",
                "average": 31.76,
                "close": 31.755,
                "high": 31.755,
                "low": 31.755,
                "open": 31.755,
                "volume": 100
            },
            {
                "time": "12:09:03",
                "average": 31.76,
                "close": 31.755,
                "high": 31.77,
                "low": 31.75,
                "open": 31.76,
                "volume": 6000
            },
            {
                "time": "12:10:50",
                "average": 31.75,
                "close": 31.75,
                "high": 31.75,
                "low": 31.75,
                "open": 31.75,
                "volume": 3400
            },
            {
                "time": "12:12:01",
                "average": 31.75,
                "close": 31.74,
                "high": 31.755,
                "low": 31.74,
                "open": 31.755,
                "volume": 2800
            },
            {
                "time": "12:14:46",
                "average": 31.75,
                "close": 31.745,
                "high": 31.75,
                "low": 31.74,
                "open": 31.75,
                "volume": 1600
            },
            {
                "time": "12:15:59",
                "average": 31.73,
                "close": 31.73,
                "high": 31.745,
                "low": 31.72,
                "open": 31.74,
                "volume": 14200
            },
            {
                "time": "12:16:02",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.74,
                "volume": 6000
            },
            {
                "time": "12:17:00",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.745,
                "volume": 2900
            },
            {
                "time": "12:18:25",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.745,
                "volume": 9600
            },
            {
                "time": "12:19:01",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.73,
                "volume": 1700
            },
            {
                "time": "12:20:19",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.745,
                "volume": 8400
            },
            {
                "time": "12:21:35",
                "average": 31.75,
                "close": 31.74,
                "high": 31.75,
                "low": 31.74,
                "open": 31.75,
                "volume": 2700
            },
            {
                "time": "12:22:23",
                "average": 31.74,
                "close": 31.74,
                "high": 31.75,
                "low": 31.73,
                "open": 31.73,
                "volume": 7500
            },
            {
                "time": "12:23:13",
                "average": 31.74,
                "close": 31.73,
                "high": 31.74,
                "low": 31.73,
                "open": 31.73,
                "volume": 6100
            },
            {
                "time": "12:24:16",
                "average": 31.74,
                "close": 31.73,
                "high": 31.74,
                "low": 31.73,
                "open": 31.735,
                "volume": 5500
            },
            {
                "time": "12:25:43",
                "average": 31.73,
                "close": 31.73,
                "high": 31.73,
                "low": 31.72,
                "open": 31.73,
                "volume": 6200
            },
            {
                "time": "12:26:25",
                "average": 31.73,
                "close": 31.73,
                "high": 31.735,
                "low": 31.72,
                "open": 31.72,
                "volume": 8100
            },
            {
                "time": "12:27:03",
                "average": 31.72,
                "close": 31.72,
                "high": 31.73,
                "low": 31.71,
                "open": 31.72,
                "volume": 15400
            },
            {
                "time": "12:28:35",
                "average": 31.72,
                "close": 31.72,
                "high": 31.72,
                "low": 31.715,
                "open": 31.715,
                "volume": 1000
            },
            {
                "time": "12:30:45",
                "average": 31.72,
                "close": 31.725,
                "high": 31.725,
                "low": 31.71,
                "open": 31.715,
                "volume": 10600
            },
            {
                "time": "12:31:07",
                "average": 31.72,
                "close": 31.71,
                "high": 31.72,
                "low": 31.71,
                "open": 31.72,
                "volume": 11600
            },
            {
                "time": "12:32:13",
                "average": 31.72,
                "close": 31.725,
                "high": 31.725,
                "low": 31.715,
                "open": 31.715,
                "volume": 4400
            },
            {
                "time": "12:33:05",
                "average": 31.72,
                "close": 31.72,
                "high": 31.72,
                "low": 31.715,
                "open": 31.72,
                "volume": 3100
            },
            {
                "time": "12:34:15",
                "average": 31.72,
                "close": 31.72,
                "high": 31.725,
                "low": 31.715,
                "open": 31.725,
                "volume": 5100
            },
            {
                "time": "12:35:49",
                "average": 31.72,
                "close": 31.71,
                "high": 31.73,
                "low": 31.71,
                "open": 31.73,
                "volume": 12400
            },
            {
                "time": "12:36:02",
                "average": 31.72,
                "close": 31.72,
                "high": 31.725,
                "low": 31.71,
                "open": 31.72,
                "volume": 6000
            },
            {
                "time": "12:37:16",
                "average": 31.72,
                "close": 31.715,
                "high": 31.73,
                "low": 31.71,
                "open": 31.725,
                "volume": 4000
            },
            {
                "time": "12:38:04",
                "average": 31.72,
                "close": 31.71,
                "high": 31.725,
                "low": 31.71,
                "open": 31.72,
                "volume": 5800
            },
            {
                "time": "12:39:16",
                "average": 31.72,
                "close": 31.72,
                "high": 31.73,
                "low": 31.71,
                "open": 31.715,
                "volume": 9600
            },
            {
                "time": "12:40:39",
                "average": 31.73,
                "close": 31.725,
                "high": 31.73,
                "low": 31.72,
                "open": 31.73,
                "volume": 8900
            },
            {
                "time": "12:41:09",
                "average": 31.73,
                "close": 31.75,
                "high": 31.75,
                "low": 31.715,
                "open": 31.72,
                "volume": 30000
            },
            {
                "time": "12:42:11",
                "average": 31.76,
                "close": 31.755,
                "high": 31.77,
                "low": 31.75,
                "open": 31.76,
                "volume": 9800
            },
            {
                "time": "12:43:00",
                "average": 31.75,
                "close": 31.74,
                "high": 31.75,
                "low": 31.74,
                "open": 31.75,
                "volume": 7100
            },
            {
                "time": "12:44:05",
                "average": 31.75,
                "close": 31.76,
                "high": 31.76,
                "low": 31.745,
                "open": 31.745,
                "volume": 11100
            },
            {
                "time": "12:45:09",
                "average": 31.76,
                "close": 31.76,
                "high": 31.77,
                "low": 31.75,
                "open": 31.77,
                "volume": 11300
            },
            {
                "time": "12:46:24",
                "average": 31.76,
                "close": 31.76,
                "high": 31.77,
                "low": 31.755,
                "open": 31.77,
                "volume": 9800
            },
            {
                "time": "12:47:07",
                "average": 31.76,
                "close": 31.75,
                "high": 31.77,
                "low": 31.75,
                "open": 31.77,
                "volume": 10500
            },
            {
                "time": "12:48:54",
                "average": 31.76,
                "close": 31.765,
                "high": 31.77,
                "low": 31.75,
                "open": 31.755,
                "volume": 12600
            },
            {
                "time": "12:49:38",
                "average": 31.77,
                "close": 31.765,
                "high": 31.78,
                "low": 31.765,
                "open": 31.77,
                "volume": 5100
            },
            {
                "time": "12:50:09",
                "average": 31.76,
                "close": 31.75,
                "high": 31.765,
                "low": 31.75,
                "open": 31.76,
                "volume": 2600
            },
            {
                "time": "12:51:58",
                "average": 31.75,
                "close": 31.74,
                "high": 31.76,
                "low": 31.74,
                "open": 31.755,
                "volume": 3000
            },
            {
                "time": "12:52:06",
                "average": 31.76,
                "close": 31.76,
                "high": 31.76,
                "low": 31.75,
                "open": 31.75,
                "volume": 13200
            },
            {
                "time": "12:53:21",
                "average": 31.78,
                "close": 31.78,
                "high": 31.78,
                "low": 31.77,
                "open": 31.77,
                "volume": 9300
            },
            {
                "time": "12:54:20",
                "average": 31.77,
                "close": 31.77,
                "high": 31.775,
                "low": 31.77,
                "open": 31.775,
                "volume": 2200
            },
            {
                "time": "12:55:04",
                "average": 31.77,
                "close": 31.765,
                "high": 31.77,
                "low": 31.76,
                "open": 31.76,
                "volume": 500
            },
            {
                "time": "12:56:59",
                "average": 31.76,
                "close": 31.755,
                "high": 31.76,
                "low": 31.75,
                "open": 31.76,
                "volume": 8900
            },
            {
                "time": "12:57:06",
                "average": 31.75,
                "close": 31.74,
                "high": 31.755,
                "low": 31.74,
                "open": 31.75,
                "volume": 10700
            },
            {
                "time": "12:58:54",
                "average": 31.75,
                "close": 31.75,
                "high": 31.76,
                "low": 31.745,
                "open": 31.75,
                "volume": 7300
            },
            {
                "time": "12:59:50",
                "average": 31.75,
                "close": 31.73,
                "high": 31.76,
                "low": 31.73,
                "open": 31.76,
                "volume": 14300
            },
            {
                "time": "13:00:01",
                "average": 31.74,
                "close": 31.72,
                "high": 31.75,
                "low": 31.72,
                "open": 31.74,
                "volume": 7600
            },
            {
                "time": "13:01:13",
                "average": 31.73,
                "close": 31.72,
                "high": 31.73,
                "low": 31.72,
                "open": 31.725,
                "volume": 4200
            },
            {
                "time": "13:02:58",
                "average": 31.73,
                "close": 31.725,
                "high": 31.73,
                "low": 31.72,
                "open": 31.725,
                "volume": 10000
            },
            {
                "time": "13:03:22",
                "average": 31.73,
                "close": 31.72,
                "high": 31.73,
                "low": 31.72,
                "open": 31.73,
                "volume": 5600
            },
            {
                "time": "13:04:23",
                "average": 31.73,
                "close": 31.735,
                "high": 31.735,
                "low": 31.72,
                "open": 31.725,
                "volume": 4200
            },
            {
                "time": "13:05:07",
                "average": 31.73,
                "close": 31.72,
                "high": 31.73,
                "low": 31.72,
                "open": 31.73,
                "volume": 1500
            },
            {
                "time": "13:06:37",
                "average": 31.73,
                "close": 31.725,
                "high": 31.73,
                "low": 31.72,
                "open": 31.73,
                "volume": 4000
            },
            {
                "time": "13:07:19",
                "average": 31.73,
                "close": 31.73,
                "high": 31.73,
                "low": 31.73,
                "open": 31.73,
                "volume": 3000
            },
            {
                "time": "13:08:20",
                "average": 31.72,
                "close": 31.71,
                "high": 31.73,
                "low": 31.71,
                "open": 31.72,
                "volume": 16300
            },
            {
                "time": "13:09:22",
                "average": 31.73,
                "close": 31.72,
                "high": 31.73,
                "low": 31.72,
                "open": 31.72,
                "volume": 9100
            },
            {
                "time": "13:10:02",
                "average": 31.7,
                "close": 31.68,
                "high": 31.71,
                "low": 31.68,
                "open": 31.71,
                "volume": 177800
            },
            {
                "time": "13:11:01",
                "average": 31.67,
                "close": 31.66,
                "high": 31.68,
                "low": 31.65,
                "open": 31.67,
                "volume": 15800
            },
            {
                "time": "13:13:04",
                "average": 31.67,
                "close": 31.665,
                "high": 31.67,
                "low": 31.66,
                "open": 31.67,
                "volume": 2200
            },
            {
                "time": "13:14:02",
                "average": 31.66,
                "close": 31.66,
                "high": 31.665,
                "low": 31.66,
                "open": 31.66,
                "volume": 4000
            },
            {
                "time": "13:15:02",
                "average": 31.67,
                "close": 31.665,
                "high": 31.68,
                "low": 31.665,
                "open": 31.67,
                "volume": 9500
            },
            {
                "time": "13:16:20",
                "average": 31.67,
                "close": 31.675,
                "high": 31.675,
                "low": 31.66,
                "open": 31.66,
                "volume": 3700
            },
            {
                "time": "13:17:07",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.67,
                "open": 31.67,
                "volume": 2300
            },
            {
                "time": "13:18:14",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.69,
                "volume": 3400
            },
            {
                "time": "13:19:24",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.685,
                "volume": 5200
            },
            {
                "time": "13:20:44",
                "average": 31.68,
                "close": 31.67,
                "high": 31.69,
                "low": 31.67,
                "open": 31.69,
                "volume": 1700
            },
            {
                "time": "13:21:01",
                "average": 31.67,
                "close": 31.67,
                "high": 31.675,
                "low": 31.67,
                "open": 31.675,
                "volume": 3800
            },
            {
                "time": "13:22:01",
                "average": 31.68,
                "close": 31.67,
                "high": 31.68,
                "low": 31.67,
                "open": 31.68,
                "volume": 800
            },
            {
                "time": "13:23:03",
                "average": 31.68,
                "close": 31.675,
                "high": 31.68,
                "low": 31.675,
                "open": 31.675,
                "volume": 1400
            },
            {
                "time": "13:24:19",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.68,
                "open": 31.68,
                "volume": 4100
            },
            {
                "time": "13:25:07",
                "average": 31.68,
                "close": 31.68,
                "high": 31.69,
                "low": 31.675,
                "open": 31.69,
                "volume": 5500
            },
            {
                "time": "13:26:03",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.69,
                "volume": 6200
            },
            {
                "time": "13:27:24",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.685,
                "volume": 1900
            },
            {
                "time": "13:28:56",
                "average": 31.68,
                "close": 31.675,
                "high": 31.69,
                "low": 31.665,
                "open": 31.685,
                "volume": 9900
            },
            {
                "time": "13:29:11",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.67,
                "open": 31.67,
                "volume": 1400
            },
            {
                "time": "13:30:19",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.66,
                "open": 31.665,
                "volume": 6500
            },
            {
                "time": "13:31:57",
                "average": 31.67,
                "close": 31.665,
                "high": 31.68,
                "low": 31.665,
                "open": 31.68,
                "volume": 3100
            },
            {
                "time": "13:32:02",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.67,
                "open": 31.67,
                "volume": 4700
            },
            {
                "time": "13:33:04",
                "average": 31.67,
                "close": 31.66,
                "high": 31.67,
                "low": 31.66,
                "open": 31.67,
                "volume": 20200
            },
            {
                "time": "13:34:44",
                "average": 31.67,
                "close": 31.665,
                "high": 31.665,
                "low": 31.665,
                "open": 31.665,
                "volume": 400
            },
            {
                "time": "13:35:00",
                "average": 31.66,
                "close": 31.655,
                "high": 31.66,
                "low": 31.655,
                "open": 31.66,
                "volume": 3200
            },
            {
                "time": "13:36:02",
                "average": 31.66,
                "close": 31.65,
                "high": 31.665,
                "low": 31.65,
                "open": 31.66,
                "volume": 7500
            },
            {
                "time": "13:37:00",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.66,
                "open": 31.66,
                "volume": 3600
            },
            {
                "time": "13:38:03",
                "average": 31.66,
                "close": 31.65,
                "high": 31.66,
                "low": 31.65,
                "open": 31.66,
                "volume": 17600
            },
            {
                "time": "13:39:05",
                "average": 31.64,
                "close": 31.64,
                "high": 31.645,
                "low": 31.63,
                "open": 31.64,
                "volume": 10500
            },
            {
                "time": "13:40:31",
                "average": 31.64,
                "close": 31.64,
                "high": 31.645,
                "low": 31.63,
                "open": 31.63,
                "volume": 2900
            },
            {
                "time": "13:41:19",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.645,
                "open": 31.65,
                "volume": 5800
            },
            {
                "time": "13:42:05",
                "average": 31.64,
                "close": 31.64,
                "high": 31.645,
                "low": 31.64,
                "open": 31.64,
                "volume": 3100
            },
            {
                "time": "13:43:27",
                "average": 31.64,
                "close": 31.63,
                "high": 31.645,
                "low": 31.63,
                "open": 31.645,
                "volume": 3000
            },
            {
                "time": "13:44:27",
                "average": 31.65,
                "close": 31.64,
                "high": 31.65,
                "low": 31.64,
                "open": 31.64,
                "volume": 10500
            },
            {
                "time": "13:45:29",
                "average": 31.64,
                "close": 31.64,
                "high": 31.645,
                "low": 31.64,
                "open": 31.645,
                "volume": 2700
            },
            {
                "time": "13:46:29",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.64,
                "open": 31.645,
                "volume": 10300
            },
            {
                "time": "13:47:08",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.64,
                "open": 31.66,
                "volume": 4700
            },
            {
                "time": "13:49:14",
                "average": 31.66,
                "close": 31.65,
                "high": 31.67,
                "low": 31.645,
                "open": 31.655,
                "volume": 8000
            },
            {
                "time": "13:50:21",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.645,
                "open": 31.645,
                "volume": 3100
            },
            {
                "time": "13:51:10",
                "average": 31.64,
                "close": 31.64,
                "high": 31.645,
                "low": 31.64,
                "open": 31.64,
                "volume": 3400
            },
            {
                "time": "13:52:28",
                "average": 31.64,
                "close": 31.64,
                "high": 31.65,
                "low": 31.63,
                "open": 31.63,
                "volume": 4600
            },
            {
                "time": "13:53:31",
                "average": 31.64,
                "close": 31.64,
                "high": 31.64,
                "low": 31.63,
                "open": 31.63,
                "volume": 800
            },
            {
                "time": "13:54:01",
                "average": 31.65,
                "close": 31.64,
                "high": 31.65,
                "low": 31.64,
                "open": 31.65,
                "volume": 10900
            },
            {
                "time": "13:55:03",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.645,
                "open": 31.65,
                "volume": 6300
            },
            {
                "time": "13:56:24",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.645,
                "open": 31.645,
                "volume": 2300
            },
            {
                "time": "13:57:24",
                "average": 31.65,
                "close": 31.65,
                "high": 31.66,
                "low": 31.645,
                "open": 31.645,
                "volume": 2500
            },
            {
                "time": "13:58:14",
                "average": 31.65,
                "close": 31.64,
                "high": 31.66,
                "low": 31.64,
                "open": 31.655,
                "volume": 3900
            },
            {
                "time": "13:59:03",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.635,
                "open": 31.635,
                "volume": 6200
            },
            {
                "time": "14:00:00",
                "average": 31.65,
                "close": 31.645,
                "high": 31.65,
                "low": 31.645,
                "open": 31.65,
                "volume": 1800
            },
            {
                "time": "14:01:12",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.64,
                "open": 31.64,
                "volume": 8500
            },
            {
                "time": "14:02:38",
                "average": 31.64,
                "close": 31.64,
                "high": 31.64,
                "low": 31.64,
                "open": 31.64,
                "volume": 100
            },
            {
                "time": "14:03:10",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.645,
                "open": 31.645,
                "volume": 5400
            },
            {
                "time": "14:04:13",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.64,
                "open": 31.64,
                "volume": 8100
            },
            {
                "time": "14:05:01",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.64,
                "open": 31.66,
                "volume": 10800
            },
            {
                "time": "14:06:02",
                "average": 31.65,
                "close": 31.63,
                "high": 31.66,
                "low": 31.63,
                "open": 31.65,
                "volume": 12800
            },
            {
                "time": "14:07:00",
                "average": 31.64,
                "close": 31.64,
                "high": 31.64,
                "low": 31.635,
                "open": 31.635,
                "volume": 3000
            },
            {
                "time": "14:08:09",
                "average": 31.64,
                "close": 31.65,
                "high": 31.65,
                "low": 31.63,
                "open": 31.635,
                "volume": 10400
            },
            {
                "time": "14:09:24",
                "average": 31.66,
                "close": 31.65,
                "high": 31.66,
                "low": 31.65,
                "open": 31.66,
                "volume": 1900
            },
            {
                "time": "14:10:07",
                "average": 31.65,
                "close": 31.64,
                "high": 31.655,
                "low": 31.64,
                "open": 31.655,
                "volume": 2300
            },
            {
                "time": "14:11:06",
                "average": 31.65,
                "close": 31.645,
                "high": 31.645,
                "low": 31.645,
                "open": 31.645,
                "volume": 3400
            },
            {
                "time": "14:12:11",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.64,
                "open": 31.65,
                "volume": 5000
            },
            {
                "time": "14:13:59",
                "average": 31.65,
                "close": 31.645,
                "high": 31.645,
                "low": 31.645,
                "open": 31.645,
                "volume": 1200
            },
            {
                "time": "14:14:13",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.64,
                "open": 31.64,
                "volume": 13200
            },
            {
                "time": "14:15:20",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.66,
                "open": 31.665,
                "volume": 7500
            },
            {
                "time": "14:16:02",
                "average": 31.67,
                "close": 31.66,
                "high": 31.68,
                "low": 31.66,
                "open": 31.68,
                "volume": 5400
            },
            {
                "time": "14:17:54",
                "average": 31.67,
                "close": 31.66,
                "high": 31.67,
                "low": 31.66,
                "open": 31.67,
                "volume": 300
            },
            {
                "time": "14:18:01",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.66,
                "open": 31.67,
                "volume": 10400
            },
            {
                "time": "14:19:12",
                "average": 31.67,
                "close": 31.67,
                "high": 31.68,
                "low": 31.665,
                "open": 31.68,
                "volume": 6600
            },
            {
                "time": "14:20:59",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.68,
                "open": 31.68,
                "volume": 2600
            },
            {
                "time": "14:21:03",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.69,
                "volume": 3800
            },
            {
                "time": "14:22:21",
                "average": 31.68,
                "close": 31.67,
                "high": 31.68,
                "low": 31.67,
                "open": 31.675,
                "volume": 5600
            },
            {
                "time": "14:23:58",
                "average": 31.66,
                "close": 31.65,
                "high": 31.665,
                "low": 31.65,
                "open": 31.665,
                "volume": 3200
            },
            {
                "time": "14:24:02",
                "average": 31.66,
                "close": 31.66,
                "high": 31.67,
                "low": 31.65,
                "open": 31.655,
                "volume": 11100
            },
            {
                "time": "14:25:03",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.67,
                "open": 31.67,
                "volume": 2300
            },
            {
                "time": "14:26:07",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.68,
                "volume": 7700
            },
            {
                "time": "14:27:05",
                "average": 31.67,
                "close": 31.68,
                "high": 31.68,
                "low": 31.665,
                "open": 31.67,
                "volume": 5300
            },
            {
                "time": "14:28:35",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.67,
                "open": 31.67,
                "volume": 300
            },
            {
                "time": "14:29:00",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.67,
                "open": 31.675,
                "volume": 3100
            },
            {
                "time": "14:30:11",
                "average": 31.68,
                "close": 31.68,
                "high": 31.69,
                "low": 31.67,
                "open": 31.67,
                "volume": 3900
            },
            {
                "time": "14:31:10",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.69,
                "volume": 6300
            },
            {
                "time": "14:32:00",
                "average": 31.68,
                "close": 31.675,
                "high": 31.68,
                "low": 31.675,
                "open": 31.68,
                "volume": 4500
            },
            {
                "time": "14:33:07",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.68,
                "volume": 2300
            },
            {
                "time": "14:34:13",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.7,
                "volume": 1600
            },
            {
                "time": "14:35:09",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.7,
                "volume": 1100
            },
            {
                "time": "14:36:15",
                "average": 31.69,
                "close": 31.68,
                "high": 31.695,
                "low": 31.68,
                "open": 31.695,
                "volume": 5400
            },
            {
                "time": "14:37:03",
                "average": 31.69,
                "close": 31.685,
                "high": 31.69,
                "low": 31.685,
                "open": 31.69,
                "volume": 700
            },
            {
                "time": "14:38:21",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.68,
                "volume": 1400
            },
            {
                "time": "14:39:05",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.685,
                "volume": 11000
            },
            {
                "time": "14:40:18",
                "average": 31.7,
                "close": 31.7,
                "high": 31.7,
                "low": 31.695,
                "open": 31.695,
                "volume": 13300
            },
            {
                "time": "14:42:15",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.69,
                "volume": 17100
            },
            {
                "time": "14:43:20",
                "average": 31.69,
                "close": 31.69,
                "high": 31.695,
                "low": 31.685,
                "open": 31.685,
                "volume": 2800
            },
            {
                "time": "14:45:36",
                "average": 31.7,
                "close": 31.69,
                "high": 31.71,
                "low": 31.69,
                "open": 31.7,
                "volume": 12200
            },
            {
                "time": "14:46:04",
                "average": 31.7,
                "close": 31.695,
                "high": 31.7,
                "low": 31.695,
                "open": 31.695,
                "volume": 4000
            },
            {
                "time": "14:47:24",
                "average": 31.7,
                "close": 31.7,
                "high": 31.7,
                "low": 31.7,
                "open": 31.7,
                "volume": 4700
            },
            {
                "time": "14:48:36",
                "average": 31.69,
                "close": 31.685,
                "high": 31.7,
                "low": 31.685,
                "open": 31.695,
                "volume": 12000
            },
            {
                "time": "14:49:01",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.69,
                "volume": 10100
            },
            {
                "time": "14:50:24",
                "average": 31.7,
                "close": 31.695,
                "high": 31.695,
                "low": 31.695,
                "open": 31.695,
                "volume": 700
            },
            {
                "time": "14:51:13",
                "average": 31.69,
                "close": 31.69,
                "high": 31.695,
                "low": 31.685,
                "open": 31.69,
                "volume": 19300
            },
            {
                "time": "14:52:11",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.685,
                "volume": 16300
            },
            {
                "time": "14:53:39",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.67,
                "open": 31.67,
                "volume": 7000
            },
            {
                "time": "14:54:10",
                "average": 31.68,
                "close": 31.68,
                "high": 31.685,
                "low": 31.675,
                "open": 31.675,
                "volume": 1300
            },
            {
                "time": "14:55:21",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.685,
                "volume": 3700
            },
            {
                "time": "14:56:19",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.7,
                "volume": 3400
            },
            {
                "time": "14:57:14",
                "average": 31.7,
                "close": 31.7,
                "high": 31.7,
                "low": 31.7,
                "open": 31.7,
                "volume": 3300
            },
            {
                "time": "14:58:52",
                "average": 31.71,
                "close": 31.71,
                "high": 31.71,
                "low": 31.7,
                "open": 31.705,
                "volume": 12300
            },
            {
                "time": "14:59:56",
                "average": 31.7,
                "close": 31.7,
                "high": 31.7,
                "low": 31.695,
                "open": 31.7,
                "volume": 5600
            },
            {
                "time": "15:00:07",
                "average": 31.69,
                "close": 31.68,
                "high": 31.7,
                "low": 31.68,
                "open": 31.69,
                "volume": 15300
            },
            {
                "time": "15:01:01",
                "average": 31.67,
                "close": 31.67,
                "high": 31.68,
                "low": 31.66,
                "open": 31.67,
                "volume": 13800
            },
            {
                "time": "15:02:58",
                "average": 31.67,
                "close": 31.67,
                "high": 31.675,
                "low": 31.67,
                "open": 31.675,
                "volume": 1900
            },
            {
                "time": "15:03:29",
                "average": 31.68,
                "close": 31.675,
                "high": 31.675,
                "low": 31.675,
                "open": 31.675,
                "volume": 2200
            },
            {
                "time": "15:04:43",
                "average": 31.68,
                "close": 31.68,
                "high": 31.685,
                "low": 31.67,
                "open": 31.67,
                "volume": 15300
            },
            {
                "time": "15:05:33",
                "average": 31.67,
                "close": 31.675,
                "high": 31.675,
                "low": 31.67,
                "open": 31.67,
                "volume": 2100
            },
            {
                "time": "15:06:08",
                "average": 31.67,
                "close": 31.66,
                "high": 31.67,
                "low": 31.66,
                "open": 31.67,
                "volume": 11900
            },
            {
                "time": "15:07:36",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.65,
                "open": 31.65,
                "volume": 10500
            },
            {
                "time": "15:08:28",
                "average": 31.66,
                "close": 31.66,
                "high": 31.665,
                "low": 31.655,
                "open": 31.655,
                "volume": 8400
            },
            {
                "time": "15:09:59",
                "average": 31.68,
                "close": 31.685,
                "high": 31.69,
                "low": 31.67,
                "open": 31.67,
                "volume": 7900
            },
            {
                "time": "15:10:30",
                "average": 31.69,
                "close": 31.685,
                "high": 31.69,
                "low": 31.68,
                "open": 31.68,
                "volume": 1000
            },
            {
                "time": "15:11:04",
                "average": 31.68,
                "close": 31.675,
                "high": 31.68,
                "low": 31.67,
                "open": 31.68,
                "volume": 7700
            },
            {
                "time": "15:12:41",
                "average": 31.68,
                "close": 31.68,
                "high": 31.685,
                "low": 31.68,
                "open": 31.68,
                "volume": 10900
            },
            {
                "time": "15:13:02",
                "average": 31.68,
                "close": 31.68,
                "high": 31.69,
                "low": 31.675,
                "open": 31.685,
                "volume": 8800
            },
            {
                "time": "15:14:07",
                "average": 31.68,
                "close": 31.68,
                "high": 31.685,
                "low": 31.675,
                "open": 31.685,
                "volume": 15300
            },
            {
                "time": "15:15:47",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.69,
                "open": 31.69,
                "volume": 1000
            },
            {
                "time": "15:16:51",
                "average": 31.69,
                "close": 31.68,
                "high": 31.69,
                "low": 31.68,
                "open": 31.685,
                "volume": 3200
            },
            {
                "time": "15:17:45",
                "average": 31.69,
                "close": 31.685,
                "high": 31.69,
                "low": 31.68,
                "open": 31.69,
                "volume": 1000
            },
            {
                "time": "15:18:44",
                "average": 31.69,
                "close": 31.7,
                "high": 31.7,
                "low": 31.68,
                "open": 31.68,
                "volume": 14200
            },
            {
                "time": "15:19:12",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.69,
                "volume": 8000
            },
            {
                "time": "15:20:00",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.685,
                "volume": 5200
            },
            {
                "time": "15:21:09",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.685,
                "open": 31.685,
                "volume": 5500
            },
            {
                "time": "15:22:01",
                "average": 31.69,
                "close": 31.69,
                "high": 31.69,
                "low": 31.68,
                "open": 31.685,
                "volume": 23900
            },
            {
                "time": "15:23:55",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.7,
                "volume": 2100
            },
            {
                "time": "15:24:11",
                "average": 31.7,
                "close": 31.69,
                "high": 31.7,
                "low": 31.69,
                "open": 31.7,
                "volume": 6400
            },
            {
                "time": "15:25:06",
                "average": 31.68,
                "close": 31.68,
                "high": 31.69,
                "low": 31.67,
                "open": 31.685,
                "volume": 47700
            },
            {
                "time": "15:26:57",
                "average": 31.67,
                "close": 31.67,
                "high": 31.675,
                "low": 31.67,
                "open": 31.675,
                "volume": 2500
            },
            {
                "time": "15:27:03",
                "average": 31.67,
                "close": 31.665,
                "high": 31.68,
                "low": 31.665,
                "open": 31.68,
                "volume": 6700
            },
            {
                "time": "15:28:04",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.665,
                "open": 31.67,
                "volume": 4800
            },
            {
                "time": "15:29:04",
                "average": 31.67,
                "close": 31.665,
                "high": 31.665,
                "low": 31.665,
                "open": 31.665,
                "volume": 6900
            },
            {
                "time": "15:30:05",
                "average": 31.68,
                "close": 31.68,
                "high": 31.68,
                "low": 31.67,
                "open": 31.67,
                "volume": 21500
            },
            {
                "time": "15:31:04",
                "average": 31.68,
                "close": 31.675,
                "high": 31.68,
                "low": 31.675,
                "open": 31.675,
                "volume": 14700
            },
            {
                "time": "15:32:02",
                "average": 31.67,
                "close": 31.665,
                "high": 31.68,
                "low": 31.665,
                "open": 31.68,
                "volume": 11400
            },
            {
                "time": "15:33:41",
                "average": 31.67,
                "close": 31.665,
                "high": 31.67,
                "low": 31.665,
                "open": 31.67,
                "volume": 3400
            },
            {
                "time": "15:34:26",
                "average": 31.67,
                "close": 31.67,
                "high": 31.67,
                "low": 31.665,
                "open": 31.67,
                "volume": 13200
            },
            {
                "time": "15:35:15",
                "average": 31.67,
                "close": 31.66,
                "high": 31.68,
                "low": 31.66,
                "open": 31.68,
                "volume": 18800
            },
            {
                "time": "15:36:02",
                "average": 31.64,
                "close": 31.645,
                "high": 31.65,
                "low": 31.63,
                "open": 31.65,
                "volume": 49400
            },
            {
                "time": "15:37:58",
                "average": 31.64,
                "close": 31.645,
                "high": 31.65,
                "low": 31.63,
                "open": 31.65,
                "volume": 12300
            },
            {
                "time": "15:38:01",
                "average": 31.65,
                "close": 31.64,
                "high": 31.65,
                "low": 31.64,
                "open": 31.65,
                "volume": 13700
            },
            {
                "time": "15:39:00",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.645,
                "open": 31.645,
                "volume": 16200
            },
            {
                "time": "15:40:56",
                "average": 31.66,
                "close": 31.66,
                "high": 31.67,
                "low": 31.65,
                "open": 31.65,
                "volume": 21500
            },
            {
                "time": "15:41:04",
                "average": 31.66,
                "close": 31.65,
                "high": 31.67,
                "low": 31.64,
                "open": 31.67,
                "volume": 30400
            },
            {
                "time": "15:42:59",
                "average": 31.64,
                "close": 31.635,
                "high": 31.64,
                "low": 31.63,
                "open": 31.64,
                "volume": 6100
            },
            {
                "time": "15:43:06",
                "average": 31.63,
                "close": 31.615,
                "high": 31.64,
                "low": 31.61,
                "open": 31.63,
                "volume": 34900
            },
            {
                "time": "15:44:02",
                "average": 31.62,
                "close": 31.62,
                "high": 31.625,
                "low": 31.61,
                "open": 31.62,
                "volume": 12000
            },
            {
                "time": "15:45:59",
                "average": 31.62,
                "close": 31.615,
                "high": 31.63,
                "low": 31.61,
                "open": 31.615,
                "volume": 23800
            },
            {
                "time": "15:46:04",
                "average": 31.62,
                "close": 31.615,
                "high": 31.62,
                "low": 31.61,
                "open": 31.62,
                "volume": 11900
            },
            {
                "time": "15:47:00",
                "average": 31.61,
                "close": 31.62,
                "high": 31.62,
                "low": 31.6,
                "open": 31.61,
                "volume": 27700
            },
            {
                "time": "15:48:30",
                "average": 31.62,
                "close": 31.62,
                "high": 31.63,
                "low": 31.615,
                "open": 31.63,
                "volume": 12100
            },
            {
                "time": "15:49:06",
                "average": 31.63,
                "close": 31.63,
                "high": 31.64,
                "low": 31.62,
                "open": 31.63,
                "volume": 10000
            },
            {
                "time": "15:50:01",
                "average": 31.64,
                "close": 31.64,
                "high": 31.64,
                "low": 31.63,
                "open": 31.64,
                "volume": 17800
            },
            {
                "time": "15:51:55",
                "average": 31.64,
                "close": 31.645,
                "high": 31.65,
                "low": 31.635,
                "open": 31.635,
                "volume": 14700
            },
            {
                "time": "15:52:00",
                "average": 31.64,
                "close": 31.65,
                "high": 31.65,
                "low": 31.63,
                "open": 31.65,
                "volume": 32300
            },
            {
                "time": "15:53:04",
                "average": 31.65,
                "close": 31.66,
                "high": 31.66,
                "low": 31.64,
                "open": 31.64,
                "volume": 27900
            },
            {
                "time": "15:54:11",
                "average": 31.65,
                "close": 31.64,
                "high": 31.66,
                "low": 31.64,
                "open": 31.65,
                "volume": 19500
            },
            {
                "time": "15:55:02",
                "average": 31.65,
                "close": 31.65,
                "high": 31.65,
                "low": 31.64,
                "open": 31.65,
                "volume": 23900
            },
            {
                "time": "15:56:03",
                "average": 31.66,
                "close": 31.66,
                "high": 31.67,
                "low": 31.645,
                "open": 31.66,
                "volume": 34500
            },
            {
                "time": "15:57:01",
                "average": 31.66,
                "close": 31.66,
                "high": 31.67,
                "low": 31.65,
                "open": 31.65,
                "volume": 29000
            },
            {
                "time": "15:58:00",
                "average": 31.65,
                "close": 31.64,
                "high": 31.66,
                "low": 31.64,
                "open": 31.655,
                "volume": 34900
            },
            {
                "time": "15:59:05",
                "average": 31.65,
                "close": 31.65,
                "high": 31.66,
                "low": 31.63,
                "open": 31.65,
                "volume": 19500
            },
            {
                "time": "16:00:00",
                "average": 31.71,
                "close": 31.71,
                "high": 31.71,
                "low": 31.71,
                "open": 31.71,
                "volume": 1400
            }
        ]
    };
</script>

<script>
    var values = {}
    var dataIn = []

    function point(time, average) {
        this.time
    }

    $.each(input['NAB.AX'], function(index, value) {
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