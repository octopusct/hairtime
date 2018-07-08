@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }
    </style>
    <h3 class="page-head">{{$lang['edit_customer']}}</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$customer['first_name']}} {{$customer['last_name']}}
                    # {{$customer['customer_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">{{$lang['avatar']}}</div>
                            <div class="main-avatar-div">
                                <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                     style="cursor: pointer;"
                                     src="@if ($customer['logo']!=null) {{$customer['logo']}} @else /api/public/img/mystery-man.png @endif"/>

                            </div>
                            <form id="data" method="post" enctype="multipart/form-data">
                                <div><input type="file" name="uploads" accept="image/*" style="display: none"
                                            id="selectFileDialog"></div>
                                <div><input type="text" style="margin-top: 5px" hidden name="user_id"
                                            value="{{$user['user_id']}}"></div>
                                <div>
                                    <button style="display: none" class="btn btn-primary small" id="uploadnow">Upload
                                        now!
                                    </button>
                                </div>
                                <div class="ajax-respond"></div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sl-4" style="margin-bottom: 15px">
                        <form class="form-horizontal" name="form" id="form-edit" method="post"
                              action="/api/admin/customer/{{$customer['customer_id']}}">
                            <div class="main-info">
                                <div class="info-head">{{$lang['main_info']}}</div>
                                <div class="control-group">
                                    <label class="control-label" for="business_name">{{$lang['user_email']}}</label>
                                    <div class="controls">
                                        <input type="text" id="email" name="email"
                                               value="{{$user['email']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="first_name">{{$lang['first_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="first_name" name="first_name"
                                               value="{{$customer['first_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="last_name">{{$lang['last_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="last_name" name="last_name"
                                               value="{{$customer['last_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="phone">{{$lang['phone']}}</label>
                                    <div class="controls">
                                        <input type="text" id="phone" name="phone"
                                               value="{{$customer['phone']}}">
                                    </div>
                                </div>
                                <div class="buttons-group">
                                    <div class="control-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button style="margin: 0 15px" class="btn btn-primary " type="submit"
                                                        name="Save"
                                                        id="save-btn">
                                                    <span class="fa fa-check"></span>{{$lang['save']}}
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button class="btn btn-danger" type="reset" name="cancel">
                                                    <span class="fa fa-times"></span>{{$lang['cancel']}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">


                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer">
                <div class="container">
                    <button class="btn btn-primary" name="Message" id="messageBtn">
                        <span class="fa fa-envelope-o"></span>{{$lang['send_message']}}
                    </button>
                    <button class="btn btn-info" name="Password" id="password">
                        <span class="fa fa-lock"></span>{{$lang['send_new_pass']}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="content"></div>

@stop