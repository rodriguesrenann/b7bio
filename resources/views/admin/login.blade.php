<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('assets/css/admin.login.css')}}">
</head>
<body>
    <div class="loginArea">
        <h1>Login</h1>
        @if ($error)
            <div class="error">{{$error}}</div>
        @endif
        <form method ="POST">
            @csrf
            <input type="email" name="email" placeholder="Digite seu email">

            <input type="password" name="password" placeholder="Digite sua senha">

            <input type="submit" value="Entrar">

            Ainda não possui cadastro? <a href="{{route('register')}}">Cadastre-se</a>
        </form>
    </div>
</body>
</html>