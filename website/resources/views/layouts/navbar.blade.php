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
                    <li > <a href="/tradeaccount">Trade Account</a></li>
                    <li><a href="/dashboard">Dashboard</a>
                    <li><a href="/inbox">Inbox <span class="badge" id="alarm-system">4</span>
                        </a>
                            @if(Auth::check())
                                <li><a href="{{url('/logout')}}">Logout</a></li>
                            @else
                                <li><a href="{{ url('/login') }}">Login</a></li>

                            @endif



                </div>
                <!-- user details-->
                <ul class="nav navbar-nav navbar-right">


                    <p>
                        <text style="font-weight: bold">Welcome,  </text>
                        <text id="name-view" class="account-info-edit" style="font-weight: bold">{{Auth::user()->name}}</text>
                        <input name="name" class="account-info-edit-field" value="{{Auth::user()->name}}" style="display: none" />
                    </p>

                    <p>

                        <text id="email-view" class="account-info-edit" style="font-weight: bold">{{Auth::user()->email}}</text>
                        <input name="email" class="account-info-edit-field" value="{{Auth::user()->email}}" style="display: none" />
                    </p>



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
                            '<a href="#"><p class="suggestion" style="margin: 0; padding: 10px;">' + item["stock_name"] + ' <br /><text class="stock_symbol" >' + item["stock_symbol"] + '</text></p></a>'
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
