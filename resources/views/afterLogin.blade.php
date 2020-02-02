<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录成功</title>
</head>
<body>
    <p>
        恭喜你{{$name}}！登录成功！没想到吧哈哈哈
    </p>
    <form action="/logout">
        <input type="submit" value="注销">  
    </form>
</body>
</html>