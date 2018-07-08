@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }
    </style>
    <h3 class="page-head">{{$lang['edit_worker']}}</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$worker['first_name']}} {{$worker['last_name']}}
                    # {{$worker['worker_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">{{$lang['avatar']}}</div>
                            <div class="main-avatar-div">
                                <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                     style="cursor: pointer;"
                                     src="@if ($worker['logo']!=null) {{$worker['logo']}} @else /img/mystery-man.png @endif"/>

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
                    <form class="form-horizontal" name="form" id="form-edit" method="post"
                          action="/api/admin/worker/{{$worker['worker_id']}}">
                        <div class="col-lg-4 col-md-4 col-sl-4" style="margin-bottom: 15px">
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
                                               value="{{$worker['first_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="last_name">{{$lang['last_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="last_name" name="last_name"
                                               value="{{$worker['last_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Specialization">{{$lang['specialization']}}</label>
                                    <div class="controls">
                                        <input type="text" id="Specialization" name="specialization"
                                               value="{{$worker['specialization']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="start_year">{{$lang['from']}}</label>
                                    <div class="controls">
                                        <input type="text" id="start_year" name="start_year"
                                               value="{{$worker['start_year']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="phone">{{$lang['phone']}}</label>
                                    <div class="controls">
                                        <input type="text" id="phone" name="phone"
                                               value="{{$worker['phone']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="salon_id">{{$lang['salon_id']}}</label>
                                    <div class="controls">
                                        <a href="/api/admin/salon/{{$worker['salon_id']}}">
                                            <input type="text" id="salon_id" name="salon_id"
                                                   value="{{$worker['salon_id']}}" disabled style="cursor:pointer">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons-group">
                                <div class="control-group">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <button style="margin: 0 15px" class="btn btn-primary " type="submit"
                                                    name="Save"
                                                    id="save-btn"><i class="fa fa-check"></i>{{$lang['save']}}
                                            </button>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <button class="btn btn-danger" type="reset" name="cancel">
                                                <i class="fa fa-times"></i>{{$lang['cancel']}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sl-4">
                            <div class="main-info" style="min-height:320px">
                                <div class="info-head">{{$lang['queue']}}</div>
                                <!-- Full calendar - START -->
                                <div id='calendar' style="min-height: 320px"></div>
                                <!-- Full calendar - END -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer">
                <div class="row">
                    <div class="col-ld-3 col-md-3 col-sm-3">
                    </div>
                    <div class="col-ld-3 col-md-3 col-sm-3">
                        <button class="btn btn-primary" name="Message" id="messageBtn">
                            <span class="fa fa-envelope-o"></span>{{$lang['send_message']}}
                        </button>
                    </div>
                    <div class="col-ld-3 col-md-3 col-sm-3">
                        <button class="btn btn-info" name="Password" id="password">
                            <span class="fa fa-lock"></span>{{$lang['send_new_pass']}}
                        </button>
                    </div>
                    <div class="col-ld-3 col-md-3 col-sm-3">
                        <button class="btn btn-info" name="Service" id="newServiceBtn">
                            <span class="fa fa-wrench"></span>{{$lang['new_service']}}
                        </button>
                    </div>
                    {{--<div class="col-ld-3 col-md-3 col-sm-3">--}}
                        {{--<button class="btn btn-info" name="schedules" id="schedulesBtn">--}}
                            {{--<span class="fa fa-lock"></span>Change Schedules--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    {{--<div class="col-ld-2 col-md-2 col-sm-2">--}}
                        {{--<button class="btn btn-info" name="queue" id="queueBtn">--}}
                            {{--<span class="fa fa-lock"></span>Add queue--}}
                        {{--</button>--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading services-panel"><b>{{$lang['worker_service']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    @foreach($services as $service)
                        @if (gmp_mod($loop->iteration, 5)==0)
                </div>
                <div class="row">
                    @endif
                    <div @if ($loop->iteration >4) class="col-lg-3 col-md-3 col-sm-3 hiddenPanel"  style="display:none;"
                         @else class="col-lg-3 col-md-3 col-sm-3" @endif>
                        <div class="panel panel-primary panel-heading-select" id="service_{{$service['service_id']}}">
                            <div class="panel-heading"><b>{{$service['name']}} # {{$service['service_id']}}</b></div>
                            <div class="panel-body">
                                <div></div>
                                <div class="panel-img">
                                    <img src='{{$service['worker']['service_logo']}}'>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div>{{$lang['duration']}}: {{$service['duration']}} {{$lang['minute']}}</div>
                                <div>{{$lang['price_min']}}: {{$service['price_min']}}₪
                                </div>
                                <div>{{$lang['price_max']}}: {{$service['price_max']}} ₪
                                </div>
                                <div>{{$lang['description']}}: {{$service['worker']['description']}}</div>
                            </div>
                        </div>
                    </div>
                    <?php $loop_max = $loop->iteration?>
                    @endforeach
                </div>
            </div>
            <div class="panel-footer">
                @if ($loop_max > 4)
                    <button class="btn btn-primary" id="showServiceBtn">{{$lang['show_more_service']}}</button>
                @endif
            </div>
        </div>
        <div class="new-form-wrapper">
            <!-- register new worker -->

            <div class="new-service-form">
                <div class="form">
                    <form method="post" action="/api/service/worker/{{$worker['worker_id']}}/" class="n-form"
                          id='newServiceForm'>
                        <div class="title">{{$lang['service']}}</div>
                        <div class="wrapper-field">
                            <label><p>{{$lang['service_id']}}</p>
                                <input type="text" required  name="service_id"></label>
                            <label><p>{{$lang['service_description']}}</p>
                                <input type="text" name="description"></label>
                            <div class="btn-wrapper clearfix">
                                <button type="sumbit" id='serviceSaveBtn' class="btn-primary">{{$lang['save']}}</button>
                                <button type="reset" id='serviceCancelBtn' class="btn-cancel">{{$lang['cancel']}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- open select file dialog  -->
    <script type="text/javascript">
           $('.panel-heading-select').click(function (e) {
            e.preventDefault();
            console.log('select', e.currentTarget.id);
            document.location.href = '/api/admin/'+e.currentTarget.id.split('_')[0]+'/' + e.currentTarget.id.split('_')[1];
        });
        $('#serviceSaveBtn').click(function (e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/api/service/worker/{{$worker['worker_id']}}/"+$('form#newServiceForm input[name=service_id]')["0"].value,
                data: $('form#newServiceForm').serialize()+
                    '&user_id={{$admin['entry_id']}}'+
                    '&token={{$admin['token']}}',
                success: function (result) {
                    if (result.error){
                        alert('Service doesn\'t created! Error:' + result.message);
                    }else{
                        alert('OK. Service created.');
                        //document.location.href = 'https://hairtime.co.il/admin/salon/{{$salon['salon_id']}}';
                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Service doesn\'t created! Not connect.\n Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Service doesn\'t created! Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                        alert('Service doesn\'t created! Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Service doesn\'t created! Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Service doesn\'t created! Time out error.');
                    } else if (exception === 'abort') {
                        alert('Service doesn\'t created! Ajax request aborted.');
                    } else {
                        alert('Service doesn\'t created! Uncaught Error.\n' + jqXHR.responseText);
                    }
                }
            });
            $(".new-form-wrapper").fadeOut(500);
        });
        $('#newServiceBtn').click(function (e) {
            e.preventDefault();
            $(".new-form-wrapper").fadeIn(500);
        });
        $('#serviceCancelBtn').click(function () {
            $(".new-form-wrapper").fadeOut(500);
        });

        $('#showServiceBtn').click(function (e) {
            e.preventDefault();
            var panels = $('.hiddenPanel');
            if ($('#showServiceBtn').text() == 'Show more services') {
                $('#showServiceBtn').text('Hide services');
                panels.fadeIn(300);
            } else if ($('#showServiceBtn').text() == 'Hide services') {
                $('#showServiceBtn').text('Show more services');
                panels.fadeOut(300);
            }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            var events = [];
            // console.log('docum redy');
            moment().format();
            // console.log(moment().dayOfYear(1).format('DD.MM.YYYY'));
            // console.log(moment().dayOfYear(366).format('DD.MM.YYYY'));
            var week_day    = (new Date()).getDay();
            // var start       = moment().day(0);
            var start       = moment().dayOfYear(1).format('DD.MM.YYYY');
            var stop        = moment().dayOfYear(366).format('DD.MM.YYYY');
            $.ajax({
                method: 'GET',
                type: 'GET',
                url: "/api/queue/worker/{{$worker['worker_id']}}",
                data: {
                    worker_id   : '{{$worker['worker_id']}}',
                    user_id     : '{{$admin['entry_id']}}',
                    from        : start,
                    to          : stop,
                    token       : '{{$admin['token']}}',

                },
                success: function (result) {
                    if (result.error){
                        alert('Can\'t receive queue. Error: ' + result.message);
                    }else{
                        result.forEach(function(value, index){
                            console.log('value', value);
                            console.log('name ', value.queue.name);
                            console.log('time ', value.queue.time);
                            console.log('duration ', value.queue.duration);
                            events.push({
                                title   : value.queue.name,
                                start   : value.queue.time,
                                end     : moment(value.queue.time).add(value.queue.duration,'m').format('YYYY-MM-DD HH:mm'),
                                description: 'admin/service/'+value.queue.service_id
                            });
                        });
                        console.log('events ',events);
                        $('#calendar').fullCalendar({
                            height              : 'parent',
                            bootstrapFontAwesome : {
                                close: 'fa-times',
                                prev: 'fa-chevron-left',
                                next: 'fa-chevron-right',
                                prevYear: 'fa-angle-double-left',
                                nextYear: 'fa-angle-double-right'
                            },
                            themeSystem         : 'bootstrap4',
                            timeFormat          : 'H:mm', // uppercase H for 24-hour clock
                            displayEvantEnd     : true,
                            defaultView         : 'listWeek',
                            isRTL               : true,
                            locale              : '{{$lang['lang']}}',
                            events:  events
                        });
                        $('.fc-toolbar h2').css('font-size', 18);
                        $('.fc-toolbar ').css('text-align', 'center');
                        $('.fc-toolbar button').css('margin-top', '15px');
                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Service doesn\'t created! Not connect.\n Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Service doesn\'t created! Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                        alert('Service doesn\'t created! Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Service doesn\'t created! Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Service doesn\'t created! Time out error.');
                    } else if (exception === 'abort') {
                        alert('Service doesn\'t created! Ajax request aborted.');
                    } else {
                        alert('Service doesn\'t created! Uncaught Error.\n' + jqXHR.responseText);
                    }
                }
            });

            // $('.fc-toolbar .fc-right').hide();

            $.ajax({
                method: 'GET',
                url: "/api/worker/schedule/{{$worker['worker_id']}}",
                headers: {
                    'User-ID': '{{$user['user_id']}}',
                    'Token': '{{$admin['token']}}',
                },
                success: function (ajax_result, textStatus) {
                    console.log('schedules: ', ajax_result);
                }
            });

            const worker_id = '<?=$worker['worker_id']?>';
            $.ajax({
                method: 'get',
                url: '/api/service/worker/' + worker_id,
                success: function (result, textStatus) {
                    if (result.status == 'success') {
                        //document.images["main-avatar"].src = result.url;
                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('User doesn\'t deleted! Not connect.\n Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('User doesn\'t deleted! Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                        alert('User doesn\'t deleted! Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('User doesn\'t deleted! Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('User doesn\'t deleted! Time out error.');
                    } else if (exception === 'abort') {
                        alert('User doesn\'t deleted! Ajax request aborted.');
                    } else {
                        alert('User doesn\'t deleted! Uncaught Error.\n' + jqXHR.responseText);
                    }
                    icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                    icon_lock.addClass('fa-trash-o fa-2x');

                }
            })
        });
    </script>
@stop