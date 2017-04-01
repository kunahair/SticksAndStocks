<script>
    $(document).ready(function(){
        $("button").click(function(){
            $("footer").toggle();
        });
    });
</script>

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
        // controls.handleResize();

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