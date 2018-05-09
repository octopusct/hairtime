@extends('layouts.main')

@section('content')
    <style>
        .btn {

        }

        .btn-warning {
            width: 120px;
        }

        .btn-primary {
            width: 120px;
        }

        th {
            text-align: center;
        }

        #tcenter {
            text-align: center;
            margin-right: 5px;
        }

        #tbutton {
            max-width: 100px;
        }
    </style>
    <h3 style="margin-top: 50px">{{$lang['messages']}}</h3><br>
    <div class="container-fluid">
        <table class="table">
            <tr>
                <td>
                    <form name="search" action="/api/admin/message" method="GET">
                        <div class="form-inline form-search" style="direction: rtl">
                            <label for="search">{{$lang['search']}}</label>
                            <input type="text" class="search-query" style="width:250px" id="search" name="search">
                        </div>
                    </form>
                </td>
            </tr>
        </table>
        <hr>
        <div class="container-fluid">
            <table id='messages' class="table-striped" cellspacing="0" style="width: 100%">
                <thead>
                <tr style="text-align: center">
                    <th>{{$lang['name']}}</th>
                    <th>{{$lang['message']}}</th>
                    <th>{{$lang['received']}}</th>
                    <th>{{$lang['action']}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($allmessages as $message)
                    <tr>
                        @if($message['answer_at'] == null)
                            <td id="tcenter"><b>{{$message['name']}}</b></td>
                            <td style="width: 45%"><b>{{$message['message']}}</b></td>
                            <td id="tcenter"><b>{{date('d-m-Y', mktime($message['create_at']))}}</b></td>
                        @else
                            <td id="tcenter">{{$message['name']}}</td>
                            <td>{{$message['message']}}</td>
                            <td id="tcenter">{{date('d-m-Y', mktime($message['create_at']))}}</td>
                        @endif
                        <td id="tbutton">
                            <form name="form-inline" method="GET" action="/api/admin/message/{{$message['message_id']}}">
                                @if ($message['answer_at'] == null)
                                    <INPUT TYPE="HIDDEN">
                                    <p>
                                        <button class="btn btn-warning" id="btn" type="submit" style="margin-bottom: 3px" name="operator" value="Answer">
                                            {{$lang['answer']}}
                                        </button>
                                        <button class="btn btn-danger" type="submit" name="operator" value="Delete">
                                            {{$lang['delete']}}
                                        </button>
                                    </p>
                                @else
                                    <INPUT TYPE="HIDDEN">
                                    <p>
                                        <button class="btn btn-primary" type="submit" style="margin-bottom: 3px" name="operator" value="Answer"
                                        >{{$lang['answer_again']}}
                                        </button>
                                        <button class="btn btn-danger" type="submit" name="operator" value="Delete">
                                            {{$lang['delete']}}
                                        </button>
                                    </p>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@stop