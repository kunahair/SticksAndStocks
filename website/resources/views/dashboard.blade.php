@include('layouts.header')

@section('title','Pineapple')

<body class="background">

    <style>
        .account-info-edit-field {
            color: #000000;
        }
    </style>

    @include('layouts.navbar')


        <div class="block-foreground">
          <div class="row bg">

             <div class="col-md-3 ">
               <h1 class="subheading">Dashboard</h1>
             </div >
             <div class="col-md-9">
             <div class="balance">
                <h2>Balance: ${{number_format(Auth::user()->balance,2)}}AUD</h2>
             </div>
             </div>

               <div id="create-ta-form" style="padding-bottom: 3%;">
               </div>
          </div>
             <div class="row edit-trade-account">
                <label>Trade Account Name: </label>
                <input id="input-ta-name" type="text" value="" />
                <button id="button-create-ta" class="btn btn-lg button" type="button">Create Account</button>
                <div id="create-ta-error" style="color: darkred; display: none">There was an error creating Trade Account</div>
             </div>

        {{--User Trade Accounts cards--}}

        <div id="accounts" class="trade-account">
          <div class="row">
            {{--Loop through all the trade accounts that the user has and list them as panels with name and stats--}}
            @foreach(Auth::user()->tradingAccounts as $ta)

                  <div >
                      {{--Create Panel that links to its individual Trade Account Page--}}
                <a href="{{url('/tradeaccount/' . $ta->id) }}">

                    <div class="panel-group panel-default  col-md-2 col-sm-1 col-xs-offset-1" >
                        {{--Name of Trade Account--}}
                        <div class="panel-heading">
                            <h3 class="panel-title">{{$ta->name}}</h3>
                        </div>
                        {{--Stats about Trade Account--}}
                        <div class="panel-body">
                            <h4>Value: ${{$ta->getCurrentStock()["stats"]["total_stock_value"]}}AUD</h4>
                            <h4>Growth: {{number_format($ta->totalGrowth(), 2)}}</h4>
                            @php
                                $tradeAccountInfo = $ta->getCurrentStock()["stats"];
                                echo '<h4>Number of Stocks: ' . $tradeAccountInfo["total_stock_count"] . '<br /></h4>';
                            @endphp
                        </div>

                    </div>

                </a>
               </div>
                {{--Spacer for panels--}}
                {{--<div class="col-md-1" style="padding-right: 30px">  </div>--}}
             @endforeach
          </div>
        </div>
    <br/>
        </div>
        <div id='content' class=" text-center col-sm-4 account-info ">
            {{--Account information div--}}
            <div id="account-info" class="bg ">

                <br/>
                <h2 class="subheading">Account Information</h2>
                <br/>

                <span id="account-info-view-mode">
                    <a href="#" id="account-info-edit-button" class="btn btn-lg button" style="text-align: right">edit</a>
                </span>
                <br/>
                <span id="account-info-edit-mode" style="display: none">
                    <a href="#" id="account-info-save-button" class="btn btn-lg button" >save</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" id="account-info-cancel-button" class="btn btn-lg button" >cancel</a>
                </span>

            </div>
         </div>
     <div class="edit-info ">
                <div class=" container ">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="update-user-error" style="display: none; color: #FF0000">There was an error updating your information<br /><br /></div>
                        <br/>
                        <br/>
                    <p>
                        <text style="font-weight: bold">Name: </text>
                        <text id="name-view" class="account-info-edit username-view" >{{Auth::user()->name}}</text>
                        <input name="name" class="account-info-edit-field" type="text" value="{{Auth::user()->name}}" style="display: none" />
                    </p>

                    <p>
                        <text style="font-weight: bold">Email: </text>
                        <text class="account-info-edit email-view">{{Auth::user()->email}}</text>
                        <input name="email" class="account-info-edit-field" type="email" value="{{Auth::user()->email}}" style="display: none" />
                    </p>

                    <p>
                        <text style="font-weight: bold">Member Since: </text>
                        <text>{{Auth::user()->created_at}}</text>
                    </p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                </div>
     </div>





     <!--End body Container -->

@include('layouts.footer')

    <script>

        {{--API call to create a new Trade account for the user, reload page if success, otherwise show error--}}
        $('#button-create-ta').click(function () {
            var tradeAccountName = {};
            tradeAccountName['name'] = $('#input-ta-name').val();
//            AJAX POST call to create a new Trade Account
            $.post("{{ url('createTA') }}", tradeAccountName)
                .done(function (data) {
                    //If successful, reload the page to show the new Trade Account
                    location.reload();
                })

                .fail(function (error) {
                    //Otherwise show an error
                    $('#create-ta-error').css('display', 'block');
                })
        });

        //Holder for values of input fields if they need to be put back the way they where from error
        var account_fields = [];

        $('#account-info-edit-button').click(function (event) {

            event.preventDefault();

            //Change edit button to save and cancel
            $('#account-info-view-mode').css('display', 'none');
            $('#account-info-edit-mode').css('display', 'block');

            //Hide the text and show the input text fields
            $('.account-info-edit').css('display', 'none');
            $('.account-info-edit-field').css('display', 'inline');

            //Put the current values of the text field in
            $('.account-info-edit').each(function(i,v){
                account_fields.push(v.innerHTML);
            });

        });

        $('#account-info-cancel-button').click(function () {

            event.preventDefault();

            //Change save and cancel buttons to edit
            $('#account-info-view-mode').css('display', 'block');
            $('#account-info-edit-mode').css('display', 'none');

            //Hide the input fields and just show original text
            $('.account-info-edit').css('display', 'inline');
            $('.account-info-edit-field').css('display', 'none');

            $('.account-info-edit').each(function(){
                var value = account_fields.pop();
                $(this).innerHTML = value;
            });


        });

        $('#account-info-save-button').click(function () {

            event.preventDefault();

            //Post data holder
            var postData = {};

            //Loop through the input fields and check which fileds have been edited
            $('.account-info-edit-field').each(function(i,v){
                if (i == 0) {
                    postData['name'] = v.value;
                }
                if (i == 1) {
                    postData['email'] = v.value;
                }
            });

            //Send the data to the server to validate and update the database
            $.post("{{ url('api/editUser') }}", postData)

            //If the operation was successful, then update the fields to refelect the changes
                .done(function (data) {
                    var jsonData = JSON.parse(data);
                    $('#update-user-error').css('display', 'none');
                    $('.username-view').text(postData['name']);
                    $('.email-view').text(postData['email']);

                    //Change save and cancel buttons to edit
                    $('#account-info-view-mode').css('display', 'block');
                    $('#account-info-edit-mode').css('display', 'none');

                    //Hide the input fields and just show original text
                    $('.account-info-edit').css('display', 'inline');
                    $('.account-info-edit-field').css('display', 'none');
                })

                //Otherwise show generic error
                .fail(function (error) {
                    $('#update-user-error').css('display', 'block');
                });

        });


    </script>

</body>