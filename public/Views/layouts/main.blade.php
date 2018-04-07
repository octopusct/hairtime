<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HairTime | Admin Panel</title>
    <link href="/plugins/bootstrap/bootstrap.css" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <!--
    <link href="/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />-->
    <link href="/css/style.css" rel="stylesheet"/>
    <link href="/css/main-style.css" rel="stylesheet"/>
    <link href="/css/form.css" rel="stylesheet"/>
    <link href="/css/javelin.css" rel="stylesheet"/>
    <!-- CSS React table -->
    <link rel="stylesheet" href="https://unpkg.com/react-table@latest/react-table.css">

    <script src="https://unpkg.com/react@15.3.1/dist/react.min.js"></script>
    <script src="https://unpkg.com/react-dom@15.3.1/dist/react-dom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.38/browser.min.js"></script>
    <!-- Page-Level CSS -->
    <style>
        th {
            cursor: pointer;
        }

        th:hover {
            background: dimgray;
        }


    </style>

</head>

<body>
<!--  wrapper -->
<div id="wrapper">
    <!-- navbar top -->
@include('layouts.head')
<!-- end navbar top -->

    <!-- navbar side -->
@include('layouts.side')
<!-- end navbar side -->
    <!--  page-wrapper -->
    <div id="page-wrapper">

        <div class="row">
            <!-- Page Header -->
            <div class="col-lg-12" id="main-content">
                @yield('content')
            </div>
            <!--End Page Header -->
        </div>
    </div>
    <!-- end page-wrapper -->
</div>
<!-- end wrapper -->

<!--message popup-->
<div class="popup-dialog">
    <div class="popup">
    </div>
</div>
<div class="dialog-templates" style="display: none">
    <div class='container popup-delete-dialog'>
        <i class='fa fa fa-exclamation-triangle fa-3x' style="margin:10px auto;width:auto ">Warning</i><br>
        <h4 style="margin:0 auto">Are you shure to delete this user?</h4>
        <button class='btn btn-primary' id='yes' style='margin:10px auto;width: 100px'>Yes</button>
        <button class='btn btn-default' id='close' style='margin:0 auto;width: 100px'>No</button>
    </div>
</div>

<!--message popup end-->
<!--result-message popup-->
<div class="result-message-popup">
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4">
            <i class="fa fa-info" aria-hidden="true" id="messageIcon"></i>
        </div>
        <div class="col-sm-8 col-md-8 col-lg-8" id='messageDiv'>

        </div>
    </div>
</div>
<!--end result-message popup-->
<!-- Core Scripts - Include with every page -->
<!-- <script src="/plugins/jquery-1.10.2.js"></script> -->
<script src="/plugins/bootstrap/bootstrap.min.js"></script>
<script src="/plugins/metisMenu/jquery.metisMenu.js"></script>
<!-- Page-Level Plugin Scripts-->
<script src="/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/plugins/dataTables/dataTables.bootstrap.js"></script>


<!-- JS React Table -->
<script src="https://unpkg.com/react-table@latest/react-table.js"></script>

@if (isset($result) && $result )
    <!-- start info dialog -->
    <div id="dialog-message" hidden title="Success">
        <p>
            <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
            Successful complete !
        </p>
    </div>
    <!-- end info dialog -->
    <script type="text/javascript">
        $(function () {
            $("#dialog-message").dialog({
                modal: true,
                buttons: {
                    Ok: function () {
                        $(this).dialog("close");
                    }
                }
            });
        });
    </script>
@elseif(isset($result) && !$result)
    <!-- start poor dialog -->
    <div id="dialog-message-poor" hidden title="Poor">
        <p>
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
            Something wrong !
        </p>
    </div>
    <!-- end poor dialog -->
    <script type="text/javascript">
        $(function () {
            $("#dialog-message-poor").dialog({
                modal: true,
                buttons: {
                    Ok: function () {
                        $(this).dialog("close");
                    }
                }
            });
        });
    </script>
@endif
</body>

<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script>

<script type="text/javascript">
    $('#messageBtn').click(function (e) {
        e.preventDefault();
        popup = $('.popup');
        if ($(".wrapper").length == 0) {
            popup.wrapInner("<div class='wrapper'>" +
                "    <div class='wrapper'>\n" +
                "        <div class='row'>\n" +
                "            <div class='col-sm-3 col-md-3 col-lg-3' style='color: #428bca'>\n" +
                "                <i class='fa fa-info-circle fa-3x' style='color: #428bca;'></i><br>Message to user\n" +
                "            </div>\n" +
                "            <div class='col-sm-9 col-md-9 col-lg-9'>\n" +
                "                <h5 style='align-content:left;color:#428bca;'>write message here:</h5>\n" +
                "                <div style='margin-bottom: 10px'><textarea  autofocus rows='5' cols='50' required tabindex='1' style=' resize: none;'></textarea></div>\n" +
                "                <button class='btn btn-primary' tabindex='2' id='send' style='min-width:125px;max-width:150px;'><span class='fa fa-envelope-o'></span>Send Message</button>\n" +
                "                <button class='btn btn-danger' tabindex='3' id='close' style='width: 100px'><span class='fa fa-times'></span>Cancel</button>\n" +
                "            </div>\n" +
                "        </div>\n" +
                "    </div>" +
                "</div>"
            );
            //popup.find('.wrapper').style('border-color:#428bca!important;');
        }
        popup.fadeIn(300);
        popup.find("#send").click(function () {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                type: 'POST',
                url: "/admin/message/",
                data: {
                    'email': '{{$user['email']}}',
                },
                headers: {
                    'User-ID': '{{$user['user_id']}}',
                    'Token': '{{$admin['token']}}',
                },
                success: function (ajax_result, textStatus) {
                    popup.find('.col-sm-9').remove();
                    popup.wrapInner(" <div class='col-sm-9 col-md-9 col-lg-9'>\n" +
                        "                <h5 style='align-content:left;color:#428bca;'>message successfully sent!</h5>\n" +
                        "                <button class='btn btn-primary' tabindex='3' id='close' style='width: 100px'><span class='fa fa-thumbs-o-up'></span>OK</button>\n" +
                        "            </div>"
                    );
                    popup.find("#close").click(function () {
                        e.preventDefault();
                        popup.fadeOut(300);
                    });
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('Message doesn\'t sent! Not connect.\n Verify Network.');
                    } else if (jqXHR.status == 404) {
                        alert('Message doesn\'t sent! Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                        alert('Message doesn\'t sent! Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Message doesn\'t sent! Requested JSON parse failed.');
                    } else if (exception === 'timeout') {
                        alert('Message doesn\'t sent! Time out error.');
                    } else if (exception === 'abort') {
                        alert('Message doesn\'t sent! Ajax request aborted.');
                    } else {
                        alert('Message doesn\'t sent! Uncaught Error.\n' + jqXHR.responseText);
                    }
                    popup.hide();

                }
            })
        });
        popup.find("#close").click(function () {
            e.preventDefault();
            popup.fadeOut(300);
        });
    })
</script>

<!-- javascript to filling in messages list -->
<script type="text/javascript">
    window.onload = function () {
        var result = '<?=$result?>',
            admin_id = '<?=$admin['entry_id']?>',
            token = '<?=$admin['token']?>';
        $.ajax({
            url: "/admin/message/new",
            method: 'GET',
            dataType: 'json',
            headers: {
                'admin_id': admin_id,
                'Token': token,
            },
            success: function (results) {
                $("#mesCount").html("<b>" + results.length + "</b>");
                $.each(results, function (index, results) {
                    $("#messagesList").append(" <li>\n" +
                        "                        <a href=\"/admin/message/" + results.message_id + "?operator=Answer\">\n" +
                        "                            <div>\n" +
                        "                                <strong><span class=\" label label-danger\">" + results.name + "</span></strong>\n" +
                        "                                <span class=\"pull-right text-muted\">\n" +
                        "                                        <em>" + results.created_at + "</em>\n" +
                        "                                    </span>\n" +
                        "                            </div>\n" +
                        "                            <div>" + results.message.substring(0, 75) + "...</div>\n" +
                        "                        </a>\n" +
                        "                    </li>\n" +
                        "                    <li class=\"divider\"></li>");
                });
                $("#messagesList").append(" <li>\n" +
                    "                    <a class=\"text-center\" href=\"/admin/message\">\n" +
                    "                        <strong>Read All Messages</strong>\n" +
                    "                        <i class=\"fa fa-angle-right\"></i>\n" +
                    "                    </a>\n" +
                    "                </li>");
            },
            error: function (jqXHR, exception) {
                if (jqXHR.status === 0) {
                    $("#mesCount").html("<b>Not connect</b>");
                } else if (jqXHR.status == 401) {
                    $("#mesCount").html("<b>Unauthorized</b>");
                } else if (jqXHR.status == 403) {
                    $("#mesCount").html("<b>Forbidden</b>");
                } else if (jqXHR.status == 404) {
                    $("#mesCount").html("<b>Page not found</b>");
                } else if (jqXHR.status == 500) {
                    $("#mesCount").html("<b>Server Error</b>");
                } else if (exception === 'timeout') {
                    $("#mesCount").html("<b>Time out error</b>");
                } else if (exception === 'abort') {
                    $("#mesCount").html("<b>Aborted</b>");
                } else {
                    $("#mesCount").html("<b>Uncaught Error</b>");
                }
                $("#messagesList").append(" <li>\n" +
                    "                    <a class=\"text-center\" href=\"/admin/message\">\n" +
                    "                        <strong>Read All Messages</strong>\n" +
                    "                        <i class=\"fa fa-angle-right\"></i>\n" +
                    "                    </a>\n" +
                    "                </li>");
            }

        });
    };
</script>
<!-- end javascript to filling in messages list -->


</html>



