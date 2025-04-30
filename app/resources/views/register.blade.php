<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Form</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #C6E7FF, #C6E7FF);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .container img {
      width: 50%;
      height: auto;
      border-radius: 8px;
      margin: 0 auto 20px;
      display: block;
    }

    .container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .container input[type="text"],
    .container input[type="password"],
    .container input[type="tel"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    .container button {
      width: 100%;
      padding: 12px;
      background-color: rgb(138, 189, 246);
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .container button:hover {
      background-color: #0056b3;
    }

    .text-danger {
      color: red;
      font-size: 12px;
    }
    .back-button {
        margin-top: 10px;
        text-align: center;
    }

    .back-button button {
        padding: 10px 20px;
        background-color: #f44336; /* Red color */
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
    }

    .back-button button:hover {
        background-color: #d32f2f;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="{{ asset('images/title_shadow.png') }}" alt="Washing Machine">
    <h2>Create an Account</h2>
    <form action="{{ url('/register') }}" method="POST">
      @csrf

      <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
      @error('last_name')
        <div class="text-danger">{{ $message }}</div>
      @enderror

      <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
      @error('first_name')
        <div class="text-danger">{{ $message }}</div>
      @enderror

      <input type="tel" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number') }}">
      @error('mobile_number')
        <div class="text-danger">{{ $message }}</div>
      @enderror

      <input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
      @error('email')
        <div class="text-danger">{{ $message }}</div>
      @enderror


      <input type="password" name="password" placeholder="Password">
      @error('password')
        <div class="text-danger">{{ $message }}</div>
      @enderror

      <input type="password" name="password_confirmation" placeholder="Re-enter Password">

      <button type="submit">Register</button>
    </form>

        <!-- Back button to login page -->
    <a href="{{ route('login') }}" class="back-button">
        <button type="button">Back to Login</button>
    </a>
  </div>
</body>
</html>
