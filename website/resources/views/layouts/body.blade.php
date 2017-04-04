<body>

<div class="">
    <div class="top-center ">
        <div class="title m-b-md ">
            <div id="logo">
                <img src="img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align="">
            </div>
            Pineapple
        </div>

        <div class="links">
            <a href="">How to Play</a>
            <a href="">Stock Market</a>
            <a href="">Trends</a>
        </div>
    </div>
    <div class="top-right">
        <div class="">

            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info btn-lg" onclick="location.href='{{ url('login') }}'">Login</button>
            <button type="button" class="btn btn-info btn-lg" onclick="location.href='{{ url('register') }}'">Register</button>

        </div>
    </div>

</div>

<footer class="footer" style="background-color: #123;">
    <ul>
        <li><a>About Us</a></li>
        <li><a>Contact Us</a></li>
    </ul>
</footer>

@include('layouts.background')

</body>
</html>
