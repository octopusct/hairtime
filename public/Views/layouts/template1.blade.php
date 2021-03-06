<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HairTime | Admin Panel</title>
    <!-- Core CSS - Include with every page -->
    <link href="/api/public/plugins/bootstrap/bootstrap.css" rel="stylesheet"/>
    <link href="/api/public/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link href="/api/public/plugins/pace/pace-theme-big-counter.css" rel="stylesheet"/>
    <link href="/api/public/css/style.css" rel="stylesheet"/>
    <link href="/api/public/css/main-style.css" rel="stylesheet"/>
    <!-- Page-Level CSS -->
</head>
<body>
<!-- navbar top -->
@include('layouts.head')
<!-- end navbar top -->
<!-- navbar side -->
@include('layouts.side')
<!-- end navbar side -->
<!-- page-content -->
@yield('content')
<!-- end page-content -->

<!-- Core Scripts - Include with every page -->
<script src="/plugins/jquery-1.10.2.js"></script>
<script src="/plugins/bootstrap/bootstrap.min.js"></script>
<script src="/plugins/metisMenu/jquery.metisMenu.js"></script>

</body>

</html>
