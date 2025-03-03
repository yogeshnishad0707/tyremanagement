<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="" method="POST">
        @csrf
        <input type="text" name="id" value="{{ $user[0]['id'] }}" >
        <input type="text" name="password" placeholder="Enter Your Password" ><br>
        <input type="text" name="password_confirmation" placeholder="Enter Your New Password" ><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>