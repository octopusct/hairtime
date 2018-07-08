@extends('layouts.template')

@section('login')
    <style>
        p {
            text-align: center;
            margin-top: 5px;
            padding-bottom-bottom: 10px;
            font-weight: 600;
            color: darkred;
        }
    </style>
<div class="container">

    <div class="row" @if ($lang['lang']=='he') style="direction:rtl"@endif>
        <div class="col-md-4 col-md-offset-4 text-center logo-margin ">
            <img src="/api/public/img/image.jpg" style="width: 100%" alt=""/>
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading" style="align-content:right ">
                    <h3 class="panel-title">{{$lang['please_sign_in']}}</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="/api/login">
                        <fieldset>
                            @if(isset($error))
                                <p>{{$error}}</p>
                            @endif
                            <div class="form-group">
                                <input class="form-control" name="email" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control"  name="password" type="password"
                                       value="">
                            </div>
                            <div class="checkbox">
                                <label for="remember">{{trim($lang['remember_me'])}}</label>
                                <input name="remember" id="remember" type="checkbox" value="Remember Me"  @if ($lang['lang']=='he') style="float:none" @endif>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" value="{{$lang['login']}}" name="log_in"/>
                            <!-- <a class="btn btn-lg btn-success btn-block" type="submit" name="log_in"  >Login</a> -->
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop