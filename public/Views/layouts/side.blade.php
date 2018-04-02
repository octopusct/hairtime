

<nav class="navbar-default navbar-static-side" role="navigation" style="position: fixed">
    <div class="mu_left_btn">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
    </div>
    <!-- sidebar-collapse -->
    <div class="sidebar-collapse">
        <!-- side-menu -->
        <ul class="nav" id="side-menu">
            <li>
                <!-- user image section-->
                <div class="user-section">
                    <div class="user-section-inner">
                        <img src="@if ($admin['logo'] != null){{$admin['logo']}}@else{{'/img/mystery-man.png'}} @endif"
                             class="img-circle" alt="admin logo">
                    </div>
                    <div class="user-info">
                        <div><h5>{{$admin['first_name'].' '.$admin['last_name']}}</h5></div>
                        <div class="user-text-online">
                            <span class="user-circle-online btn btn-success btn-circle "></span>{{$lang['online']}}
                        </div>
                    </div>
                </div>
                <!--end user image section-->
            </li>

            <li @if ($menu=='salons') class="selected" @endif>
                <a href="/api/admin"><i class="fa fa-list fa-fw"></i>{{$lang['salon_list']}}</a>
            </li>
            <li @if ($menu=='workers') class="selected" @endif>
                <a href="/api/admin/worker"><i class="fa fa-scissors fa-fw"></i>{{$lang['worker_list']}}</a>
            </li>
            <li @if ($menu=='customers') class="selected" @endif>
                <a href="/api/admin/customer"><i class="fa fa-shopping-cart"></i>{{$lang['customer_list']}}</a>
            </li>
            <li @if ($menu=='services') class="selected" @endif>
                <a href="/api/admin/service"><i class="fa fa-wrench"></i>{{$lang['service_list']}}</a>
            </li>
            {{--<li @if ($menu=='comments') class="selected" @endif>--}}
                {{--<a href="/admin/comment"><i class="fa fa-comment fa-fw"></i>Comments</a>--}}
            {{--</li>--}}
            <li @if ($menu=='messages') class="selected" @endif style="margin-bottom: 25px">
                <a href="/api/admin/message"><i class="fa fa-comment fa-fw"></i>{{$lang['service_list']}}</a>
            </li>
            {{--<li @if ($menu=='api') class="selected" @endif>--}}
                {{--<a href="/admin/api"><i class="fa fa-reply fa-fw"></i>API requests</a>--}}
            {{--</li>--}}

        </ul>
        <!-- end side-menu -->
        <div class="octopus-img"><a href="http://octopus.net.ua"><img src="/img/octopus.png"></a></div>

    </div>
    <!-- end sidebar-collapse -->
    <style>

    </style>
</nav>
<script>
    $('.mu_left_btn').on('click', function () {
        $('.mu_left_btn').toggleClass('active');
        $('.navbar-static-side').toggleClass('active');
    })
</script>
