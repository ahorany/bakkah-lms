<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{CustomAsset('assets/images/logo.png')}}">

    <link rel="stylesheet" href="{{CustomAsset('assets/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/style.css')}}">
</head>
<body>
   @yield('content')
</body>
</html>
