@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }
    </style>
    <h3 class="page-head">Edit Worker</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$worker['first_name']}} {{$worker['last_name']}}
                    # {{$worker['worker_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">Avatar</div>
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
                          action="/admin/worker/{{$worker['worker_id']}}">
                        <div class="col-lg-4 col-md-4 col-sl-4" style="margin-bottom: 15px">
                            <div class="main-info">
                                <div class="info-head">Main info</div>
                                <div class="control-group">
                                    <label class="control-label" for="business_name">User email</label>
                                    <div class="controls">
                                        <input type="text" id="email" name="email"
                                               placeholder="Enter business name here"
                                               value="{{$user['email']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="first_name">First name</label>
                                    <div class="controls">
                                        <input type="text" id="first_name" name="first_name"
                                               placeholder="Enter first name here"
                                               value="{{$worker['first_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="last_name">Last name</label>
                                    <div class="controls">
                                        <input type="text" id="last_name" name="last_name"
                                               placeholder="Enter last name here"
                                               value="{{$worker['last_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Specialization">Specialization</label>
                                    <div class="controls">
                                        <input type="text" id="Specialization" name="specialization"
                                               placeholder="Enter phone here"
                                               value="{{$worker['specialization']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="start_year">Start year</label>
                                    <div class="controls">
                                        <input type="text" id="start_year" name="start_year"
                                               placeholder="Enter founded year here"
                                               value="{{$worker['start_year']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="phone">Phone</label>
                                    <div class="controls">
                                        <input type="text" id="phone" name="phone"
                                               value="{{$worker['phone']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="salon_id">Salon id</label>
                                    <div class="controls">
                                        <a href="/admin/salon/{{$worker['salon_id']}}">
                                            <input type="text" id="salon_id" name="salon_id"
                                                   value="{{$worker['salon_id']}}" disabled style="cursor:pointer">
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sl-4">
                            <div class="main-info">
                                <div class="info-head">Schedules & Queue</div>
                                <!-- Responsive calendar - START -->
                                <div class="responsive-calendar">
                                    <div class="controls">
                                        <a class="pull-left" data-go="prev">
                                            <div class="btn btn-primary btn-circle"><</div>
                                        </a>
                                        <h5><span data-head-year></span> <span data-head-month></span></h5>
                                        <a class="pull-right" data-go="next">
                                            <div class="btn btn-primary btn-circle">></div>
                                        </a>
                                    </div>
                                    <hr/>
                                    <div class="day-headers">
                                        <div class="day header">Sun</div>
                                        <div class="day header">Mon</div>
                                        <div class="day header">Tue</div>
                                        <div class="day header">Wed</div>
                                        <div class="day header">Thu</div>
                                        <div class="day header">Fri</div>
                                        <div class="day header">Sat</div>
                                    </div>
                                    <div class="days" data-group="days">

                                    </div>
                                </div>
                                <!-- Responsive calendar - END -->
                            </div>
                            <div class="buttons-group">
                                <div class="control-group">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <button style="margin: 0 15px" class="btn btn-primary " type="submit"
                                                    name="Save"
                                                    id="save-btn"><i class="fa fa-check"></i>Save
                                            </button>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
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
            <div class="panel-footer" id="panel-footer">
                <div class="row">
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-primary" name="Message" id="messageBtn">
                            <span class="fa fa-envelope-o"></span>Send Message
                        </button>
                    </div>
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-info" name="Password" id="password">
                            <span class="fa fa-lock"></span>Send new pass
                        </button>
                    </div>
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-info" name="Service" id="newServiceBtn">
                            <span class="fa fa-wrench"></span>New service
                        </button>
                    </div>
                    <div class="col-ld-3 col-md-3 col-sm-3">
                        <button class="btn btn-info" name="schedules" id="schedulesBtn">
                            <span class="fa fa-lock"></span>Change Schedules
                        </button>
                    </div>
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-info" name="queue" id="queueBtn">
                            <span class="fa fa-lock"></span>Add queue
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading services-panel"><b>Worker's Services</b></div>
            <div class="panel-body">
                <div class="row">
                    @foreach($services as $service)
                        @if (gmp_mod($loop->iteration, 5)==0)
                </div>
                <div class="row">
                    @endif
                    <div class="col-lg-3 col-md-3 col-sm-3" @if ($loop->iteration >4) style="display:none;"
                         id="hiddenPanel" @endif>
                        <div class="panel panel-primary panel-heading-select">
                            <div class="panel-heading"><b>{{$service['name']}} # {{$service['service_id']}}</b></div>
                            <div class="panel-body">
                                <div></div>
                                <div class="panel-img">
                                    <img src='{{$service['worker']['service_logo']}}'>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div>Duration: {{$service['duration']}} min</div>
                                <div>Price MIN: {{$service['duration']}} <i class="fa fa-sheqel" aria-hidden="true"></i>
                                </div>
                                <div>Price MAX: {{$service['duration']}} <i class="fa fa-eur" aria-hidden="true"></i>
                                </div>
                                <div>Description: {{$service['worker']['description']}}</div>
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
        <div class="new-form-wrapper">
            <!-- register new worker -->

            <div class="new-service-form">
                <div class="form">
                    <form method="post" action="/service/worker/{worker_id}/{service_id}" class="n-form"
                          id='newServiceForm'>
                        <div class="title">Services</div>
                        <div class="wrapper-field">
                            <input type="text" required value="{{$worker['worker_id']}}" name="worker_id" hidden>
                            <label><p>Servie ID number</p><input type="text" required
                                                                 placeholder="service_id - Servie ID number"
                                                                 name="service_id"></label>
                            <label><p>Service’s description</p><input type="text"
                                                                      placeholder="Service's name for this Worker. "
                                                                      name="description"></label>
                            <div class="btn-wrapper clearfix">
                                <button type="sumbit" id='serviceSaveBtn' class="btn-primary">Save</button>
                                <button type="reset" id='serviceCancelBtn' class="btn-cancel">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <pre><? echo var_dump($salon_services)?></pre>

        <div id="content"></div>
    </div>
    <!-- open select file dialog  -->
    <script type="text/javascript">
        $('#serviceSaveBtn').click(function (e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/api/service/salon/{{$salon['salon_id']}}",
                data: $('form#newServiceForm').serialize(),
                headers: {
                    'User-ID': '{{$admin['entry_id']}}',
                    'Token': '{{$admin['token']}}',
                },
                success: function (result) {
                    alert('OK. Service created.');
                    //document.location.href = 'https://hairtime.co.il/admin/salon/{{$salon['salon_id']}}';
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
            var panels = $('#hiddenPanel');
            if ($('#showServiceBtn').text() == 'Show more services >>>') {
                $('#showServiceBtn').text('Hide services <<<');
                panels.fadeIn(300);
            } else if ($('#showServiceBtn').text() == 'Hide services <<<') {
                $('#showServiceBtn').text('Show more services >>>');
                panels.fadeOut(300);
            }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            console.log('docum redy');
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
            $(".responsive-calendar").responsiveCalendar({
                startFromSunday: true,
                time: new Date().getFullYear() + '-' + new Date().getMonth(),
                events: {
                    "2017-11-30": {},
                    "2017-11-26": {},
                    "2017-11-03": {},
                    "2017-11-12": {}
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