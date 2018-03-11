@extends('layouts.main')

@section('content')
    <h3 style="margin-top: 45px">Customer's list</h3><br>
    <div class="container-fluid">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px" onclick="location.reload();location.href='/admin/salon/new'"
                        class="btn btn-primary" name="new_salon">Add new Customer
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">Customer ID</th>
                            <th style="text-align: center">First Name</th>
                            <th style="text-align: center">Last Name</th>
                            <th style="text-align: center">Phone</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($customers as $customer)
                            <tr id="tr_{{$customer['customer_id']}}" >
                                <td style="text-align: center">{{$customer['customer_id']}}</td>
                                <td>{{$customer['first_name']}}</td>
                                <td>{{$customer['last_name']}}</td>
                                <td>{{$customer['phone']}}</td>
                                <td style="align-content: center;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="#" id="edit_{{$customer['customer_id']}}">
                                                <i class="fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-6" >
                                            @if ($customer['deleted_at']== null)
                                            <a class="icon-btn" href="#" id="delete_{{$customer['customer_id']}}">
                                                <i class="fa fa fa-trash-o fa-2x" ></i>
                                            </a>
                                            @else
                                            <a class="icon-btn" href="#" id="undo_{{$customer['customer_id']}}">
                                                <i class="fa fa fa-undo fa-lg" ></i>
                                            </a>
                                            @endif
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
        <!--End Advanced Tables -->
        <!--delete confirm popup start-->
        <div class="popup">
            <i class="fa fa fa-exclamation-triangle fa-3x">Warning</i><br>
            <h4>Are you shure to delete this user?</h4>
            <button class="btn btn-primary" id="yes" style="width: 100px">Yes</button>
            <button class="btn btn-default" id="close" style="width: 100px">No</button>

        </div>
        <!--delete confirm popup end-->

        <!-- Page-Level Plugin Scripts-->
        <script src="/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="/plugins/dataTables/dataTables.bootstrap.js"></script>
        <link href="/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

        <!-- click on icons -->

        <!-- button activ/inactiv click -->
        <script type="text/javascript">
            $(".icon-btn").click(function (e) {
                e.preventDefault();
                var id = $(this).attr("id").split('_');
                customer_id = id[1];
                console.dir(id);
                icon_lock =  $(this).find('i');
                if (id[0] == "edit") {
                    icon_lock.removeClass('fa-info-circle fa-2x');
                    icon_lock.addClass('fa-refresh fa-spin fa-lg');
                    document.location.href = 'https://hairtime.co.il/admin/customer/'+ customer_id;

                } else if (id[0] == 'delete') {
                    admin_id = '<?=$admin['user_id']?>';
                    token = '<?=$admin['token']?>';
                    console.log(customer_id);
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
                            url: "/del/customer/" + customer_id,
                            headers: {
                                'admin_id': admin_id,
                                'Token': token,
                            },
                            success: function (ajax_result, textStatus) {
                                $("#tr_"+customer_id).remove();
                                //document.location.href = 'https://hairtime.co.il/admin/customer';
                                //history.pushState('', '', href);
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
    </div>

@stop