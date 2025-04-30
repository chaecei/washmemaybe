<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wash Me Maybe</title>
  <link href="https://fonts.googleapis.com/css2?family=Helvetica+Neue:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    body {
      background: linear-gradient(#f9f9f9, #eee);
      font-family: 'Helvetica Neue', sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      background-color:rgb(255, 237, 237);
    }
    .navbar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        font-weight: bold;
        padding: 20px 40px;
        position: absolute;
        top: 25px;
        right: 290px; /* Move it closer to the image */
        font-size: 18px;
        gap: 20px; /* Adds space between "Register" and "About the shop" */
    }

    .navbar a {
      margin-left: 20px;
      color: #333;
      text-decoration: none;
      font-weight: 700;
    }
    .main-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .content {
      display: flex;
      width: 100%;
      max-width: 1200px;
      justify-content: space-between;
      align-items: center;
    }
    .left-section {
      flex: 1;
      padding-right: 50px;
    }
    .left-section h2 {
      font-size: 65px;
      font-family: 'Arial';
      font-weight: 700;
      color:rgb(30, 127, 212);
      margin-bottom: 30px;
    }
    .left-section p {
      font-size: 18px;
      color: #555;
      margin-bottom: 30px;
      max-width: 400px;
    }
    .form-group {
      margin-bottom: 15px;
      max-width: 400px;
      border-radius: 10px;
    }
    .form-control {
      height: 50px;
      font-size: 16px;
      border-radius: 11px;
      padding: 12px 18px;
    }
    .btn-login {
      width: 90px;
      height: 38px;
      font-size: 18px;
      border-radius: 18px;
      background-color: #1877f2;
      color: #fff;
      border: none;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .btn-login:hover {
      background-color: #166fe0;
    }
    .forgot-password {
      margin-top: 10px;
      display: block;
      font-size: 14px;
      color: #27548A;
      text-decoration: none;
    }
    .store-buttons {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }
    .store-buttons img {
      height: 40px;
    }
    .right-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .right-section img {
      width: 150%;
      max-width: 650px;
      margin-top: 50px;
    }
    @media (max-width: 991px) {
      .content {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
      .left-section {
        padding-right: 0;
      }
      .right-section {
        margin-top: 30px;
      }
    }
  </style>
</head>
<body>

<div class="navbar">
  <a href="{{ route('register') }}" class="register">Register</a>
</div>

<div class="main-container">
  <div class="content">
    <div class="left-section">
      <h2>Hello, Admin! </h2>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <input type="text" name="mobile_number" class="form-control" placeholder="Username/Email" required value="{{ old('mobile_number') }}">
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-login">Log In</button>
        <a href="#" class="forgot-password">Forgotten your password?</a>


        @if ($errors->any())
          <div class="error-message" style="color: red; margin-top: 10px;">{{ $errors->first() }}</div>
        @endif
      </form>
    </div>

    <div class="right-section">
      <img src="{{ asset('images/title_shadow.png') }}" alt="Washing Machine">
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


