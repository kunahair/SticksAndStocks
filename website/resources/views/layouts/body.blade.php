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

<footer class="footer">
    <ul>
        <li><a>About Us</a></li>
        <li><a>Contact Us</a></li>
    </ul>
</footer>

@include('layouts.background')

</body>
</html>
