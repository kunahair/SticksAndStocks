{{--/**--}}
{{--* Created by: Josh Gerlach.--}}
{{--* Authors: Josh Gerlach and Abnezer Yhannes--}}
{{--*/--}}

@include('layouts.header')

@include('layouts.navbar')


<div class="container-box">

    {{--Error Message Display--}}
    <div class="alert alert-danger" id="friendError" style="display: none"></div>
    {{--Success Message Display--}}
    <div class="alert alert-success" id="friendSuccess" style="display: none"></div>

    <div class="col-xs-12">
        <div class="col-xs-1 col-md-2"></div>
        <div class="col-xs-10 col-md-4" style="text-align: center">
            <h2>{{$user->name}}</h2>
            <img src="http://placehold.it/350x350" style="width: 100%; height: auto">

            {{--//**
         * 0 - not friends
         * 1 - are friends
         * 2 - same user
         * 3 - no user found
         * 4 - friend request pending
         * 5 - waiting for friend to accept
         *
         * 10 - Error, reached the end of the function with no return
         */--}}

            <div style="margin-top: 5%;">
                @if($user->isFriend($user->id, Auth::user()->id) == 0)
                    <button id="addFriendButton" class="btn btn-primary">Add Friend</button>
                @elseif($user->isFriend($user->id, Auth::user()->id) == 1)
                    <a href="{{url('messages') . '/' . $user->id}}"><button id="sendMessageButton" class="btn btn-primary">Send Message</button></a>
                @elseif($user->isFriend($user->id, Auth::user()->id) == 2)
                    <p></p>
                @elseif($user->isFriend($user->id, Auth::user()->id) == 4)
                    <button id="acceptFriendRequestButton" class="btn btn-primary">Accept Friend Request</button>
                @elseif($user->isFriend($user->id, Auth::user()->id) == 5)
                    <p>Friend Request Pending...</p>
                @elseif($user->isFriend($user->id, Auth::user()->id) == 10)
                    <p>Something went wrong, please reload page</p>
                @endif
            </div>
        </div>

        <div class="col-xs-10 col-md-4" >
            <h2 style="font-weight: bold"><br />Stats:</h2>
            <p><h3>Total Growth: {{number_format(Growth::getTotalGrowth($user->id),2)}}</h3></p>
            <p><h3>Leaderboard: {{Auth::user()->getLeaderBoardPosition($user->id)}}</h3></p>
            <p><h3>Number of Friends: {{count($user->getFriendList($user->id))}}</h3></p>
        </div>
        <div class="col-xs-1 col-md-2"></div>
    </div>


    <script type="application/javascript">

        //Function for when a User sends a Friend Request to another User
        function addFriend(userId, authUserId) {
            var postData = {};

            postData["to"] = userId;
            postData["from"] = authUserId;

            //AJAX to the API to send friend request
            $.post("{{ url('api/sendFriendRequest') }}", postData)
            //If all good, hide the error message and show success message
                .done(function (data) {
                    $('#friendSuccess').text('Friend Request Sent').css('display', 'block');
                    $('#addFriendButton').replaceWith('<p>Friend Request Pending...</p>');
                    $('#friendError').css('display', 'none');
                    $('#addFriend').css('display', 'none');

                })
                //If there is an error, display error message to user
                .fail(function (error) {
                    console.log(error);
                    $('#friendError').css('display', 'block').text(error["responseText"]);
                    $('#friendSuccess').css('display', 'none');

                    if (error["status"] == 400)
                    {
                        window.setTimeout(function () {
                            location.reload();
                        }, 1500);
                    }
                })
            ;
        }

        //When a User Accepts a Pending Friend Request from another User
        function acceptFriendRequest(userId, authUserId) {
            var postData = {};

            postData["from"] = userId;
            postData["to"] = authUserId;

            //AJAX to the API to accept friend request
            $.post("{{ url('api/acceptFriendRequest') }}", postData)
            //If all good, hide the error message and show success message
                .done(function (data) {
                    $('#friendSuccess').text('Friend Request Accepted').css('display', 'block');
                    $('#friendError').css('display', 'none');
                    $('#acceptFriendRequestButton').css('display', 'none');
                    $('#acceptFriendRequestButton').replaceWith({{'<a href="' . url('messages') . '/' . $user->id}} +
                        '"<button id="sendMessageButton" class="btn btn-primary">Send Message</button>');
                })
                //If there is an error, display error message to user
                .fail(function (error) {
                    console.log(error);
                    $('#friendError').css('display', 'block').text(error["responseText"]);
                    $('#friendSuccess').css('display', 'none');
                })
            ;
        }

        //When add Friend button clicked, wrap up data and POST to server
        $('#addFriendButton').click(function () {
            var userId = {{$user->id}};
            var authUserId = {{Auth::user()->id}};

           addFriend(userId, authUserId);
        });

        //When accept Friend Request button clicked, wrap up data and POST to server
        $('#acceptFriendRequestButton').click(function () {
            var userId = {{$user->id}};
            var authUserId = {{Auth::user()->id}};

            acceptFriendRequest(userId, authUserId);
        });

        //Method to turn HTML encoded entities into their string representation
        function htmlDecode(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        }

    </script>


</div>