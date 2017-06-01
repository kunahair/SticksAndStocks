<div class="">

    <nav class="navbar navbar-inverse bg-primary" >
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/"><img src="/img/PineappleWC (1).gif" class="logo" alt="logo" align=""></a>
                <text class="title">Pineapple</text>

            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <form class="navbar-form navbar-left">
                    {{--ASX company search with autocomplete--}}
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search" id="autocomplete">
                        <div id="stocksList" style="position: absolute; z-index: 300; background-color: #FFFFFF"></div>
                    </div>

                    {{--<button type="submit" class="btn btn-default">Search</button>--}}
                </form>
                <!-- links-->
                <div class="nav navbar-nav navbar-left">
                    <li > <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Social</a>
                        <ul class="dropdown-menu">
                            <li><a href="/messages">Messages</a></li>
                            <li ><a href="/friends">Friends</a></li>
                            <li ><a href="/profiles">Profiles</a></li>
                        </ul>
                    </li>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/leaderboard">Leaderboard</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                            Inbox
                            @if(count(Auth::user()->getNotifications()) > 0)
                                <span id="notificationsBadge" class="badge bg" id="alarm-system">
                            @else
                                 <span id="notificationsBadge" class="badge bg" id="alarm-system" style="display: none">
                            @endif
                                     {{count(Auth::user()->getNotifications())}}
                                </span>
                        </a>
                        <ul id="notificationsList" class="dropdown-menu">
                            {{--Loop through all the pending notifications the user has and display as dropdown notification--}}
                            @foreach(Auth::user()->getNotifications() as $notification)

                                {{--If the notification is a message, show that it is a new message and who it is from--}}
                                @if($notification instanceof App\Message)
                                    <li>
                                        <a href="{{url('messages')}}/{{$notification["from"]}}">New Message from {{$notification["name"]}}</a>
                                    </li>
                                    @continue
                                @endif

                                {{--Othewise, assume that it is a new friend request and show that it is a new friend request and who it is from--}}
                                @if($notification->pending == true)
                                    <li>
                                        <a href="{{url('profile')}}/{{$notification["from"]}}">New Friend Request from {{$notification["name_from"]}}</a>
                                    </li>
                                {{--If the notification is an accepted friend request that has not been viewed, show as such--}}
                                 @elseif($notification->accept_view == false)
                                    <li>
                                        <a href="{{url('profile')}}/{{$notification["to"]}}">Friend Request Accepted from {{$notification["name_to"]}}</a>
                                    </li>

                                @endif

                            @endforeach

                        </ul>

                    </li>

                            @if(Auth::check())
                                <li><a href="{{url('/logout')}}">Logout</a></li>
                            @else
                                <li><a href="{{ url('/login') }}">Login</a></li>

                            @endif



                </div>
                <!-- user details-->
                <ul class="navbar-nav navbar-right container-fluid">


                    <p>
                        <text>Welcome,  </text>
                        <text class="username-view" >{{Auth::user()->name}}</text>
                    </p>

                    <p>

                        <text class="email-view" >{{Auth::user()->email}}</text>
                    </p>


                    {{--<div class="alert alert-success" role="alert">Logged In</div>--}}
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>


    <script>



        $(document).ready(function(){ // this will be called when the DOM is ready

            //Get a list of all notifications every three seconds and update the Inbox navbar item
            window.setInterval(function () {
                //Get the list of unread notifications
                $.get("{{url('api/getNotifications')}}")
                    .done(function (data) {
                        if (data.length > 0)
                        {
                            $('#notificationsBadge').css('display', 'inline');
                        }
                        else {
                            $('#notificationsBadge').css('display', 'none');
                        }
                        //Update the number of notifications badge
                        $('#notificationsBadge').text(data.length);

                        //Empty the notifications list
                        $('#notificationsList').empty();

                        //Loop through every received notification and add to the dropdown list
                        $.each(data, function (index, item) {
                            //If it has pending it means it is a friend request
                            if (item["pending"] != null)
                            {
                                //If it is a pending friend request, show as Pending Friend Request
                                if (item["pending"] == true)
                                    $('#notificationsList').append('<li><a href="' + '/profile'  + '/' + item["from"] + '">New Friend Request from ' + item["name_from"] + '</a></li>');
                                else if(item["accept_view"] == false)
                                    $('#notificationsList').append('<li><a href="' + '/profile'  + '/' + item["to"] + '">Friend Request Accepted from ' + item["name_to"] + '</a></li>');
                            }
                            //Otherwise test to see if it is a message
                            else if (item["message"] != null)
                                $('#notificationsList').append('<li><a href="' + '/messages'  + '/' + item["from"] + '">New Message from ' + item["name"] + '</a></li>');
                        });
                    });

            }, 3000);

            var stocksList = null; //Set stocksList to null initially

            //Get all stocks names and ASX codes from API
            $.get("{{url('api/all-stocks')}}")// 'http://localhost:8000/api/all-stocks')
                .done(function (data) {
                    stocksList = data;
                });

            //When there is a key up inside the searchbar, search through the stocks list and return a max of 10 results to auto complete
            $('#autocomplete').keyup(function() {

                var query = $('#autocomplete').val(); //Get query

                //If stocksList is still null, just return
                if (stocksList == null)
                    return;

                //Clear contents of stocksList suggestions box
                $("#stocksList").empty();

                //If Query is an empty string, just return
                if (query == "")
                    return;

                //Limit number of results shown
                var j = 5;

                //Go through each stock in the API stocksList, see if the query matches the name or symbol.
                //If it does, add it to the suggestions
                $.each(stocksList, function(i, item) {
                    if ((item["stock_symbol"].indexOf(query.toUpperCase()) >= 0 || item["stock_name"].indexOf(query.toUpperCase()) >= 0) && j > 0)
                    {
                        //Get the link to the stock page
                        var link = "{{url('stock')}}" + "/" + item["stock_symbol"];
                        //Add Found Stock to Stock Search auto complete
                        $('#stocksList').append('' +
                            '<a href="' + link + '"><p class="suggestion" style="margin: 0; padding: 10px;">' + item["stock_name"] + ' <br /><text class="stock_symbol" >' + item["stock_symbol"] + '</text><text>.' + item["market"] + '</text></p></a>'
                        );
                        j--;
                    }

                });
            });

        });

        /**
         * Function to convert html escaped characters into their proper symbols.
         * Found on: http://stackoverflow.com/questions/1912501/unescape-html-entities-in-javascript
         * @param input Encoded string
         * @returns {string} Decoded string
         */
        function htmlDecode(input)
        {
            var doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
    </script>
</div>
