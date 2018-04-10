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

        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center logo-margin ">
                <img src="/img/image.jpg" style="width: 100%" alt=""/>
            </div>
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="/admin/dispatcher">
                            <fieldset>
                                @if(isset($error))
                                    <p>{{$error}}</p>
                                @endif
                                <div class="form-group">
                                    <input class="form-control"  name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" type="password"
                                           value="">
                                </div>
                                <div>
                                    <input type="hidden" name="method" value="post">
                                    <input type="hidden" name="result" value="index">
                                    <input type="hidden" name="url" value="http://hairtime.co.il/auth/singin">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                    <label style="align-items:right">
                                        <a href="/admin/fogot">Forgot password...</a>
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login"
                                       name="log_in"/>
                                <!-- <a class="btn btn-lg btn-success btn-block" type="submit" name="log_in"  >Login</a> -->
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop