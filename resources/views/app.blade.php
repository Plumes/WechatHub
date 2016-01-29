<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>菜单</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">公众号管理平台</a>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="left-bar col-md-3">
            <ul>
                <li>菜单管理</li>
                <li>图文消息</li>
                <li>素材管理</li>
                <li>账号配置</li>
            </ul>
        </div>
        <div class="right-bar col-md-9">
            @yield("content")
        </div>
    </div>
</div>
@yield("script")
</body>
</html>