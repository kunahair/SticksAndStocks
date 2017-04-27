@include('layouts.header')

@include('layouts.navbar')

<div class="container">

    {{--Friends list--}}
    <div class="col-xs-4">
        <h3>Friends</h3>
        {{--Show list of friends--}}
        <table class="table-hover">
            <tbody>
                @foreach(Auth::user()->getFriendList(Auth::user()->id) as $friend)
                    {{--Make row clickable to go to that friends message page--}}
                    <tr onclick="window.document.location=' {{url("messages") . "/" . $friend->id}}'">
                        <td>{{$friend->name}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-xs-8">
        <h3>{{$user->name}}</h3>
        {{--Form that enables user to send message to selected Friend--}}
        <form action="{{url("messages") . '/' . $user->id}}" method="POST">
            <textarea id="message" name="message" type="text" class="form-control"></textarea>
            <input type="hidden" name="id" value="{{Auth::user()->id}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary" style="margin-top: 2%; font-weight: bold">Send</button>
        </form>

        @if($data["error"] != null)
            <div class="alert alert-danger" style="margin-top: 2%">{{$data["error"]}}</div>
        @endif

        {{--Show table of all messages--}}
        <table class="table-hover" style="margin-top: 2%; margin-bottom: 5%">
            <tbody>
                @foreach($data["messages"] as $message)
                    {{--If the message is read, then leave row as default--}}
                    @if($message->read)
                        <tr>
                    @else
                        {{--Otherwise change the background colour to inicate to the user the message has not been read--}}
                        <tr style="background-color: #ffe6b3">
                    @endif
                            <td>
                                <div style="font-weight: bold">
                                    {{--Show who sent the message--}}
                                    @if($message->from == $user->id)
                                        {{$user->name}}
                                    @else
                                        {{Auth::user()->name}}
                                    @endif
                                </div>
                                <div>{{$message->message}}<br /></div>
                                <div style="text-align: right">{{$message->created_at}}</div>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('layouts.footer')