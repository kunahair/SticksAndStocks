{{--/**--}}
{{--* Created by: Josh Gerlach.--}}
{{--* Authors: Josh Gerlach, Sadhurshan Ganeshan and Abnezer Yhannes--}}
{{--*/--}}

@include('layouts.header')

@include('layouts.navbar')

<div class="container">

    <div class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{--Friends list--}}
    <div class="col-xs-4">
        <h3>Friends</h3>
        {{--Show list of friends--}}
        <table class="table-hover">
            <tbody>
                @foreach(Auth::user()->getFriendList(Auth::user()->id) as $friend)
                    {{--Make row clickable to go to that friends message page--}}
                    <tr onclick="window.document.location=' {{url("messages") . "/" . $friend->id}}'" class="clickable-table-item">
                        <td>{{$friend->name}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{--Messages Form and List--}}
    <div class="col-xs-8">
        <h3>{{$user->name}}</h3>
        {{--Form that enables user to send message to selected Friend--}}
        <form action="{{url("messages") . '/' . $user->id}}" method="POST">
            <textarea id="message" name="message" type="text" class="form-control" placeholder="Hi {{$user->name}}"></textarea><br />
            <input id="money" name="money" type="text" class="form-control" placeholder="Send Money"/>
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
            {{--@foreach($data["moneyTransfers"] as $moneyTransfer)--}}
                {{--{{var_dump($moneyTransfer->to_message)}}--}}
            {{--@endforeach--}}

                @foreach($data["messages"] as $message)

                    {{--Check if the message has a Money Transfer attached to it--}}
                    @if(key_exists($message->id, $data["moneyTransfers"]))

                        {{--If the user recieved the money from friend--}}
                        @if($data["moneyTransfers"][$message->id]->from == $user->id)

                            <tr>
                                <td>
                                    <div style="font-weight: bold">
                                        {{$user->name}}
                                    </div>
                                    <div class="money-message">
                                        "{{$message->message}}"
                                    </div>
                                    <div>
                                        {{$user->name}} sent you: ${{$data["moneyTransfers"][$message->id]->amount}}<br />
                                        {{--If the money transfer has not been read, then show the form to send back a reply--}}
                                        @if(!$data["moneyTransfers"][$message->id]->to_read)
                                            <form action="{{url("messages")}}/accept-money/{{$user->id}}" method="POST">
                                                <input type="hidden" name="friend_id" value="{{$user->id}}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="money_id" value="{{$data["moneyTransfers"][$message->id]->id}}">
                                                <label style="font-weight: 100">How much would you like to accept? $</label>
                                                <input id="moneyAccept"
                                                       name="moneyAccept"
                                                       type="text"
                                                       value="{{number_format($data["moneyTransfers"][$message->id]->amount, 2)}}"
                                                />
                                                <button id="acceptMoney" type="submit" class="btn btn-primary">Accept</button>
                                            </form>

                                            @else
                                                {{--Otherwise, just show the accepted--}}
                                                <div>
                                                    You accepted: ${{$data["moneyTransfers"][$message->id]->taken}}
                                                </div>

                                        @endif

                                    </div>
                                    <div style="text-align: right">{{$message->created_at}}</div>
                                </td>
                            </tr>
                            {{--If the User is the person that sent the money, but the reciever has not sent a reply
                                Just show that the User sent money--}}
                            @elseif(!$data["moneyTransfers"][$message->id]->to_read)
                                <tr>
                                    <td>
                                        <div style="font-weight: bold">
                                            {{Auth::user()->name}}
                                        </div>
                                        <div class="money-message">
                                            "{{$message->message}}"
                                        </div>
                                        <div>
                                            You sent ${{$data["moneyTransfers"][$message->id]->amount}}
                                        </div>
                                    </td>
                                    <div style="text-align: right">{{$message->created_at}}</div>
                                </tr>
                        @else
                            {{--Otherwise, show how much the User sent and what the Friend Accepted--}}
                            <tr>
                                <td>
                                    <div style="font-weight: bold">
                                        {{Auth::user()->name}}
                                    </div>
                                    <div class="money-message">
                                        "{{$message->message}}"
                                    </div>
                                    <div>
                                        You sent ${{$data["moneyTransfers"][$message->id]->amount}}<br />
                                        They accepted: ${{$data["moneyTransfers"][$message->id]->taken}}
                                    </div>
                                    <div style="text-align: right">{{$message->created_at}}</div>
                                </td>
                            </tr>

                        @endif

                        @continue
                    @endif

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