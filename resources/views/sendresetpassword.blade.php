<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
</head>
<body>
    <h2>{{ $confirmed['title'] }}</h2>
    <p>{{ $confirmed['body'] }}</p>
    <p><a href="{{ $confirmed['url'] }}">Click here to reset your password</a></p>
</body>
</html>
