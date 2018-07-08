<!DOCTYPE html>
<?php session_start() ?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HairTime | Admin Panel</title>
    <link href="/api/public/plugins/bootstrap/bootstrap.css" rel="stylesheet"/>

    <link href="/api/public/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <!--
    <link href="/plugins/pace/pace-theme-big-counter.css" rel="stylesheet" />-->
    <link href="/api/public/css/style.css" rel="stylesheet"/>
    <link href="/api/public/css/main-style.css" rel="stylesheet"/>
    <!-- Page-Level CSS -->

</head>

<body>
@yield('login')

<!-- Core Scripts - Include with every page -->
<script src="/api/public/plugins/jquery-1.10.2.js"></script>
<script src="/api/public/plugins/bootstrap/bootstrap.min.js"></script>
<script src="/api/public/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>

</html>
