@extends('layouts.editpage')

@section('content')

    <style>
        li {
            list-style-type: none;
        }

        .panel-heading-select {
        "min-height: 267px"
        }
    </style>
    <h3 class="page-head">{{$lang['edit_salon']}}</h3><br>
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-heading"><b>{{$salon['business_name']}} # {{$salon['salon_id']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sl-4">
                        <div class="main-info">
                            <div class="info-head">{{$lang['avatar']}}</div>
                            <div class="main-avatar-div">
                                <img class="img-main-avatar" id="img-main-avatar" alt="Click to load new avatar"
                                     style="cursor: pointer;"
                                     src="@if ($salon['logo']!=null) {{$salon['logo']}} @else /img/mystery-man.png @endif"/>

                            </div>
                            <form id="data" method="post" enctype="multipart/form-data">
                                <div><input type="file" name="uploads" accept="image/*" style="display: none"
                                            id="selectFileDialog"></div>
                                <div><input type="text" style="margin-top: 5px" hidden name="user_id"
                                            value="{{$user['user_id']}}"></div>
                                <div>
                                    <button style="display: none" class="btn btn-primary small" id="uploadnow">{{$lang['upload']}}
                                    </button>
                                </div>
                                <div class="ajax-respond"></div>
                            </form>
                        </div>
                        <div class="main-info" style="margin-top: 25px">
                            <div class="info-head">{{$lang['statistic']}}</div>
                            <div style="padding-top:10px">
                                <div>
                                    {{$lang['rate'] . ':'}}
                                    @for  ($i = 1; $i < 6; $i++)
                                        @if ($i<= $salon['rating'])
                                            <i class="fa fa-star fa-lg" style="margin-right:2px;"
                                               aria-hidden="true"></i>
                                        @else
                                            <span class="fa fa-star-o fa-lg" style="margin-right:2px;margin-top:3px;"
                                                  aria-hidden="true"></span>
                                        @endif
                                    @endfor
                                </div>
                                <div>
                                    <i class="fa fa-scissors fa-lg " style="margin:7px 0"></i>
                                    {{$lang['staff'].':'}}<b> {{$salon['staff_number']}}</b>
                                </div>
                                <div>
                                    <i class="fa fa-comments" aria-hidden="true"></i>
                                    {{$lang['comments'].':'}} <b>{{$salon['comments_number']}}</b>
                                </div>
                            </div>
                        </div>

                    </div>
                    <form class="form-horizontal" name="form" id="edit-form" method="post"
                          action={{"/admin/salon/".$salon['salon_id']}}>
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
                                    <label class="control-label" for="business_name">{{$lang['business_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="business_name" name="business_name"
                                               value="{{$salon['business_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="first_name">{{$lang['first_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="first_name" name="first_name"
                                               value="{{$salon['first_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="last_name">{{$lang['last_name']}}</label>
                                    <div class="controls">
                                        <input type="text" id="last_name" name="last_name"
                                               value="{{$salon['last_name']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="phone">{{$lang['phone']}}</label>
                                    <div class="controls">
                                        <input type="text" id="phone" name="phone"
                                               value="{{$salon['phone']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="founded_in">{{$lang['founded']}}</label>
                                    <div class="controls">
                                        <input type="text" id="founded_in" name="founded_in"
                                               value="{{$salon['founded_in']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sl-4">
                            <div class="main-info">
                                <div class="info-head">{{$lang['location']}}</div>
                                <div class="control-group">
                                    <label class="control-label" for="city">{{$lang['city']}}</label>
                                    <div class="controls">
                                        <input type="text" id="city" name="city"
                                               value="{{$salon['city']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="address">{{$lang['address']}}</label>
                                    <div class="controls">
                                        <input type="text" id="address" name="address"
                                               value="{{$salon['address']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="address">{{$lang['house']}}</label>
                                    <div class="controls">
                                        <input style="width:50px" type="text" id="address" name="house"
                                               value="{{$salon['house']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="lat">{{$lang['latitude']}}</label>
                                    <div class="controls">
                                        <input type="text" id="lat" name="lat"
                                               value="{{$salon['lat']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="lng">{{$lang['longitude']}}</label>
                                    <div class="controls">
                                        <input type="text" id="lng" name="lng"
                                               value="{{$salon['lng']}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="waze">WAZE URL</label>
                                    <div class="controls">
                                        <input type="text" id="waze" name="waze"
                                               value="{{$salon['waze']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="buttons-group">
                                <div class="control-group">
                                    <button style="margin: 0 15px" class="btn btn-primary " type="submit" name="Save"
                                            id="save-btn">
                                        <span class="fa fa-check"></span>{{$lang['save']}}
                                    </button>
                                    <button class="btn btn-danger" type="reset" name="cancel">
                                        <span class="fa fa-times"></span>{{$lang['cancel']}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer">
                <div class="row">
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
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-info" name="worker" id="newWorkerBtn">
                            <span class="fa fa-lock"></span>{{$lang['new_worker']}}
                        </button>
                    </div>
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-info" name="Service" id="newServiceBtn">
                            <span class="fa fa-lock"></span>{{$lang['new_service']}}
                        </button>
                    </div>
                    <div class="col-ld-2 col-md-2 col-sm-2">
                        <button class="btn btn-primary small" name="status"
                                @if ($salon['status']=='Active') style='display: none' @endif id="activeBtn">{{$lang['activate']}}
                        </button>
                        <button class="btn btn-warning small" name="status"
                                @if ($salon['status']=='Inactive') style='display: none' @endif id="deactiveBtn">
                            {{$lang['deactivate']}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="workers">
        <div class="panel panel-primary">
            <div class="panel-heading services-panel"><b>{{$lang['worker_list']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    @foreach($workers as $worker)
                        @php($loop_max = $loop->iteration)
                    @if (gmp_mod($loop->iteration, 5)==0)
                </div>
                <div class="row">
                        @endif
                    <div @if ($loop->iteration >4)
                         class="col-lg-3 col-md-3 col-sm-3 hidden-worker"
                         @else
                         class="col-lg-3 col-md-3 col-sm-3"
                            @endif>
                        <div class="panel panel-primary panel-heading-select" id="worker_{{$worker['worker_id']}}">
                            <div class="panel-heading"><b>{{$worker['first_name']}} {{$worker['last_name']}}
                                    # {{$worker['worker_id']}}</b></div>
                            <div class="panel-body">
                                <div></div>
                                <div class="panel-img">
                                    <img src='{{$worker['logo']}}'>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div><b>{{$lang['spec']}}</b> {{$worker['specialization']}}</div>
                                <div><b>{{$lang['from']}}</b> {{$worker['start_year']}}</div>
                                <div><b>{{$lang['phone']}}</b> {{$worker['phone']}} </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="panel-footer">
                @if ($loop_max >4)
                    <button class="btn btn-primary" id="showWorkerBtn" data-value="Show more workers">{{$lang['show_more_workers']}}</button>
                @endif
            </div>
        </div>
    </div>
    <div id="services">
        <div class="panel panel-primary">
            <div class="panel-heading services-panel"><b>{{$lang['service_list']}}</b></div>
            <div class="panel-body">
                <div class="row">
                    @foreach($services as $service)
                        @php($loop_max = $loop->iteration)
                        <div @if ($loop->iteration >4)
                             class="col-lg-3 col-md-3 col-sm-3 hidden-service"
                             @else
                             class="col-lg-3 col-md-3 col-sm-3"
                                @endif>
                            <div class="panel panel-primary panel-heading-select"
                                 id="service_{{$service['service_id']}}">
                                <div class="panel-heading"><b>{{$service['name']}} # {{$service['service_id']}}</b>
                                </div>
                                <div class="panel-body">
                                    <div></div>
                                    <div class="panel-img">
                                        <img src='{{$service['logo']}}'>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div><b>{{$lang['duration'].':'}}</b> {{$service['duration']}} min</div>
                                    <div><b>{{$lang['price_min'].':'}}</b> {{$service['price_min']}} ₪
                                    </div>
                                    <div><b>{{$lang['price_max']}}</b> {{$service['price_max']}} ₪
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (gmp_mod($loop->iteration, 4)==0)
                </div>
                <div class="row">
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="panel-footer">
                @if ($loop_max > 4)
                    <button class="btn btn-primary" id="showServiceBtn" data-value="Show more services">{{$lang['show_more_service']}}</button>
                @endif
            </div>
        </div>
    </div>
    <div class="new-worker-form-wrapper">
        <!-- register new worker -->

        <div class="new-worker-form">
            <div class="form">
                <form method="post" id="newWorkerForm" class="n-form">
                    <div class="title">Make new Worker</div>
                    <div class="wrapper-field">
                        <label><p>e-mail </p>
                            <input type="text" required  name="email"
                                   id="newWorkerEmail"></label>
                        <input type="text" hidden value="{{$salon['salon_id']}}" name="salon_id">
                        <div class="btn-wrapper clearfix">
                            <button id="submit_btn" class="btn-primary">Save</button>
                            <button type="reset" id="cancelBtn" class="btn-cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="new-service-form-wrapper">
        <!-- register new worker -->

        <div class="new-service-form">
            <div class="form">
                <form method="post" id="newServiceForm" class="n-form">
                    <div class="title">Salon's Services</div>
                    <div class="wrapper-field">
                        <input type="text" hidden value="{{$salon['salon_id']}}" name="salon_id">
                        <label><p>Service’s name. </p>
                            <input type="text" required name="name"></label>
                        <label><p>service’s duration max, min </p>
                            <input type="text" required name="duration"></label>
                        <label><p>Service’s minimal price. </p>
                            <input type="text" required name="price_min"></label>
                        <label><p>Service’s maximal price. </p>
                            <input type="text" name="price_max"></label>
                        <div class="btn-wrapper clearfix">
                            <button type="sumbit" id='serviceSaveBtn' class="btn-primary">Save</button>
                            <button type="reset" id="serviceCancelBtn" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{--new worker --}}
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#submit_btn').click(function (e) {
                e.preventDefault();
                admin_id = '<?=$admin['entry_id']?>';
                token = '<?=$admin['token']?>';
                $.ajax({
                    method: 'POST',
                    type: 'POST',
                    url: "/api/auth/singup/worker/start",
                    data: $('form#newWorkerForm').serialize()+
                        '&user_id='+admin_id+'&token='+token,
                    success: function (result, textStatus) {
                        if (!result.error){
                            alert('OK. Worker created.');
                            document.location.href = 'https://hairtime.co.il/api/admin/salon/{{$salon['salon_id']}}';
                        }else{
                            alert('Worker doesn\'t created! Error.' + result.message)
                        }
                    },
                    error: function (jqXHR, exception) {
                        if (jqXHR.status === 0) {
                            alert('Worker doesn\'t created! Not connect.\n Verify Network.');
                        } else if (jqXHR.status == 404) {
                            alert('Worker doesn\'t created! Requested page not found. [404]');
                        } else if (jqXHR.status == 500) {
                            alert('Worker doesn\'t created! Internal Server Error [500].');
                        } else if (exception === 'parsererror') {
                            alert('Worker doesn\'t created! Requested JSON parse failed.');
                        } else if (exception === 'timeout') {
                            alert('Worker doesn\'t created! Time out error.');
                        } else if (exception === 'abort') {
                            alert('Worker doesn\'t created! Ajax request aborted.');
                        } else {
                            alert('Worker doesn\'t created! Uncaught Error.\n' + jqXHR.responseText);
                        }
                    }
                });
                $(".new-worker-form-wrapper").fadeOut(500);
            });
            $('#newWorkerBtn').click(function (e) {
                e.preventDefault();
                $(".new-worker-form-wrapper").fadeIn(500);
            });
            $('#cancelBtn').click(function () {
                $(".new-worker-form-wrapper").fadeOut(500);
            });
            $('#serviceSaveBtn').click(function (e) {
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    type: 'POST',
                    url: "/api/service/salon/{{$salon['salon_id']}}",
                    data: $('form#newServiceForm').serialize()+
                        '&user_id={{$admin['entry_id']}}'+
                        '&token{{$admin['token']}}',
                    success: function (result) {
                        if (!result.error){
                            alert('OK. Service created.');
                            document.location.href = 'https://hairtime.co.il/api/admin/salon/{{$salon['salon_id']}}';
                        }else{
                            alert('Service doesn\'t created. Error: ' + result.message)
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
                $(".new-service-form-wrapper").fadeOut(500);
            });
            $('#newServiceBtn').click(function (e) {
                e.preventDefault();
                $(".new-service-form-wrapper").fadeIn(500);
            });
            $('#serviceCancelBtn').click(function () {
                $(".new-service-form-wrapper").fadeOut(500);
            });
        })
    </script>

    <!-- button activ/inactiv click -->
    <script type="text/javascript">
        $('form#edit-form').submit(function (e) {
            e.preventDefault();
            const formId = $(this).attr('id'),
                formNm = $('#' + formId);
            $.ajax({
                type: "POST",
                url: "/api/admin/salon/" + '<?= $salon['salon_id']?>',
                data: formNm.serialize(),
                headers: {
                    'User-ID': "<?= $admin['entry_id']?>",
                    'Token': "<?= $admin['token']?>",
                },
                success: function (result) {
                    if (result.status == 'success') {
                        $('.result-message-popup').find('#messageDiv').replaceWith('Data successfully saved!');
                        $('.result-message-popup').show();
                    } else {
                        $('.result-message-popup').find('#messageDiv').replaceWith(result.message);
                        $('.result-message-popup').show();
                    }
                }
            });

        });

        $('.panel-heading-select').click(function (e) {
            e.preventDefault();
            console.log('select', e.currentTarget.id);
            document.location.href = 'https://hairtime.co.il/api/admin/'+ e.currentTarget.id.split('_')[0]+'/' + e.currentTarget.id.split('_')[1];
        });
        $('#showWorkerBtn').click(function (e) {
            e.preventDefault();
            if ($('#showWorkerBtn').data('value') == 'Show more workers') {
                $('#showWorkerBtn').data('value', 'Hide workers');
                $('#showWorkerBtn').text('{{$lang['hide_workers']}}');
                changeClass = $(".hidden-worker");
                changeClass.removeClass('hidden-worker');
                changeClass.addClass('active-worker');
            } else if ($('#showWorkerBtn').data('value') == 'Hide workers') {
                $('#showWorkerBtn').data('value', 'Show more workers');
                $('#showWorkerBtn').text('{{$lang['show_more_workers']}}');
                changeClass = $(".active-worker");
                changeClass.removeClass('active-worker');
                changeClass.addClass('hidden-worker');
            }
        });
        $('#showServiceBtn').click(function (e) {
            e.preventDefault();
            console.log('showServiceBtn click');

            if ($('#showServiceBtn').data('value') == 'Show more services') {
                $('#showServiceBtn').data('value','Hide services');
                $('#showServiceBtn').text('{{$lang['hide_services']}}');
                changeClass = $(".hidden-service");
                changeClass.removeClass('hidden-service');
                changeClass.addClass('active-service');
            } else if ($('#showServiceBtn').data('value') == 'Hide services') {
                $('#showServiceBtn').data('value', 'Show more services');
                $('#showServiceBtn').text('{{$lang['show_more_service']}}');
                changeClass = $(".active-service");
                changeClass.removeClass('active-service');
                changeClass.addClass('hidden-service');
            }
        });
        $("#deactiveBtn").click(function () {
            salon_id = window.location.pathname.split('/')[4];
            admin_id = '<?=$admin['entry_id']?>';
            token = '<?=$admin['token']?>';
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/api/admin/salon/" + salon_id,
                data: {
                    'status': 'Inactive',
                    'user_id': admin_id,
                    'token': token,
                },
                success: function (result, textStatus) {
                    document.getElementById('activeBtn').style.display = "";
                    document.getElementById('deactiveBtn').style.display = "none";
                },
            })
        })
        $("#activeBtn").click(function () {
            salon_id = window.location.pathname.split('/')[4];
            admin_id = '<?=$admin['entry_id']?>';
            token = '<?=$admin['token']?>';
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/api/admin/salon/" + salon_id,
                data: {
                    'status': 'Active',
                    'user_id': admin_id,
                    'token': token,
                },
                success: function (result, textStatus) {
                    document.getElementById('activeBtn').style.display = "none";
                    document.getElementById('deactiveBtn').style.display = "";
                },
            })
        })
    </script>


@stop