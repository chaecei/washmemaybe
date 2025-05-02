<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fbfbfb;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 30px 20px;
            background-color: #d4f6ff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100px;
            height: auto;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #c6e7ff;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #ffddae;
            color: #333;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c6e7ff;
        }

        .error-message {
            color: red;
            font-size: 12px;
            text-align: left;
        }

        .success-message {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .login-link {
            margin-top: 15px;
        }

        .login-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="logo">
            <img src="{{ asset('laundry-image.png') }}" alt="Laundry Logo">
        </div>

        <h2>Registration Form</h2>

        @if(session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            @error('first_name') <p class="error-message">{{ $message }}</p> @enderror

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name') <p class="error-message">{{ $message }}</p> @enderror

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p class="error-message">{{ $message }}</p> @enderror

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            @error('password') <p class="error-message">{{ $message }}</p> @enderror

            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>

</body>
</html>
