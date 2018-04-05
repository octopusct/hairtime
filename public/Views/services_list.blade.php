@extends('layouts.main')

@section('content')

    <h3 class="page-header">Services's list</h3>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button style="margin: 10px"
                        class="btn btn-primary" id="addNewServiceBtn" name="new_salon">Add new Service
                </button>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">Service ID</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Salon ID</th>
                            <th style="text-align: center">Duration</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($services as $service)
                            <tr id="tr_{{$service['service_id']}}">
                                <td style="text-align: center">{{$service['service_id']}}</td>
                                <td>{{$service['name']}}</td>
                                <td><a href="/admin/salon/{{$service['salon_id']}}">{{$service['salon_id']}}</a></td>
                                <td>{{$service['duration']}}</td>
                                <td style="align-content: center;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="#" id="edit_{{$service['service_id']}}">
                                                <i class="fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="icon-btn" href="#" id="delete_{{$service['service_id']}}">
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

            <div class="new-service-form">
                <div class="form">
                    <form class="n-form" method="post"  id="newServiceForm">
                        <div class="title">Salon's Services</div>
                        <div class="wrapper-field">
                            <label><p>Salon ID</p><input type="text" required tabindex="1" name="salon_id" id="salon_id"></label>
                            <label><p>Service’s name.</p><input type="text" required tabindex="2" name="name"></label>
                            <label><p>service’s duration min</p><input type="text" required tabindex="3" name="duration"></label>
                            <label><p>service’s duration max</p><input type="text" required tabindex="4"  name="duration"></label>
                            <label><p>Service’s minimal price.</p><input type="text" required tabindex="5"  name="price_min"></label>
                            <label><p>Service’s maximal price.</p><input type="text" required tabindex="6" name="price_max"></label>
                            <input type="text" required hidden value="{{$admin['entry_id']}}" name="User-ID"></label>
                            <input type="text" required hidden value="{{$admin['token']}}" name="Token"></label>
                            <div class="btn-wrapper clearfix">
                                <button id="saveServiceBtn" class="btn-primary"  tabindex="7">Save</button>
                                <button id="cancelServiceBtn" class="btn-cancel"  tabindex="8">Cancel</button>
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
        <link href="/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet"/>

<script type="text/javascript">
    $(".icon-btn").click(function (e) {
        e.preventDefault();varid = $(this).attr("id").split('_'),
            service_id = id[1],
            icon_lock = $(this).find('i'),
            admin_id = '{{$admin['entry_id']}}',
            token = '{{$admin['token']}}';
        if (id[0] == "edit") {
            icon_lock.removeClass('fa-info-circle fa-2x');
            icon_lock.addClass('fa-refresh fa-spin fa-lg');
            document.location.href = 'https://hairtime.co.il/api/admin/service/' + service_id;
        } else if (id[0] == 'delete') {
            $('.popup').html($('.popup-delete-dialog').html());
            $('.popup-dialog').fadeIn(500);
            $('.popup').find("#yes").click(function () {
            e.preventDefault();
            var id = $(this).attr("id").split('_'),
                icon_lock = $(this).find('i');
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/api/ajax/service/delete/" + service_id,
                headers: {
                    'User-ID': admin_id,
                    'Token': token,
                },
                success: function (result, textStatus) {
                  if (result.status==='success'){
                    alert(result.message);
                    console.log('service_id: ', $('#tr_{{$service['service_id']}}').text());
                    $('#tr_'+ id[1]).remove();
                    $('.popup-dialog').fadeOut(500);
                  }else{
                    alert('Error: ', result.message);
                    $('.popup-dialog').fadeOut(500);
                    icon_lock.removeClass('fa-refresh fa-spin fa-lg');
                    icon_lock.addClass('fa-info-circle fa-2x');
                  }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Service doesn\'t deleted! Not connect.\n Verify Network.');
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

    {{--/service/worker/{worker_id}/{service_id}--}}

    {{--new service form--}}
    <script type="text/javascript">
      $('#addNewServiceBtn').click(function (e) {
        e.preventDefault();
        $('.new-form-wrapper').fadeIn(300);

          $('#newServiceForm').on('submit', function(){
              e.preventDefault();
                console.log('submit');
          });
          $('#saveServiceBtn').click(function (e) {
            e.preventDefault();
            console.log('button');
            var salon_id = $('#salon_id').val();
            console.log('salon_id: ', salon_id);
            $.ajax({
              method: 'POST',
              type: 'POST',
              url: "/api/service/salon/"+salon_id,
              data: $('form#newServiceForm').serialize()+
                '&user_id={{$admin['entry_id']}}'+
                '&token={{$admin['token']}}',
              success: function (result) {
                    if (result.error){
                        alert("Service doesn't created! Error: " + result.message)
                    }else{
                        alert('Salon successfully created');
                        document.location.href = '/api/admin/salon/' + $('#salon_id')[0].value;
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
            })
          });
          $('#cancelServiceBtn').click(function (e) {
            e.preventDefault();
            $('.new-form-wrapper').fadeOut(300);
          });
      });
    </script>
@stop