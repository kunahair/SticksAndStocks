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

            .dropdown-menu :hover {
                cursor: pointer;
            }

            .options input{
                margin:0;
                padding:0;
                -webkit-appearance:none;
                -moz-appearance:none;
                appearance:none;
            }

            .user{background-image:url({{ url('img/osa_user_blue.png') }});}
            .admin{background-image:url({{ url('img/osa_user_blue_sysadmin.png') }});}

            .options input:active +.group-image{opacity: .9;}
            .options input:checked +.group-image{
                -webkit-filter: none;
                -moz-filter: none;
                filter: none;
            }

            .group-image{
                cursor:pointer;
                background-size:contain;
                background-repeat:no-repeat;
                display:inline-block;
                width:100px;height:70px;
                -webkit-transition: all 100ms ease-in;
                -moz-transition: all 100ms ease-in;
                transition: all 100ms ease-in;
                -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
                -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                filter: brightness(1.8) grayscale(1) opacity(.7);
            }

            .group-image:hover{
                -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
                -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                filter: brightness(1.2) grayscale(.5) opacity(.9);
            }

        </style>

        @include('layouts.navbar')

        <div id='content' class="container">
            <div class="row">
                <div class="col-xs-9">
                    <h2>Admin Dashboard</h2>
                </div>
                <div class="col-xs-3">
                    <br/>
                    <button class="btn btn-primary btn-default btn-block" data-toggle="modal" data-target="#sendMessage">Broadcast Message</button>
                </div>
            </div>
            <hr />

            {{--Quick stats--}}
            <div class="row">
                <div class="col-xs-12">
                    <h4>Total Users: {{$data["usersCount"]}}</h4>
                </div>
            </div>

            <br/><br/>

            {{--Show list of Users with info that is editible--}}
            <div class="row">
                <table class="table table-hover">
                    {{--Headings--}}
                    <thead>
                        <tr>
                            <td class="col-xs-1">id</td>
                            <td class="col-xs-2">name</td>
                            <td class="col-xs-3">email</td>
                            <td class="col-xs-1">role</td>
                            <td class="col-xs-2">created</td>
                            <td class="col-xs-2">modified</td>
                            <td class="col-xs-1"></td>
                        </tr>
                    </thead>

                    {{--Loop through all users and display as table rows--}}
                    <tbody>
                    @foreach($data["users"] as $user)

                        <tr>
                            <td class="col-xs-1">{{$user->id}}</td>
                            <td class="col-xs-2">{{$user->name}}</td>
                            <td class="col-xs-3">{{$user->email}}</td>

                            @if($user->admin)
                                <td class="col-xs-1 bg-danger">Admin</td>
                            @else
                                <td class="col-xs-1 bg-info">User</td>
                            @endif

                            <td class="col-xs-2">{{$user->created_at}}</td>
                            <td class="col-xs-2">{{$user->updated_at}}</td>

                            {{--Edit button, may make it a drop down menu later--}}
                            <td class="col-xs-1">
                                <div class="dropdown">
                                    <button id="{{$user->id}}" class="btn btn-primary dropdown-toggle userid" type="button" data-toggle="dropdown">Manage
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" data-container="body">
                                        <li class="dropdown-item"><a data-toggle="modal" data-target="#deleteWarning">Delete</a></li>
                                        <li class="dropdown-item"><a data-toggle="modal" data-target="#modifyRole">Modify Role</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>

            {{--Pagination links for users--}}
            <div class="row">
                <div class="">
                    {{$data["users"]->links()}}
                </div>
            </div>

            <div id="deleteWarning" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Are you sure you want to delete this user's account?</h3>
                        </div>
                        <div class="modal-body">
                            <div id="delete-error" class="alert alert-warning"></div>
                            <div class="row">
                                <div class="col-xs-6"><button class="btn btn-success btn-block" data-dismiss="modal">Cancel</button></div>
                                <div class="col-xs-6"><button id='killer' class="btn btn-danger btn-block">Terminate</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modifyRole" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Role Modification</h3>
                        </div>
                        <div class="modal-body">
                            <h3>Select a role:</h3>
                            <br/>
                            <br/>
                            <div id="role-error"></div>
                            <br/>
                            <div class="row">
                                <form>
                                    <div class="options">
                                        <div class="col-xs-6" align="center">
                                            <input type="radio" id="user" name="group" value="user"
                                            @if(!$user->admin)
                                                checked="checked"
                                            @endif
                                            >
                                        <label class="group-image user" for="user"></label>
                                        <h4>User</h4>
                                        </div>
                                        <div class="col-xs-6" align="center">
                                            <input type="radio" id="admin" name="group" value="admin"
                                            @if($user->admin)
                                                checked="checked"
                                            @endif
                                            >
                                            <label text-align="center" class="group-image admin" for="admin"></label>
                                            <h4>Admin</h4>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <br/>
                                <br/>
                                <div class="col-xs-6"><button class="btn btn-primary btn-block" data-dismiss="modal">Cancel</button></div>
                                <div class="col-xs-6"><button id="saveRole" class="btn btn-success btn-block">Save Changes</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sendMessage" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Broadcast Message</h3>
                        </div>
                        <div class="modal-body">
                            <h4>Message:</h4>
                            <div id="message-error"></div>
                            <br/>
                            <div class="row">
                                <form>
                                    <textarea class="form-control col-xs-12" id="message" rows="6"></textarea>
                                </form>
                            </div>
                            <div class="row">
                                <br/>
                                <br/>
                                <div class="col-xs-6"><button class="btn btn-primary btn-block" data-dismiss="modal">Cancel</button></div>
                                <div class="col-xs-6"><button id="messageGo" class="btn btn-success btn-block">Send</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function() {
                    $('#delete-error').hide();
                    var id = 0;
                    $('.userid').click(function () {
                        id = $(this).attr('id');
                        console.log(id);
                    });

                    $('#killer').click(function () {
                        var postData = {};
                        postData["userid"] = parseInt(id);
                        $.post("{{ url('api/deleteUser') }}",postData)
                            .done(function (data) {
                            $('#deleteWarning').modal('hide');
                            })
                            .fail(function (error) {
                            $('#delete-error').show();
                            $('#delete-error').text(error["message"]);
                            console.log(error);
                        }).done(function (error) {
                            location.reload();
                            $('#delete-error').text(error.statusMessage);
                        });
                    });

                    $('#role-error').hide();
                    $('#saveRole').click(function () {
                        $.post("{{ url('api/modifyRole') }}",{userid: parseInt(id), role: $('input[name=group]:checked').val()}).done(function (data) {
                            $('#modifyRole').modal('hide');
                        }).fail(function (error) {
                            $('#role-error').show();
                            $('#role-error').text(error.statusMessage);
                            console.log(error);
                        }).done(function (error) {
                            location.reload();
                            $('#role-error').text(error.statusMessage);
                        });
                    });

                    $('#messageGo').click(function () {
                        $.post("{{ url('api/emailUsers') }}",{message: $('#message').val()}).done(function (data) {
//                            $('#mess').modal('hide');
                        }).fail(function (error) {
                            $('#message-error').show();
                            $('#message-error').text(error.statusMessage);
                            console.log(error);
                            console.log($('#message').val());
                        }).done(function (error) {
//                            location.reload();
                            $('#message-error').text(error.statusMessage);
                        });
                    });
                });
            </script>

            <br/>
            <br/>
            <br/>
        </div>
    </body>


</html>