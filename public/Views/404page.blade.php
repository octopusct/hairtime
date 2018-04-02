@extends('layouts.error')

@section('content')
    <div class="wrapper row" style="background-color: white;margin: 83px 0 0 0px;padding: 15% 30px;">
    <div id="container" class="clear">
        <div class="fl_left col-lg-6 col-md-6 col-sm-12">
            <img src="/img/404.png" alt="">
            <div id="respond">
            </div>
        </div>
        <div class="fl_right col-lg-6 col-md-6 col-sm-12">
            <h1>Sorry, Nothing Found</h1>
            <p>For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
            <p>Go back to the <a href="javascript:history.go(-1)">previous page</a> or visit our <a href="/admin">homepage</a>
                or try one of the links below:</p>

        </div>
    </div>
    </div>
@stop