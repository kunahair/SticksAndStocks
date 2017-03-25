
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>@yield('title')</title>

    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ url('/style.css') }}" rel="stylesheet" type="text/css">
    <!-- scripts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: black;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;


        }

        .full-height {
            height: 50vh;
        }

        .top-center {
            position: absolute;
            left: 350px;
            top: 18px;
        }
        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
            background-color: black;
            padding: 15px;
        }

        .title {
            font-size: 84px;
            padding: 10px;
            align: center;
            position: relative;
            color: white;
        }
        .text-container{
            font-size: 23px;
            font-color: black;
            background-color: orange;
            padding: 5px;
            border: 19px solid transparent;

        }
        .links > a {
            color: white;
            padding: 25px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .0rem;
            text-decoration: none;
            text-transform: uppercase;


        }
        a:hover{
            background-color: yellowgreen;
            padding: 25px;

        }

        .m-b-md {
            margin-bottom: 30px;


        }
        .button{
            float: right;
        }
        .input-group {
            float: right;
            width: 25%;
        }

        .font-color{
            color: white;
        }

        #logo {
            display: inline-block;

            right: 30px;
        }
        .wrapper{
            height: 100%;
            width: 100%;
        }

        .footer{
            position: absolute;
            background-color: black;
            width: 100%;
            height: 20%;

        }

    </style>

    <!--script>
        $(document).ready(function(){
            $("button").click(function(){
                $("input").toggle();
                $("span").toggle();
                $("i").toggle();
            });
        });
    </script-->
    <script src="jj/js/three.min.js"></script>
    <script src="jj/js/controls/TrackballControls.js"></script>
    <!--<script src="js/effects/AsciiEffect.js"></script>-->

    <script src="jj/js/renderers/Projector.js"></script>
    <script src="jj/js/renderers/CanvasRenderer.js"></script>

    <script src="jj/js/libs/stats.min.js"></script>

    <script>

        /*var scene = new THREE.Scene();
         var camera = new THREE.PerspectiveCamera( 75, window.innerWidth/window.innerHeight, 0.1, 1000 );

         var renderer = new THREE.WebGLRenderer();
         renderer.setSize( window.innerWidth, window.innerHeight );
         document.body.appendChild( renderer.domElement );

         var geometry = new THREE.BoxGeometry( 1, 1, 1 );
         var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
         var cube = new THREE.Mesh( geometry, material );
         scene.add( cube );

         camera.position.z = 5;

         var render = function () {
         requestAnimationFrame( render );

         cube.rotation.x += 0.1;
         cube.rotation.y += 0.1;

         renderer.render(scene, camera);
         };

         render();*/
        var container, stats;
        var camera, scene, renderer;

        var raycaster;
        var mouse;

        //var img = new THREE.MeshBasicMaterial({ //CHANGED to MeshBasicMaterial
        //map:THREE.ImageUtils.loadTexture('slide/img/01.jpg')
        //});
        //img.map.needsUpdate = true; //ADDED

        // plane
        //var plane = new THREE.Mesh(new THREE.PlaneGeometry(200, 200),img);
        //plane.overdraw = true;
        // scene.add(plane);

        var PI2 =Math.PI * 2 ;

        var programFill = function ( context ) {

            context.beginPath();
            context.arc( 0, 0, 2, 0, PI2, true );
            context.fill();

        };

        var programStroke = function ( context ) {

            context.lineWidth = 0.03;
            context.beginPath();
            context.arc( 0, 0, 0.5, 0, PI2, false );
            context.stroke();

        };

        var INTERSECTED;

        init();
        animate();

        function init() {

            container = document.createElement( 'div' );
            document.body.appendChild( container );

            /*var info = document.createElement( 'div' );
             info.style.position = 'absolute';
             info.style.top = '10px';
             info.style.width = '100%';
             info.style.textAlign = 'center';
             info.innerHTML = '<a href="http://threejs.org" target="_blank">three.js</a> canvas - interactive particles';
             container.appendChild( info );*/

            camera = new THREE.PerspectiveCamera( 80, window.innerWidth / window.innerHeight, 8, 10000 );
            camera.position.set( 0, 100, 500 );

            scene = new THREE.Scene();

            for ( var i = 0; i < 100; i ++ ) {

                var particle = new THREE.Sprite( new THREE.SpriteCanvasMaterial( { color: Math.random() * 0x808080 + 0x808080, program: programStroke } ) );
                particle.position.x = Math.random() * 800 - 400;
                particle.position.y = Math.random() * 800 - 400;
                particle.position.z = Math.random() * 800 - 400;
                particle.scale.x = particle.scale.y = Math.random() * 20 + 20;
                scene.add( particle );

            }

            //

            raycaster = new THREE.Raycaster();
            mouse = new THREE.Vector2();

            renderer = new THREE.CanvasRenderer();
            renderer.setClearColor(  );
            renderer.setPixelRatio( window.devicePixelRatio );
            renderer.setSize( window.innerWidth, window.innerHeight );
            container.appendChild( renderer.domElement );



            document.addEventListener( 'mousemove', onDocumentMouseMove, false );

            //

            window.addEventListener( 'resize', onWindowResize, false );

        }

        function onWindowResize() {

            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();

            renderer.setSize( window.innerWidth, window.innerHeight );

        }

        function onDocumentMouseMove( event ) {

            event.preventDefault();

            mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
            mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;

        }

        //

        function animate() {

            requestAnimationFrame( animate );

            render();


        }

        var radius = 600;
        var theta = 0;

        function render() {

            // rotate camera

            theta += 0.2;

            //camera.position.x = radius * Math.cos( THREE.Math.degToRad( theta ) );
            camera.position.y = radius * Math.sin( THREE.Math.degToRad( theta ) );
            camera.position.z = radius * Math.tan( THREE.Math.degToRad( theta ) );
            camera.lookAt( scene.position );

            camera.updateMatrixWorld();

            // find intersections

            raycaster.setFromCamera( mouse, camera );

            var intersects = raycaster.intersectObjects( scene.children );

            if ( intersects.length > 0 ) {

                if ( INTERSECTED != intersects[ 0 ].object ) {

                    if ( INTERSECTED ) INTERSECTED.material.program = programStroke;

                    INTERSECTED = intersects[ 0 ].object;
                    INTERSECTED.material.program = programFill;

                }

            } else {

                if ( INTERSECTED ) INTERSECTED.material.program = programStroke;

                INTERSECTED = null;

            }

            renderer.render( scene, camera );

        }
    </script>
</head>
<body>


    <div class=" ">
        <div class="content ">
        <div class="title m-b-md ">
            <div id="logo">
           <img src="img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align="">
            </div>
            Pineapple
        </div>

        <div class="links">
            <a href="https://laravel.com/docs">Stock information</a>
            <a href="https://laracasts.com">play-Game</a>
            <a href="https://laravel-news.com">News</a>
            <a href="https://forge.laravel.com">contact us</a>
        </div>
        </div>
        <div class="top-right">
        <div class="button">
            <button type="button" class="btn btn-success">Login</button>
        </div>
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="email" type="text" class="form-control" name="email" placeholder="Email">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="password" type="password" class="form-control" name="password" placeholder="Password">
        </div>
        </div>


    </div>




            <!--div class="text-container">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Column 1</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
                    </div>
                    <div class="col-sm-4">
                        <h2>Column 2</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
                    </div>
                    <div class="col-sm-4">


                    </div>
                </div>
            </div-->

    @section('charter')


<footer class="footer">
    <p> footer</p>
<footer>

</body>
</html>
