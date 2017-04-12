<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.css" rel="stylesheet" >--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css" rel="stylesheet" >--}}
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css">
    <!--<link href="{{ url('js/background.js') }}" rel="stylesheet" type="text/css">-->
    <!-- scripts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Styles -->

    <script src="jj/js/three.min.js"></script>
    <script src="jj/js/controls/TrackballControls.js"></script>
    <!--<script src="js/effects/AsciiEffect.js"></script>-->

  
    <script src="jj/js/renderers/Projector.js"></script>
    <script src="jj/js/renderers/CanvasRenderer.js"></script>

    <script src="jj/js/libs/stats.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css" rel="stylesheet" >

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!--Style for the suggestions div-->
    <style>
        p.suggestion {
            font-weight: bold;
            background-color: #FFFFFF;
        }
        p.suggestion:hover {
            background-color: orange !important;
        }

        p.suggestion>text:hover{

        }
    </style>
</head>