<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog Post</title>
    <link rel="stylesheet" href="{{ asset('asset/authenticatestyle.css') }}">
</head>

<body>
    <div class="form-modal">

        <div class="form-toggle">
            <button id="login-toggle" onclick="toggleLogin()">log in</button>
            <button id="signup-toggle" onclick="toggleSignup()">sign up</button>
        </div>

        <div id="login-form">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                {{-- <input type="hidden" name="form" value="1"> --}}
                <input type="text" name="username" placeholder="Enter email or username" />
                <input type="password" name="password" placeholder="Enter password" />
                <button type="submit" class="btn login">login</button>
            </form>
        </div>

        <div id="signup-form">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                {{-- <input type="hidden" name="form" value="2"> --}}
                <input type="email" name="email" placeholder="Enter your email" />
                <input type="text" name="username" placeholder="Choose username" />
                <input type="password" name="password" placeholder="Create password" />
                <input type="password" name="password_confirmation" placeholder="Confirm password" />
                <button type="submit" class="btn signup">create account</button>
            </form>
        </div>

    </div>

<script src="{{ asset('asset/authenticatestyle.js') }}"></script>

</body>

</html>
