<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
    <!-- navbar-header -->

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/api/admin">
            <img src="/img/image.jpg" alt=""/>
        </a>
    </div>
    <!-- end navbar-header -->
    <!-- navbar-top-links -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- main dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="top-label label label-danger" id="mesCount">

                </span><i class="fa fa-envelope fa-3x"></i>
            </a>
            <!-- dropdown-messages -->
            <ul class="dropdown-menu dropdown-messages" id="messagesList">


            </ul>
            <!-- end dropdown-messages -->
        </li>

        {{--<li class="dropdown">--}}
            {{--<a class="dropdown-toggle" data-toggle="dropdown" href="#">--}}
                {{--<span class="top-label label label-danger">5</span> <i class="fa fa-bell fa-3x"></i>--}}
            {{--</a>--}}
            {{--<!-- dropdown alerts-->--}}
            {{--<ul class="dropdown-menu dropdown-alerts">--}}
                {{--<li>--}}
                    {{--<a href="#">--}}
                        {{--<div>--}}
                            {{--<i class="fa fa-comment fa-fw"></i>New Comment--}}
                            {{--<span class="pull-right text-muted small">4 minutes ago</span>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="divider"></li>--}}
                {{--<li>--}}
                    {{--<a href="#">--}}
                        {{--<div>--}}
                            {{--<i class="fa fa-twitter fa-fw"></i>3 New Followers--}}
                            {{--<span class="pull-right text-muted small">12 minutes ago</span>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--</ul>--}}
            {{--<!-- end dropdown-alerts -->--}}
        {{--</li>--}}

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-3x"></i>
            </a>
            <!-- dropdown user-->
            <ul class="dropdown-menu dropdown-user">
                <li><a href="/api/admin/profile"><i class="fa fa-user fa-fw"></i>{{$lang['user_profile']}}</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i>{{$lang['settings']}}</a>
                </li>
                <li class="divider"></li>
                <li><a href="/api/admin/logout"><i class="fa fa-sign-out fa-fw"></i>{{$lang['logout']}}</a>
                </li>
            </ul>
            <!-- end dropdown-user -->
        </li>
        <!-- end main dropdown -->
    </ul>
    <!-- end navbar-top-links -->


</nav>