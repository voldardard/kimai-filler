<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        body{
            font-family: "Roboto", sans-serif;
        }
        .login-page {
            width: 390px;
            padding: 8% 0 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 390px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background-color: #151515;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            transition: all;
            cursor: pointer;
        }
        .form button:hover,.form button:active,.form button:focus {
            background: #434343;
        }
        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }
        .form .message a {
            color: #151515;
            text-decoration: none;
            font-weight: bolder;
        }
        .form .register-form {
            display: none;
        }
        .red{
            color:red;
            font-weight: bolder;
            text-align: center;
        }
        .green{
            color:green;
            font-weight: bolder;
            text-align: center;
        }
        .orange{
            color: rgb(249, 190, 18);
        }
        .grey{
            color: #646460;
            cursor: auto;
        }
    </style>
</head>
<body>
<div class="login-page">
    <div class="form">
        <form class="login-form" method="POST" action="/login">
            <input type="text" autofocus required name="user" value="{{ old('user') }}" placeholder="Username"/>
            <input type="password" required name="password" placeholder="Kimai API token"/>
            <button>Log-in</button>
            @csrf
        </form>
        @if (session('error'))
            <br /> <br /><span class="red">{{ session('error') }}</div>
        @endif

    </div>
</div>

</body>
</html>
