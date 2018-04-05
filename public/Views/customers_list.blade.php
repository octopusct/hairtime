@extends('layouts.main')

@section('content')
    <h3 style="margin-top: 45px">Customer's list</h3><br>
    <div class="container-fluid">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px" id='addNewCustomerBtn' class="btn btn-primary" name="new_salon">Add new Customer
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
                            <tr id="tr_{{$customer['customer_id']}}">
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
                                        <div class="col-sm-6">
                                            @if ($customer['deleted_at']== null)
                                                <a class="icon-btn" href="#" id="delete_{{$customer['customer_id']}}">
                                                    <i class="fa fa fa-trash-o fa-2x"></i>
                                                </a>
                                            @else
                                                <a class="icon-btn" href="#" id="undo_{{$customer['customer_id']}}">
                                                    <i class="fa fa fa-undo fa-lg"></i>
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
        {{-- New Customer dialod--}}
        <div class="new-form-wrapper">
            <!-- register new worker -->
            <div class="new-salon-form">
                <div class="form">
                    <form id='newCustomerForm' class="n-form">
                        <div class="title">New Customer</div>
                        <div class="wrapper-field">
                            <label><p>e-mail use as username </p><input type="text" required="" tabindex="1"
                                                                        autofocus
                                                                        placeholder="email - e-mail use as username to login. String[250]"
                                                                        name="email"></label>
                            <label><p>Customer’s password. </p><input type="text" required="" tabindex="2"
                                                                   placeholder="password - Salon's password. String[50]"
                                                                   name="password"></label>
                            <label><p>User’s first name. </p><input type="text" required="" tabindex="3"
                                                                    placeholder="first_name - User's first name. String[100]"
                                                                    name="first_name"></label>
                            <label><p>User’s last name. </p><input type="text" required="" tabindex="4"
                                                                   placeholder="last_name - User's last name. String[100]"
                                                                   name="last_name"></label>
                            <label><p>Customer’s phone. </p><input type="text" required="" tabindex="5"
                                                                placeholder="phone - Customer's phone. String[20]"
                                                                name="phone"></label>
                            <div class="btn-wrapper clearfix">
                                <button id='saveCustomerBtn' class="btn-primary" tabindex="6">Save</button>
                                <button type="reset" id="cancelCustomerBtn" class="btn-cancel" tabindex="7">Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--end new Customer--}}

        <!-- Page-Level Plugin Scripts-->
        <script src="/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="/plugins/dataTables/dataTables.bootstrap.js"></script>
        <link href="/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"/>

        <!-- click on icons -->

        <!-- button activ/inactiv click -->
        <script type="text/javascript">
            $(document).ready(function () {
                $(".icon-btn").click(function (e) {
                    e.preventDefault();
                    var id = $(this).attr("id").split('_'),
                        customer_id = id[1],
                        admin_id = '{{$admin['entry_id']}}',
                        token = '{{$admin['token']}}',
                        icon_lock = $(this).find('i');
                    if (id[0] == "edit") {
                        icon_lock.removeClass('fa-info-circle fa-2x');
                        icon_lock.addClass('fa-refresh fa-spin fa-lg');
                        document.location.href = 'https://hairtime.co.il/api/admin/customer/' + customer_id;
                    } else if (id[0] == 'delete') {
                        $('.popup').html($('.popup-delete-dialog').html());
                        $('.popup-dialog').fadeIn(500);
                        $('.popup').find("#yes").click(function () {
                            e.preventDefault();
                            icon_lock.removeClass('fa-trash-o fa-2x');
                            icon_lock.addClass('fa-refresh fa-spin fa-lg');
                            $.ajax({
                                method: 'POST',
                                type: 'POST',
                                url: "/api/ajax/customer/delete/" + customer_id,
                                headers: {
                                  'User-ID': admin_id,
                                  'Token': token,
                                },
                                success: function (result, textStatus) {
                                    if (result.error){
                                        alert('Customer doesn\'t deleted!'+result.message)
                                        $('.popup-dialog').fadeOut(500);
                                    }else{
                                        alert('Customer deleted');
                                        $("#tr_" + customer_id).remove();
                                        $('.popup-dialog').fadeOut(500);
                                    }
                                },
                                error: function (jqXHR, exception) {
                                    if (jqXHR.status === 0) {
                                        alert('Customer doesn\'t deleted! Not connect.\n Verify Network.');
                                    } else if (jqXHR.status == 401) {
                                        alert('Customer doesn\'t deleted! Unauthorized request. [401]');
                                    } else if (jqXHR.status == 404) {
                                        alert('Customer doesn\'t deleted! Requested page not found. [404]');
                                    } else if (jqXHR.status == 500) {
                                        alert('Customer doesn\'t deleted! Internal Server Error [500].');
                                    } else if (exception === 'parsererror') {
                                        alert('Customer doesn\'t deleted! Requested JSON parse failed.');
                                    } else if (exception === 'timeout') {
                                        alert('Customer doesn\'t deleted! Time out error.');
                                    } else if (exception === 'abort') {
                                        alert('Customer doesn\'t deleted! Ajax request aborted.');
                                    } else {
                                        alert('Customer doesn\'t deleted! Uncaught Error.\n' + jqXHR.responseText);
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
                });
                $('#addNewCustomerBtn').click(function (e) {
                  e.preventDefault()
                  $('.new-form-wrapper').fadeIn(300);
                });
                $('#saveCustomerBtn').click(function (e) {
                e.preventDefault();
                $.ajax({
                  method: 'POST',
                  type: 'POST',
                  url: "/api/auth/singup/customer",
                  data: $('form#newCustomerForm').serialize(),
                  success: function (result) {
                    alert('Customer successfully created');
                    document.location.href = '/api/admin/customer/' + result.customer_id;
                  },
                  error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                      alert('Customer doesn\'t created! Not connect.\n Verify Network.');
                    } else if (jqXHR.status == 404) {
                      alert('Customer doesn\'t created! Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                      alert('Customer doesn\'t created! Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                      alert('Customer doesn\'t created! Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                      alert('Customer doesn\'t created! Time out error.');
                    } else if (exception === 'abort') {
                      alert('Customer doesn\'t created! Ajax request aborted.');
                    } else {
                      alert('Customer doesn\'t created! Uncaught Error.\n' + jqXHR.responseText);
                    }
                  }
                })
                });
                $('#cancelCustomerBtn').click(function (e) {
                  e.preventDefault();
                  $('.new-form-wrapper').fadeOut(300);
                });
            })
        </script>
    </div>

@stop