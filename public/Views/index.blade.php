@extends('layouts.main')

@section('content')
    <h3 style="margin-top: 45px">{{$lang['salon_list']}}</h3><br>
    <div class="container-fluid">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px" id='addNewSalonBtn' class="btn btn-primary" name="new_salon">{{$lang['add_salon']}}
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">{{$lang['salon_id']}}</th>
                            <th style="text-align: center">{{$lang['first_name']}}</th>
                            <th style="text-align: center">{{$lang['last_name']}}</th>
                            <th style="text-align: center">{{$lang['business_name']}}</th>
                            <th style="text-align: center">{{$lang['phone']}}</th>
                            <th style="text-align: center;">{{$lang['action']}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($salons as $salon)
                            <tr id="tr_{{$salon['salon_id']}}">
                                <td style="text-align: center">{{$salon['salon_id']}}</td>
                                <td>{{$salon['first_name']}}</td>
                                <td>{{$salon['last_name']}}</td>
                                <td>{{$salon['business_name']}}</td>
                                <td>{{$salon['phone']}}</td>
                                <td style="align-content: center;">
                                    <div class="row">
                                        <div class="col-sm-4" id="activeBtn"
                                             @if ($salon['status']=='Active') style='display: none' @endif>
                                            <a class="icon-btn" id="activeBtn_{{$salon['salon_id']}}">
                                                <i class="fa fa fa-lock fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-4" id="deactiveBtn"
                                             @if ($salon['status']=='Inactive') style='display: none' @endif>
                                            <a class="icon-btn" id="deactiveBtn_{{$salon['salon_id']}}">
                                                <i class="fa fa fa-unlock fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <a class="icon-btn" id="edit_{{$salon['salon_id']}}">
                                                <i class="fa fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <a class="icon-btn" id="delete_{{$salon['salon_id']}}">
                                                <i class="fa fa fa-trash-o fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                {{--<pre style="border: #0d70b7 1px solid;">{{var_dump($lang)}}</pre>--}}
                </div>

            </div>
            <div class="new-form-wrapper">
                <!-- register new worker -->
                <div class="new-salon-form">
                    <div class="form">
                        <form id='newSalonForm' class="n-form">
                            <div class="title">{{$lang['new_salon']}}</div>
                            <div class="wrapper-field">
                                <div class="row">
                                    <div class="col-lx-6 col-md-6 col-sx-6">
                                        <label><p>{{$lang['city']}}</p>
                                            <input type="text" required tabindex="7" name="city"></label>
                                        <label><p>{{$lang['address']}}</p>
                                            <input type="text" required tabindex="8" name="address"></label>
                                        <label><p>{{$lang['house']}}</p>
                                            <input type="text" required tabindex="9" name="house"></label>
                                        <label><p>{{$lang['latitude']}}</p>
                                            <input type="text" tabindex="10" name="lat"></label>
                                        <label><p>{{$lang['longitude']}}</p>
                                            <input type="text" tabindex="11" name="lng"></label>
                                        <label><p>{{$lang['phone']}}</p>
                                            <input type="text" required tabindex="12" name="phone"></label>
                                    </div>
                                    <div class="col-lx-6 col-md-6 col-sx-6">
                                        <label><p>{{$lang['email']}} </p>
                                            <input type="text" required tabindex="1" autofocus name="email"></label>
                                        <label><p>{{$lang['password']}}</p>
                                            <input type="text" required tabindex="2" name="password"></label>
                                        <label><p>{{$lang['first_name']}}</p>
                                            <input type="text" required tabindex="3" name="first_name"></label>
                                        <label><p>{{$lang['last_name']}}</p>
                                            <input type="text" required tabindex="4" name="last_name"></label>
                                        <label><p>{{$lang['business_name']}} </p>
                                            <input type="text" required tabindex="5" name="business_name"></label>
                                        <label><p>{{$lang['founded']}}</p>
                                            <input type="text" required tabindex="6" name="founded_in"></label>
                                    </div>

                                </div>
                                <div class="btn-wrapper clearfix">
                                    <button id='saveSalonBtn' class="btn-primary" tabindex="13">{{$lang['save']}}</button>
                                    <button type="reset" id="cancelSalonBtn" class="btn-cancel" tabindex="14">{{$lang['cancel']}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!--End Advanced Tables -->
    </div>

        <!-- Page-Level Plugin Scripts-->
        <script src="/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="/plugins/dataTables/dataTables.bootstrap.js"></script>
        <link href="/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"/>

    {{--new salon form--}}
        <script type="text/javascript">
            $('#addNewSalonBtn').click(function (e) {
                e.preventDefault();
                $('.new-form-wrapper').fadeIn(300);
            });
            $('#saveSalonBtn').click(function (e) {
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    type: 'POST',
                    url: "/api/auth/singup/salon",
                    data: $('form#newSalonForm').serialize(),
                    success: function (result) {
                        if (result.error){
                            alert('Salon doesn\'t created! Error: '+ result.message);
                        }else{
                            alert('Salon successfully created');
                            document.location.href = '/api/admin/salon/' + result.salon_id;
                        }
                    },
                    error: function (jqXHR, exception) {
                        if (jqXHR.status === 0) {
                            alert('Salon doesn\'t created! Not connect.\n Verify Network.');
                        } else if (jqXHR.status == 404) {
                            alert('Salon doesn\'t created! Requested page not found. [404]');
                        } else if (jqXHR.status == 500) {
                            alert('Salon doesn\'t created! Internal Server Error [500].');
                        } else if (exception === 'parsererror') {
                            alert('Salon doesn\'t created! Requested JSON parse failed.');
                        } else if (exception === 'timeout') {
                            alert('Salon doesn\'t created! Time out error.');
                        } else if (exception === 'abort') {
                            alert('Salon doesn\'t created! Ajax request aborted.');
                        } else {
                            alert('Salon doesn\'t created! Uncaught Error.\n' + jqXHR.responseText);
                        }
                    }
                })
            });
            $('#cancelSalonBtn').click(function (e) {
                e.preventDefault();
                $('.new-form-wrapper').fadeOut(300);
            });
        </script>

    <!--icon-button activ/inactiv/edit/delete click -->
    <script type="text/javascript">
        $(document).ready(function () {
            $(".icon-btn").click(function (e) {
                e.preventDefault();
                var id = $(this).attr("id").split('_'),
                    icon_lock = $(this).find('i'),
                    salon_id = id[1],
                    admin_id = '{{$admin['entry_id']}}',
                    token = '{{$admin['token']}}';
                if (id[0] === "deactiveBtn") {
                    icon_lock.removeClass('fa-unlock fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    console.log(token);
                    $.ajax({
                        method: 'POST',
                        type: 'POST',
                        url: "/api/ajax/salon/status/" + salon_id,
                        data: {'status': 'Inactive'},
                        headers: {
                            'User-ID': admin_id,
                            'Token': token,
                        },
                        success: function (result, textStatus) {
                            icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                            icon_lock.addClass('fa-unlock fa-2x');
                            $('#deactiveBtn_' + salon_id).parent().hide();
                            $("#activeBtn_" + salon_id).parent().show();
                        },
                        error: function (result, textStatus) {
                            icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                            icon_lock.removeClass('fa-unlock fa-2x');

                        },
                    })
                } else if (id[0] === "activeBtn") {
                    icon_lock.removeClass('fa-lock fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    $.ajax({
                        method: 'POST',
                        type: 'POST',
                        url: "/api/ajax/salon/status/" + salon_id,
                        data: {'status': 'Active'},
                        headers: {
                            'User-ID': admin_id,
                            'Token': token,
                        },
                        success: function (result, textStatus) {
                            icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                            icon_lock.addClass('fa-lock fa-2x');
                            $('#activeBtn_' + salon_id).parent().hide();
                            $("#deactiveBtn_" + salon_id).parent().show();
                        },
                    })
                } else if (id[0] == "edit") {
                    icon_lock.removeClass('fa-info-circle fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    document.location.href = '/api/admin/salon/' + salon_id;
                } else if (id[0] == 'delete') {
                    $('.popup').html($('.popup-delete-dialog').html());
                    $('.popup-dialog').fadeIn(500);
                    $('.popup-dialog').find("#yes").click(function () {
                        e.preventDefault();
                        icon_lock.removeClass('fa-trash-o fa-2x');
                        icon_lock.addClass('fa-refresh fa-spin fa-lg');
                        $.ajax({
                            method: 'POST',
                            type: 'POST',
                            url: "/api/ajax/salon/delete/" + salon_id,
                            headers: {
                                'User-ID': admin_id,
                                'Token': token,
                            },
                            success: function (ajax_result, textStatus) {
                                console.log("result");
                                console.dir(ajax_result);
                                $("#tr_" + salon_id).remove();
                                $('.popup-dialog').fadeOut(500);
                            },
                        })

                    });
                    $('.popup-dialog').find("#close").click(function () {
                        e.preventDefault();
                        $('.popup-dialog').fadeOut(500);
                    });

                }
            });
        });
    </script>

@stop