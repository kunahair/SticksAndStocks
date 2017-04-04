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

            <div id="account-info" >
                <h2>Account Information</h2><a href="#" id="account-info-edit-button" style="text-align: right">edit</a>
                <hr />
                <div >
                    <p><text style="font-weight: bold">Name: </text><text class="account-info-edit">{{Auth::user()->name}}</text></p>
                    <p><text style="font-weight: bold">Email: </text><text class="account-info-edit">{{Auth::user()->email}}</text></p>
                    <p><text style="font-weight: bold">Member Since: </text><text>{{Auth::user()->created_at}}</text></p>
                </div>
            </div>
        </div>



    </div> <!--End body Container -->

@include('layouts.footer')

    <script>

        var account_fields = [];

        $('#account-info-edit-button').click(function () {
           $('.account-info-edit').each(function(i, v){
               account_fields.push(v.innerHTML);
              console.log(v);
           });

           console.log(account_fields);

        });
    </script>

</body>

</html>