@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }
    </style>
    <h3 class="page-head">{{$lang['edit_service']}}</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$service['name']}}
                    # {{$service['service_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">{{$lang['avatar']}}</div>
                            <div class="main-avatar-div">
                                <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                     style="cursor: pointer;"
                                     src="@if ($service['logo']!=null) {{$service['logo']}} @else /api/public/img/mystery-man.png @endif"/>

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
                                <div class="info-head">{{$lang['main_info']}}</div>
                                <div class="control-group">
                                    <label class="control-label" for="name">{{$lang['service_name']}}</label>
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
                                    <label class="control-label" for="duration">{{$lang['duration']}}</label>
                                    <div class="controls">
                                        <input type="text" id="duration" name="duration"
                                               value="{{$service['duration']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price_min">{{$lang['price_min']}} ₪</label>
                                    <div class="controls">
                                        <input type="text" id="price_min" name="price_min"
                                               value="{{$service['price_min']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price_max">{{$lang['price_max']}} ₪</label>
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
                                                    <span class="fa fa-check"></span>{{$lang['save']}}
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button class="btn btn-danger" type="reset" name="reset">
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

                        {{--<div class="main-info">--}}
                            {{--<div class="info-head">Schedules</div>--}}

                        {{--</div>--}}
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer">
                <div class="container">
                    <button class="btn btn-info" name="Delete" id="delete-service">
                        <span class="fa fa-trash-o"></span>{{$lang['delete_this_service']}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="content"></div>
<script>
        $('#delete-service').click(function (e) {
      e.preventDefault()
      $('.popup').html($('.popup-delete-dialog').html());
      $('.popup-dialog').fadeIn(500);
      $('.popup').find("#yes").click(function () {
        $.ajax({
          method: 'POST',
          type: 'POST',
          url: "/api/ajax/service/delete/{{$service['service_id']}}",
          headers: {
            'User-ID': '{{$admin['entry_id']}}',
            'Token': '{{$admin['token']}}',
          },
          success: function (ajax_result, textStatus) {
              if (ajax_result.error){
                  alert('Service doesn\'t deleted! Error: '+ ajax_result.message);
              }else{
                alert('Service successfully deleted !');
                document.location.href = '/api/admin/service';
              }

          },
          error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
              alert('Service doesn\'t deleted! Not connect.\n Verify Network.');
            } else if (jqXHR.status == 401) {
              alert('Service doesn\'t deleted! Unauthorized request. [401]');
            } else if (jqXHR.status == 404) {
              alert('Service doesn\'t deleted! Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
              alert('Service doesn\'t deleted! Internal Server Error [500].');
            } else if (exception === 'parsererror') {
              alert('Service doesn\'t deleted! Requested JSON parse failed.');
            } else if (exception === 'timeout') {
              alert('Service doesn\'t deleted! Time out error.');
            } else if (exception === 'abort') {
              alert('Service doesn\'t deleted! Ajax request aborted.');
            } else {
              alert('Service doesn\'t deleted! Uncaught Error.\n' + jqXHR.responseText);
            }
            $('.popup-dialog').fadeOut(500);

          }
        })
      });
      $('.popup').find("#close").click(function () {
        e.preventDefault();
        $('.popup-dialog').fadeOut(500);
      });
    });
</script>
@stop