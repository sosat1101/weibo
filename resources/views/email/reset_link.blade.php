<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>找回密码</title>
</head>
<body>
    <h1>您正在尝试找回密码</h1>
    <p>
        请点击以下链接进入下一步操作：
        <a href="{{ route('password.reset', $token)}}">
            {{ route('password.reset', $token)}}
        </a>
    </p>
</body>
</html>