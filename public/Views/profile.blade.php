@extends('layouts.editpage')

@section('content')
    <style>
        .page-head {
            text-align: center;
            font-weight: 600;
        }

        .main-info {
            border: 1px solid grey;
            border-radius: 5px;
            padding: 15px;
            margin-left: 5px;
            margin-right: 10px;
        }

        .info-head {
            margin-top: -25px;
            background-color: #ffffff;
            border: 1px solid grey;
            border-radius: 5px;
            width: 30%;
            text-align: center;
        }

    </style>
    <h3 class="page-head">Admin Profile</h3><br>
    <div class="container-fluid" style="width: 95%">
            <div class="panel panel-primary">
                <div class="panel-heading"><b>{{$admin['first_name']}} # {{$admin['admin_id']}}</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sl-4">
                            <div class="main-info">
                                <div class="info-head">Avatar</div>
                                <div class="main-avatar-div">
                                    <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                         style="cursor: pointer;"
                                         src="@if ($admin['logo']!=null) {{$admin['logo']}} @else /img/mystery-man.png @endif"/>

                                </div>
                                <form id="data" method="post" enctype="multipart/form-data">
                                    <div><input type="file" name="uploads" accept="image/*" style="display: none"
                                                id="selectFileDialog"></div>
                                    <div><input type="text" style="margin-top: 5px" hidden name="user_id"
                                                value="{{$user['user_id']}}"></div>
                                    <div>
                                        <button style="display: none" class="btn btn-primary small" id="uploadnow">
                                            Upload
                                            now!
                                        </button>
                                    </div>
                                    <div class="ajax-respond"></div>
                                </form>
                            </div>
                        </div>
                        <form class="form-horizontal" name="form" method="post"
                              action="/admin/profile/{{$admin->admin_id}}">

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="main-info">
                                    <div class="info-head">Login info</div>
                                    <label for="email">Email: </label>
                                    <input name="email" id="email" value="{{$admin->email}}"
                                           placeholder="enter email here">

                                    <label for="pass">Password:</label>
                                    <input type="password" value="{{$admin->password}}" id="pass" name="password"
                                           placeholder="input pasword here">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="main-info">
                                    <div class="info-head">Main info</div>
                                    <label for="first_name">First name: </label><input name="first_name" id="first_name"
                                                                                       value="{{$admin->first_name}}"
                                                                                       placeholder="enter first name here">

                                    <label for="last_name">Last name: </label><input name="last_name" id="last_name"
                                                                                     value="{{$admin->last_name}}"
                                                                                     placeholder="enter last name here">


                                </div>
                                <div class="buttons-group">
                                    <div class="control-group">
                                        <div class="row">
                                            <div class="col-ld-6 col-md-6 col-sm-6">
                                                <button style="margin: 0 15px" class="btn btn-primary " type="submit"
                                                        name="Save"
                                                        id="save-btn">
                                                    <i class="fa fa-check"></i>Save
                                                </button>
                                            </div>
                                            <div class="col-ld-6 col-md-6 col-sm-6">
                                                <button class="btn btn-danger" type="cancel" name="cancel">
                                                    <i class="fa fa-times"></i>Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer"></div>
            </div>
        <div class="panel panel-primary" @if($admins==null) style="display: none" @endif>
            <div class="panel-heading services-panel"><b>Admin's List</b></div>
            <div class="panel-body">
                <div class="row">
                    @foreach($admins as $this_admin)
                        @if (gmp_mod($loop->iteration, 5)==0)
                </div>
                <div class="row">
                    @endif
                    <div class="col-lg-3 col-md-3 col-sm-3" @if ($loop->iteration >4) style="display:none;"
                         id="hiddenPanel" @endif>
                        <div class="panel panel-primary panel-heading-select">
                            <div class="panel-heading"><b>{{$this_admin['first_name']}} {{$this_admin['last_name']}}
                                    # {{$this_admin['admin_id']}}</b></div>
                            <div class="panel-body">
                                <div></div>
                                <div class="panel-img">
                                    <img src="@if ($this_admin['logo']!=null) {{$this_admin['logo']}} @else /img/mystery-man.png @endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-primary" id="showServiceBtn">Show more services >>></button>
            </div>

        </div>
    </div>
@stop