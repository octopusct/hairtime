@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }
    </style>
    <h3 class="page-head">Edit Service</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$service['name']}}
                    # {{$service['service_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">Avatar</div>
                            <div class="main-avatar-div">
                                <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                     style="cursor: pointer;"
                                     src="@if ($service['logo']!=null) {{$service['logo']}} @else /img/mystery-man.png @endif"/>

                            </div>
                            <form id="data" method="post" enctype="multipart/form-data">
                                <div><input type="file" name="uploads" accept="image/*" style="display: none"
                                            id="selectFileDialog"></div>
                                <div><input type="text" style="margin-top: 5px" hidden name="service_id"
                                            value="{{$service['service_id']}}"></div>
                                <div><input type="text" style="margin-top: 5px" hidden name="service_id"
                                            value="{{$salon['salon_id']}}"></div>
                                <div><input type="text" hidden name="user_id"
                                            value="{{$admin['entry_id']}}"></div>
                                <div><input type="text" style="margin-top: 5px" hidden name="service_id"
                                            value="{{$admin['token']}}"></div>
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
                              action="/admin/service/{{$service['service_id']}}">
                            <div class="main-info">
                                <div class="info-head">Main info</div>
                                <div class="control-group">
                                    <label class="control-label" for="name">Service name</label>
                                    <div class="controls">
                                        <input type="text" id="name" name="name"
                                               value="{{$service['name']}}">
                                    </div>
                                </div>
                                {{--<div class="control-group">--}}
                                    {{--<label class="control-label" for="duration_min">Duration nim</label>--}}
                                    {{--<div class="controls">--}}
                                        {{--<input type="text" id="duration_min" name="duration_min"--}}
                                               {{--value="{{$service['duration_min']}}">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="control-group">
                                    <label class="control-label" for="duration">Duration max</label>
                                    <div class="controls">
                                        <input type="text" id="duration" name="duration"
                                               value="{{$service['duration']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price_min">Price Min</label>
                                    <div class="controls">
                                        <input type="text" id="price_min" name="price_min"
                                               value="{{$service['price_min']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price_max">Price Max</label>
                                    <div class="controls">
                                        <input type="text" id="price_max" name="price_max"
                                               value="{{$service['price_max']}}">
                                    </div>
                                </div>
                                <div class="buttons-group">
                                    <div class="control-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button style="margin: 0 15px" class="btn btn-primary " type="submit"
                                                        name="Save"
                                                        id="save-btn">
                                                    <span class="fa fa-check"></span>Save
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button class="btn btn-danger" type="cancel" name="cancel">
                                                    <span class="fa fa-times"></span>Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sl-4">

                        {{--<div class="main-info">--}}
                            {{--<div class="info-head">Schedules</div>--}}

                        {{--</div>--}}
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer">
                <div class="container">
                    <button class="btn btn-info" name="Delete" id="delete">
                        <span class="fa fa-trash-o"></span>Delete this service
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="content"></div>

@stop