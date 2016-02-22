<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form action="" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="appname">公众号名称</label>
                    <input type="text" class="form-control" id="appname" name="appname" placeholder="公众号名称">
                </div>
                <div class="form-group">
                    <label for="appid">APPID</label>
                    <input type="text" class="form-control" id="appid" name="appid" placeholder="">
                </div>
                <div class="form-group">
                    <label for="appsecret">Secret</label>
                    <input type="text" class="form-control" id="appsecret" name="appsecret" placeholder="">
                </div>
                <div class="form-group">
                    <label for="agent_id">Secret</label>
                    <input type="text" class="form-control" id="agent_id" name="agent_id" placeholder="Agent id">
                </div>
                <button type="submit" class="btn btn-default">添加</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>