@extends('layouts.main')

@section('content')

    <h3 class="page-header">{{$lang['worker_list']}}</h3>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px"
                        class="btn btn-primary" id="newWorkerBtn" name="new_salon">{{$lang['add_new_worker']}}
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">{{$lang['worker_id']}}</th>
                            <th style="text-align: center">{{$lang['first_name']}}</th>
                            <th style="text-align: center">{{$lang['last_name']}}</th>
                            <th style="text-align: center">{{$lang['salon_id']}}</th>
                            <th style="text-align: center">{{$lang['phone']}}</th>
                            <th style="text-align: center;">{{$lang['action']}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($workers as $worker)
                            <tr id="tr_{{$worker['worker_id']}}">
                                <td style="text-align: center">{{$worker['worker_id']}}</td>
                                <td>{{$worker['first_name']}}</td>
                                <td>{{$worker['last_name']}}</td>
                                <td><a href="/admin/salon/{{$worker['salon_id']}}">{{$worker['salon_id']}}</a></td>
                                <td>{{$worker['phone']}}</td>
                                <td style="align-content: center;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="" id="edit_{{$worker['worker_id']}}">
                                                <i class="fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="#" id="delete_{{$worker['worker_id']}}">
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
        </div>
        <!-- add new worker form -->
        <div class="new-form-wrapper">
            <!-- register new worker -->

            <div class="new-worker-form">
                <div class="form">
                    <form method="post" id="newWorkerForm" class="n-form">
                        <div class="title">{{$lang['new_worker']}}</div>
                        <div class="wrapper-field">
                            <label><p>{{$lang['email']}}</p>
                                <input type="text" required name="email"
                                       id="newWorkerEmail"></label>
                            <label><p>{{$lang['salon_id']}}</p>
                                <input type="text" required
                                       name="salon_id"></label>
                            <div class="btn-wrapper clearfix">
                                <button id="submit_btn" class="btn-primary">{{$lang['save']}}</button>
                                <button type="reset" id="cancel_btn" class="btn-cancel">{{$lang['cancel']}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- add new worker form -->

    <!-- Page-Level Plugin Scripts-->
    <script src="/api/public/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/api/public/plugins/dataTables/dataTables.bootstrap.js"></script>
    <link href="/api/public/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"/>

    <script type="text/javascript">
        $(".icon-btn").click(function (e) {
            e.preventDefault();
            var id = $(this).attr("id").split('_'),
                worker_id = id[1],
                icon_lock = $(this).find('i'),
                admin_id = '{{$admin['entry_id']}}',
                token = '{{$admin['token']}}';
            if (id[0] == "edit") {
                icon_lock.removeClass('fa-info-circle fa-2x');
                icon_lock.addClass('fa-refresh fa-spin fa-lg');
                document.location.href = 'admin/worker/' + worker_id;

            } else if (id[0] == 'delete') {
                $('.popup').html($('.popup-delete-dialog').html());
                $('.popup-dialog').fadeIn(500);
                $('.popup-dialog').find("#yes").click(function () {
                    icon_lock.removeClass('fa-trash-o fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    $.ajax({
                        method: 'POST',
                        type: 'POST',
                        url: "ajax/worker/delete/" + worker_id,
                        headers: {
                            'User-ID': admin_id,
                            'Token': token,
                        },
                        success: function (result, textStatus) {
                            if (!result.error){
                                alert('Worker delete');
                                $("#tr_" + worker_id).remove();
                                $('.popup-dialog').fadeOut(500);
                            }else{
                                alert('Worker doesn\'t deleted! Error: '+ result.message);
                                $('.popup-dialog').fadeOut(500);
                                $('#tr_'+worker_id).hide();
                            }
                        },
                        error: function (jqXHR, exception) {
                            if (jqXHR.status === 0) {
                                alert('Worker doesn\'t deleted! Not connect.\n Verify Network.');
                            } else if (jqXHR.status == 404) {
                                alert('Worker doesn\'t deleted! Requested page not found. [404]');
                            } else if (jqXHR.status == 500) {
                                alert('Worker doesn\'t deleted! Internal Server Error [500].');
                            } else if (exception === 'parsererror') {
                                alert('Worker doesn\'t deleted! Requested JSON parse failed.');
                            } else if (exception === 'timeout') {
                                alert('Worker doesn\'t deleted! Time out error.');
                            } else if (exception === 'abort') {
                                alert('Worker doesn\'t deleted! Ajax request aborted.');
                            } else {
                                alert('Worker doesn\'t deleted! Uncaught Error.\n' + jqXHR.responseText);
                            }
                            icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                            icon_lock.addClass('fa-trash-o fa-2x');

                        }
                    })
                });
                $('.popup').find("#close").click(function () {
                    e.preventDefault();
                    $('.popup-dialog').fadeOut(500);
                });
            }
        })
    </script>


    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#submit_btn').click(function (e) {
                e.preventDefault();
                var
                    admin_id = '{{$admin['entry_id']}}',
                    token = '{{$admin['token']}}';
                $.ajax({
                    method: 'POST',
                    type: 'POST',
                    url: "/api/auth/singup/worker/start",
                    data: $('form#newWorkerForm').serialize(),
                    success: function (result, textStatus) {
                        if (result.error){
                            alert('Worker doesn\'t created! Error: '+ result.message);
                        }else {
                            alert('OK. Worker created.');
                            document.location.href = '/api/admin/worker/' + result.worker_id;
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
                $(".new-form-wrapper").fadeOut(500);
            });
            $('#newWorkerBtn').click(function (e) {
                e.preventDefault();
                $(".new-form-wrapper").fadeIn(500);
            });
            $('#cancel_btn').click(function () {
                $(".new-form-wrapper").fadeOut(500);
            });
        })
    </script>
    <!-- Get worker list -->
@stop