@include('layouts.header')

@include('layouts.navbar')


<div class="container">

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

    @if($user->isFriend($user->id, Auth::user()->id) == 0)
        <button id="addFriendButton">Add Friend</button>
    @elseif($user->isFriend($user->id, Auth::user()->id) == 1)
        <a href="{{url('messages') . '/' . $user->id}}"><button id="sendMessageButton">Send Message</button></a>
    @elseif($user->isFriend($user->id, Auth::user()->id) == 2)
        <p></p>
    @elseif($user->isFriend($user->id, Auth::user()->id) == 4)
        <button id="acceptFriendRequestButton">Accept Friend Request</button>
    @elseif($user->isFriend($user->id, Auth::user()->id) == 5)
        <p>Friend Request Pending...</p>
    @elseif($user->isFriend($user->id, Auth::user()->id) == 10)
        <p>Something went wrong, please reload page</p>
    @endif
    <div class="alert alert-danger" id="friendError" style="display: none"></div>
    <div class="alert alert-success" id="friendSuccess" style="display: none"></div>


    <h2>{{$user->name}}</h2>
    <h2>{{$user->email}}</h2>
    @foreach($user->getFriendList(Auth::user()->id) as $friend)
        {{$friend}}
    @endforeach

    {{number_format(Growth::getTotalGrowth(Auth::user()->id),2)}}


    <script type="application/javascript">

        function addFriend(userId, authUserId) {
            var postData = {};

            postData["to"] = userId;
            postData["from"] = authUserId;

            //AJAX to the API to send friend request
            $.post("{{ url('api/sendFriendRequest') }}", postData)
            //If all good, hide the error message and show success message
                .done(function (data) {
                    $('#friendSuccess').text('Friend Request Sent').css('display', 'block');
                    $('#addFriendButton').replaceWith('<button id="sendMessageButton">Send Message</button>');
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


        {{--{{$growth}}--}}

</div>