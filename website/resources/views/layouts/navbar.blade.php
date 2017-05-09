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
                <a href="/"><img src="/img/PineappleWC (1).gif" alt="logo" hight="100px" width="100px" align=""></a>
                <text style="font-size: 300%;">Pineapple</text>

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
                    <li > <a href="/tradeaccount/" class="dropdown-toggle" data-toggle="dropdown" role="button" >Social</a>
                        <ul class="dropdown-menu">
                            <li><a href="/messages">Messages</a></li>
                            <li ><a href="/friends">Friends</a></li>
                            <li ><a href="/profiles">Profiles</a></li>
                        </ul>
                    </li>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/leaderboard">Leaderboard</a></li>
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Inbox <span class="badge bg" id="alarm-system">{{count(Auth::user()->getNotifications())}}</span></a>
                        <ul class="dropdown-menu">
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
                                <li>
                                    <a href="{{url('profile')}}/{{$notification["from"]}}">New Friend Request from {{$notification["name"]}}</a>
                                </li>

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
                <ul class="nav navbar-nav navbar-inverse navbar-right container-fluid">


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

    {{--<form>--}}
    {{--<input type="text" class="form-control col-2" name="stockList" id="autocomplete"/>--}}
    {{--<div id="stocksList" style="position: absolute; z-index: 300"></div>--}}
    {{--</form>--}}

    <script>


        $(document).ready(function(){ // this will be called when the DOM is ready

            /**
             * When user clicks a suggested item, the text box is filled with the ASX code and the
             * User is directed to the stocks page of that code
             **/
            $(document).on('click', '.suggestion', function(event) {

                //Stop the default click behaviour of this event
                event.preventDefault();

                //Get the decoded element
                var string = htmlDecode(event.target.innerHTML);
                //Tokenize the string into an array
                var split = string.split(" ");

                //Put the stock symbol value into the text field
                $("#autocomplete").val(split[split.length - 1]);

                //Get the stock code of the selected suggestion
                var stockCode = split[split.length - 1];
                stockCode = stockCode.substring(0, stockCode.indexOf("."));
                //If the user clicked the company name, the code must be retrieved from the correct node
                if (stockCode.length == 0)
                    stockCode = event.target.parentNode.childNodes[0].childNodes[2].innerHTML;


                //Direct User to the stock page of selected suggestion
                //'http://pineapple-stocks.ddns.net/stock/'
                location.href = "{{url('stock')}}" + "/" + stockCode;
            });

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

                //Track the number of results to return
                var j = 5;

                //Go through each stock in the API stocksList, see if the query matches the name or symbol.
                //If it does, add it to the suggestions
                $.each(stocksList, function(i, item) {
                    if ((item["stock_symbol"].indexOf(query.toUpperCase()) >= 0 || item["stock_name"].indexOf(query.toUpperCase()) >= 0) && j >= 0)
                    {
                        {{--var link = "{{url('stock')}}" + "/" + item["stock_symbol"];--}}
                        $('#stocksList').append('' +
                            '<a href="#"><p class="suggestion" style="margin: 0; padding: 10px;">' + item["stock_name"] + ' <br /><text class="stock_symbol" >' + item["stock_symbol"] + '</text><text>.' + item["market"] + '</text></p></a>'
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
