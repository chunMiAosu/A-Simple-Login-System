<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录界面</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    @if(count($errors) > 0)
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li><br/>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div>
        <form action="" method="post">
            <p>用户名: <input type="text" name="name"></p>
            <p>密码: <input type="password" name="password"></p>
            <p>验证码: <input type="text" name="captcha"><img src="{{captcha_src()}}"></p>
            {{csrf_field()}}
            <input type="submit" value="登录">
        </form>
    </div>

</body>
</html>