{{--/**--}}
{{--* Created by: Paul Davidson.--}}
{{--* Authors: Paul Davidson, Sadhurshan Ganeshan and Abnezer Yhannes--}}
{{--*/--}}
@include('layouts.header')

@section('title','Pineapple')

<style>
    .container{
        background-color: #222;
        color:#fff;
        margin-top:40px;
        padding-top:40px;
        padding-bottom: 100px;
    }


  .form-control{

      color:blue;
  }


    #map{
        margin-top:30px;
        padding-top:30px

    }

</style>


    @include('layouts.header-navbar')

    <link href="{{ url('css/broken_css_pages.css') }}" rel="stylesheet" type="text/css">



    <div class="container">

        {{--Message display div, will show depending on type--}}
        @if(isset($message))
            <div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3" style="margin-bottom: 20px">
                @if($message["type"] == 'error')
                    <div class="alert alert-danger">
                @else
                    <div class="alert alert-success">
                @endif
                        {{$message["message"]}}
                    </div>
            </div>
        @endif

        {{--Contact information--}}
        <div class="col-xs-12 col-md-6">
            <p>Contact us and we'll get back to you ASAP.</p>
            <p><span class="glyphicon glyphicon-map-marker"></span> Melbourne, Australia</p>
            <p><span class="glyphicon glyphicon-phone"></span> +00 1515190097</p>
            <p><span class="glyphicon glyphicon-envelope"></span> Pineapple@something.com</p>
            {{--Google Maps pin of Pineapple Location--}}
            <p><div id="map" style="width:400px;height:400px; margin: 0 auto"></div></p>
        </div>

        {{--Contact Form--}}
        <div class="col-xs-12 col-md-6">
            <form action="{{url('contact')}}" method="POST">
                    <div class="col-sm-6 form-group">
                        <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
                    </div>

                    {{--Token needed for POST requests--}}
                    <div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                <div>
                    <textarea class="form-control" id="comments" name="comments" placeholder="Description" rows="5"></textarea><br>
                </div>

                    <div class="col-sm-12 form-group">
                        <button class="btn btn-default pull-right" type="submit">Send</button>
                    </div>

            </form>
        </div>
    </div>
  <script>
        function initMap() {
            var uluru = {lat: -37.8084349, lng: 144.9630036};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 17,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcNXgoLSLCzOikdkaCNQQYiYXM9H_WJiA&callback=initMap">
    </script>
    </div>
    @include('layouts.footer')
