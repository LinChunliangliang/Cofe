<!DOCTYPE html>
<html lang="en">
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?436fb257af45b07f93429ccc851c4cf0";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>

        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <title>Cofe</title>

        <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.8&key=108a1a121e660dcf303671c320cf406d"></script>
        <script type='text/javascript'>
            window.Laravel =<?php echo json_encode(['csrfToken' => csrf_token(),]); ?>
        </script>
    </head>
    <body>

    <div id="app">

        <router-view>

        </router-view>
    </div>

    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    </body>
</html>
