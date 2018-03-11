@extends('layouts.main')

@section('content')
    <h3 style="margin-top: 45px">Salon's list</h3><br>
    <div class="container-fluid">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px" id='addNewSalonBtn' class="btn btn-primary" name="new_salon">Add new Salon
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">Salon ID</th>
                            <th style="text-align: center">First Name</th>
                            <th style="text-align: center">Last Name</th>
                            <th style="text-align: center">Business Name</th>
                            <th style="text-align: center">Phone</th>
                            <th style="text-align: center;">Action</th>
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
                </div>

            </div>
            <div class="new-form-wrapper">
                <!-- register new worker -->
                <div class="new-salon-form">
                    <div class="form">
                        <form id='newSalonForm' class="n-form">
                            <div class="title">New Salon</div>
                            <div class="wrapper-field">
                                <div class="row">
                                    <div class="col-lx-6 col-md-6 col-sx-6">
                                        <label><p>Salon’s city. </p><input type="text" required="" tabindex="7"
                                                                           placeholder="city - Salon's city. String[255]"
                                                                           name="city"></label>
                                        <label><p>Salon’s address. </p><input type="text" required="" tabindex="8"
                                                                              placeholder="address - Salon's address. String[255]"
                                                                              name="address"></label>
                                        <label><p>Salon’s house number. ]</p><input type="text" required="" tabindex="9"
                                                                                    placeholder="house - Salon's house number. String[10]"
                                                                                    name="house"></label>
                                        <label><p>Salon’s coordinata LAT </p><input type="text" tabindex="10"
                                                                                    placeholder="lat - Salon's coordinata LAT 'XX.XXXXXXXX'. String[11]"
                                                                                    name="lat"></label>
                                        <label><p>Salon’s coordinata LNG </p><input type="text" tabindex="11"
                                                                                    placeholder="lng - Salon's coordinata LNG '(-)XX.XXXXXXXX'. String[12]"
                                                                                    name="lng"></label>
                                        <label><p>Salon’s phone. </p><input type="text" required="" tabindex="12"
                                                                            placeholder="phone - Salon's phone. String[20]"
                                                                            name="phone"></label>
                                    </div>
                                    <div class="col-lx-6 col-md-6 col-sx-6">
                                        <label><p>e-mail use as username </p><input type="text" required="" tabindex="1"
                                                                                    autofocus
                                                                                    placeholder="email - e-mail use as username to login. String[250]"
                                                                                    name="email"></label>
                                        <label><p>Salon’s password. </p><input type="text" required="" tabindex="2"
                                                                               placeholder="password - Salon's password. String[50]"
                                                                               name="password"></label>
                                        <label><p>User’s first name. </p><input type="text" required="" tabindex="3"
                                                                                placeholder="first_name - User's first name. String[100]"
                                                                                name="first_name"></label>
                                        <label><p>User’s last name. </p><input type="text" required="" tabindex="4"
                                                                               placeholder="last_name - User's last name. String[100]"
                                                                               name="last_name"></label>
                                        <label><p>Salon’s bussiness name. </p><input type="text" required=""
                                                                                     tabindex="5"
                                                                                     placeholder="business_name - Salon's bussiness name. String[100]"
                                                                                     name="business_name"></label>
                                        <label><p>Founded date. </p><input type="text" required="" tabindex="6"
                                                                           placeholder="founded_in - Founded date. String[4]"
                                                                           name="founded_in"></label>
                                    </div>

                                </div>
                                <div class="btn-wrapper clearfix">
                                    <button id='saveSalonBtn' class="btn-primary" tabindex="13">Save</button>
                                    <button type="reset" id="cancelSalonBtn" class="btn-cancel" tabindex="14">Cancel
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
                    url: "/auth/singup/salon",
                    data: $('form#newSalonForm').serialize(),
                    headers: {
                        'User-ID': '{{$user['user_id']}}',
                        'Token': '{{$admin['token']}}',
                    },
                    success: function (result) {
                        alert('Salon successfully created');
                        document.location.href = '/admin/salon/' + result.salon_id;
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
                let id = $(this).attr("id").split('_'),
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
                        url: "/ajax/salon/status/" + salon_id,
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
                        url: "/ajax/salon/status/" + salon_id,
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
                    document.location.href = '/admin/salon/' + salon_id;
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
                            url: "/ajax/salon/delete/" + salon_id,
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