@extends('layouts.main')

@section('content')
    <style>
        .form {
            margin: 25px auto;
        }
    </style>
    <div class="form">
        <form method="post" action="/dispatcher" class="n-form">
            <div class="title">Create new Salon</div>
            <div class="wrapper-field">
                <label><p>e-mail use as username to login. </p><input type="text" required name="email"></label>
                <label><p>First name. </p><input type="text" required name="first_name"></label>
                <label><p>Last name. </p><input type="text" required name="last_name"></label>
                <label><p>Bussiness name. </p><input type="text" required name="business_name"></label>
                <label><p>Founded date. </p><input type="text" required name="founded_in"></label>
                <label><p>Salon’s city. </p><input type="text" required name="city"></label>
                <label><p>Salon’s address. </p><input type="text" required name="address"></label>
                <label><p>Salon’s house number. </p><input type="text" required name="house"></label>
                <label><p>Salon’s coordinata LAT "XX.XXXXXXXX".</p><input type="text" name="lat"></label>
                <label><p>Salon’s coordinata LNG "(-)XX.XXXXXXXX". </p><input type="text" name="lng"></label>
                <label><p>Salon’s password. </p><input type="text" required name="password"></label>
                <label><p>Salon’s phone. </p><input type="text" required name="phone"></label>
                <input hidden value="https://hairtime.co.il/auth/singup/salon" name="url"/>
                <input hidden value="newSalon" name="request"/>
                <div class="btn-wrapper clearfix">
                    <button type="sumbit" class="btn-primary">Save</button>
                    <button type="reset" class="btn-cancel">Cancel</button>
                </div>
            </div>
        </form>
    </div>

@stop
