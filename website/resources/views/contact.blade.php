{{--/**--}}
{{--* Created by: Paul Davidson.--}}
{{--* Authors: Paul Davidson and Abnezer Yhannes--}}
{{--*/--}}
@include('layouts.header')

@section('title','Pineapple')

<style>
    .container{

        background-color: #222;
        color:#fff;
        margin-top:30px;
        padding-top:30px;
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
        <div class="row">
            <div class="col-sm-5">
                <p>Contact us and we'll get back to you within 24 hours.</p>
                <p><span class="glyphicon glyphicon-map-marker"></span> Melbourne, Australia</p>
                <p><span class="glyphicon glyphicon-phone"></span> +00 1515190097</p>
                <p><span class="glyphicon glyphicon-envelope"></span> Pineapple@something.com</p>
            </div>
            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
                    </div>
                </div>
                <textarea class="form-control" id="comments" name="comments" placeholder="Description" rows="5"></textarea><br>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <button class="btn btn-default pull-right" type="submit">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="map" style="width:400px;height:400px;"></div>
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
    </div>
    @include('layouts.footer')
