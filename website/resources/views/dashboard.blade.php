@include('layouts.header')

@section('title','Pineapple')

<body>

    <style>
        .panel {
            padding: 0;
            /*margin: 48px 60px;*/
        }
        .panel :hover{
            cursor: pointer;
            background-color: #f0ad4e;
        }

        #account-info {
            margin-top: 20%;
        }
    </style>

    @include('layouts.navbar')

    <div id='content' class="container">
        <div class="alert alert-success" role="alert">Logged In</div>

        {{--User Trade Accounts cards--}}
        <div id="accounts" class="col-xs-12">
            <div class="panel panel-default col-xs-12 col-md-3">
                <div class="panel-heading">
                    <h3 class="panel-title">Trade Account Title</h3>
                </div>
                <div class="panel-body">
                    Basic panel example
                </div>
            </div>

            <div class="col-md-1"></div>

            <div class="panel panel-default col-xs-12 col-md-3">
                <div class="panel-heading">
                    <h3 class="panel-title">Trade Account Title</h3>
                </div>
                <div class="panel-body">
                    Basic panel example
                </div>
            </div>

            <div class="col-md-1"></div>

            <div class="panel panel-default col-xs-12 col-md-3">
                <div class="panel-heading">
                    <h3 class="panel-title">Trade Account Title</h3>
                </div>
                <div class="panel-body">
                    Basic panel example
                </div>
            </div>

            {{--Account information div--}}
            <div id="account-info" >
                <h2>Account Information</h2>
                <span id="account-info-view-mode">
                    <a href="#" id="account-info-edit-button" style="text-align: right">edit</a>
                </span>
                <span id="account-info-edit-mode" style="display: none">
                    <a href="#" id="account-info-save-button">save</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" id="account-info-cancel-button">cancel</a>
                </span>
                <hr />
                <div >
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
                    <p>
                        <text style="font-weight: bold">Name: </text>
                        <text id="name-view" class="account-info-edit">{{Auth::user()->name}}</text>
                        <input name="name" class="account-info-edit-field" value="{{Auth::user()->name}}" style="display: none" />
                    </p>

                    <p>
                        <text style="font-weight: bold">Email: </text>
                        <text id="email-view" class="account-info-edit">{{Auth::user()->email}}</text>
                        <input name="email" class="account-info-edit-field" value="{{Auth::user()->email}}" style="display: none" />
                    </p>

                    <p>
                        <text style="font-weight: bold">Member Since: </text>
                        <text>{{Auth::user()->created_at}}</text>
                    </p>

                </div>
            </div>
        </div>



    </div> <!--End body Container -->

@include('layouts.footer')

    <script>

        var account_fields = [];

        $('#account-info-edit-button').click(function () {

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
            
            var postData = {};

//            //Change save and cancel buttons to edit
//            $('#account-info-view-mode').css('display', 'block');
//            $('#account-info-edit-mode').css('display', 'none');

//            //Hide the input fields and just show original text
//            $('.account-info-edit').css('display', 'inline');
//            $('.account-info-edit-field').css('display', 'none');

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
                    $('#name-view').text(postData['name']);
                    $('#email-view').text(postData['email']);

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

</html>