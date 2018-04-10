@extends('layouts.main')

@section('content')

    <h3 class="page-header">Worker's list</h3>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px"
                        class="btn btn-primary" id="newWorkerBtn" name="new_salon">Add new Worker
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">Worker ID</th>
                            <th style="text-align: center">First Name</th>
                            <th style="text-align: center">Last Name</th>
                            <th style="text-align: center">Salon ID</th>
                            <th style="text-align: center">Phone</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($workers as $worker)
                            <tr >
                                <td style="text-align: center">{{$worker['worker_id']}}</td>
                                <td>{{$worker['first_name']}}</td>
                                <td>{{$worker['last_name']}}</td>
                                <td><a href="/admin/salon/{{$worker['salon_id']}}">{{$worker['salon_id']}}</a> </td>
                                <td>{{$worker['phone']}}</td>
                                <td style="align-content: center;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="#" id="edit_{{$worker['worker_id']}}">
                                                <i class="fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-6" >
                                            <a class="icon-btn" href="#" id="delete_{{$worker['worker_id']}}">
                                                <i class="fa fa fa-trash-o fa-2x" ></i>
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
        <!--delete confirm popup start-->
        <div class="popup">
            <i class="fa fa fa-exclamation-triangle fa-3x">Warning</i><br>
            <h4>Are you shure to delete this user?</h4>
            <button class="btn btn-primary" id="yes" style="width: 100px">Yes</button>
            <button class="btn btn-default" id="close" style="width: 100px">No</button>

        </div>
        <!--delete confirm popup end-->
        <!-- add new worker form -->
    <div class="new-worker-form-wrapper" >
        <!-- register new worker -->

        <div class="new-worker-form">
            <div class="form">
            <form method="post" action="/auth/singup/worker/complete" class="n-form">
                <div class="title">Make new Worker</div>
                <div class="wrapper-field">
                    <label><p>e-mail use as username to login. String[250]</p><input type="text" required  name="email"></label>
                    <label><p>Worker’s password. String[250]</p><input type="text" required  name="password"></label>
                    <label><p>Worker’s first name. String[100]</p><input type="text" required  name="first_name"></label>
                    <label><p>Worket’s last name. String[100]</p><input type="text" required  name="last_name"></label>
                    <label><p>Worker’s spicialization. String[20]</p><input type="text" required  name="specialization"></label>
                    <label><p>Worker’s start working year in format YYYY String[4]</p><input type="text" required  name="start_year"></label>
                    <label><p>Salon’s phone. Format (555)555-5555 / +33(1)22 22 22 22 / +33(020)7777 7777 String[20]</p><input type="text" required  name="phone"></label>
                    <label><p>Worker’s avatar URL. String [250]</p><input type="text"  name="logo"></label>
                    <div class="btn-wrapper clearfix">
                        <button type="sumbit" id="submit_btn" class="btn-primary">Save</button>
                        <button type="reset" id="cancel_btn" class="btn-cancel">Cancel</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    </div>
        <!-- add new worker form -->

        <!-- Page-Level Plugin Scripts-->
        <script src="/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="/plugins/dataTables/dataTables.bootstrap.js"></script>
        <link href="/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

        <script type="text/javascript">
            $(".icon-btn").click(function (e) {
                e.preventDefault();
                var id = $(this).attr("id").split('_');
                worker_id = id[1];
                console.dir(id);
                icon_lock =  $(this).find('i');
                if (id[0] == "edit") {
                    icon_lock.removeClass('fa-info-circle fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    document.location.href = 'https://hairtime.co.il/admin/worker/'+ customer_id;

                } else if (id[0] == 'delete') {
                    admin_id = '<?=$admin['entry_id']?>';
                    token = '<?=$admin['token']?>';
                    console.log(worker_id);
                    if ($(".wrapper").length == 0){
                        $('.popup').wrapInner("<div class='wrapper'></div>");
                    }
                    $('.popup').show();
                    $('.popup').find("#yes").click(function() {
                        e.preventDefault();
                        $('.popup').hide();
                        icon_lock.removeClass('fa-trash-o fa-2x');
                        icon_lock.addClass('fa-refresh fa-spin fa-lg');
                        $.ajax({
                            method: 'POST',
                            type: 'POST',
                            url: "/del/worker/" + worker_id,
                            headers: {
                                'User-ID': admin_id,
                                'Token': token,
                            },
                            success: function (result, textStatus) {
                                document.location.href = 'https://hairtime.co.il/admin/worker';
                                history.pushState('', '', href);
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
                    $('.popup').find("#close").click(function() {
                        e.preventDefault();
                        $('.popup').hide();
                    });
                }
            })
        </script>


    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#newWorkerBtn').click(function (e) {
                e.preventDefault();
                const newWorker = $('.new-worker-form-wrapper').html();
                $(".new-popup").append(newWorker);
                $(".new-popup").fadeIn(500);
                
                $('.btn-cancel').click(function () {
                    $(".new-popup").css('display','none');
                })
            })
            
            window.addEventListener("keydown", function(e){
              if (e.keyCode == 27) {
                $(".new-popup").css('display','none');
              }
            }, true);


        })
    </script>

    <!-- Get worker list -->
@stop