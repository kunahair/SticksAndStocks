
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
    <link href="{{ url('css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('js/background.js') }}" rel="stylesheet" type="text/css">
    <!-- scripts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Styles -->
    <style>


    </style>

    <script>
        $(document).ready(function(){
            $("button").click(function(){
                $("footer").toggle();
            });
        });
    </script>
    <script src="jj/js/three.min.js"></script>
    <script src="jj/js/controls/TrackballControls.js"></script>
    <!--<script src="js/effects/AsciiEffect.js"></script>-->

  
    <script src="jj/js/renderers/Projector.js"></script>
    <script src="jj/js/renderers/CanvasRenderer.js"></script>

    <script src="jj/js/libs/stats.min.js"></script>

    <script>

        var container;
        var camera, scene, controls, renderer;

        init();
        animate();

        function init() {

            container = document.createElement( 'div' );
            document.body.appendChild( container );

            camera = new THREE.PerspectiveCamera( 30, window.innerWidth / window.innerHeight, 6, 10000 );
            camera.position.set( 0, 300, 500 );
            scene = new THREE.Scene();

            // creates triangle shapes
            for ( var i = 0; i < 100; i ++ ) {
                var geometry = new THREE.Geometry();
                geometry.vertices.push(
                    new THREE.Vector3( -10,  10, 0 ),
                    new THREE.Vector3( -10, -10, 0 ),
                    new THREE.Vector3(  10, -10, 0 )

                );
                geometry.faces.push( new THREE.Face3( 0, 1, 2 ));

                //generates random color screens
                var material = new THREE.MeshBasicMaterial( { color: Math.random() * 0x808080 + 0x808080} );
                var cube = new THREE.Mesh( geometry, material );
                //var particle = new THREE.Sprite( new THREE.SpriteCanvasMaterial( { color: Math.random() * 0x808080 + 0x808080, program: programStroke } ) );
                cube.position.x = Math.random() * 800 - 400;
                cube.position.y = Math.random() * 800 - 400;
                cube.position.z = Math.random() * 800 - 400;
                cube.scale.x = cube.scale.y = Math.random() * 20 + 20;
                scene.add( cube );

            }


            //render
            renderer = new THREE.CanvasRenderer();
            renderer.setClearColor(0x808080 );
            renderer.setPixelRatio( window.devicePixelRatio );
            renderer.setSize( window.innerWidth, window.innerHeight );
            container.appendChild( renderer.domElement );


            window.addEventListener( 'resize', onWindowResize, false );

        }

        function onWindowResize() {

            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();

            renderer.setSize( window.innerWidth, window.innerHeight );
            controls.handleResize();

        }

        function animate() {

            requestAnimationFrame( animate );

            render();


        }

        var radius = 600;
        var theta = 0;

        function render() {

            // rotate camera

            theta += 0.1;

            //camera.position.x = radius * Math.cos( THREE.Math.degToRad( theta ) );
            //camera.position.y = radius * Math.sin( THREE.Math.degToRad( theta ) );
            //camera.position.z = radius * Math.tan( THREE.Math.degToRad( theta ) );
            camera.lookAt( scene.position );


            renderer.render( scene, camera );

        }
    </script>
</head>
<body>

@section("header")
<div class="">
    <div class="top-center ">
        <div class="title m-b-md ">
            <div id="logo">
                <img src="img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align="">
            </div>
            Pineapple
        </div>

        <div class="links">
            <a href="">Stock information</a>
            <a href="">About us</a>
            <a href="">contact us</a>
        </div>
    </div>
    <div class="top-right">
        <div class="">

            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#login">Login</button>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#signup">Register</button>

            <!-- Modal -->
            <form class="modal fade" id="login" role="dialog" action="" method="post">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="font-color">Login</h4>
                        </div>
                        <div class="modal-body font-color">
                            <h3>Email:</h3>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="email" type="text" class="form-control" name="email" placeholder="Email">
                            </div>
                            <h3>Password:</h3>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info btn-lg" data-dismiss="modal">Login</button>
                        </div>
                    </div>

                </div>
            </form>
            <!-- sign up -->
            <div class="modal fade" id="signup" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="font-color">Register</h4>
                        </div>
                    <form action="{{ url('/user.blade.php')}}" method="post">
                        <div class="form-group" >
                            <label for="firstname">FirstName:</label>
                            <input type="email" class="form-control" id="firstname" placeholder="Enter email">
                        </div>
                        <div class="form-group" >
                            <label for="lastname">LastName:</label>
                            <input type="email" class="form-control" id="lastname" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label class="email">Email:</label>
                            <input id="email" type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="pwd" placeholder="Enter password">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="submit" class="btn btn-info btn-lg" data-dismiss="modal">Submit</button>
                        </div>
                      </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@show
<footer class="footer">
    <p>footer</p>
</footer>

</body>
</html>
