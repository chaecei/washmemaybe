<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications – Laundry Shop Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="flex bg-[#D4F6FF] min-h-screen font-sans">

    <div class="sidebar">
      <ul>
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('services') ? 'active' : '' }}">
            <a href="{{ route('services') }}" class="nav-link">Services</a>
        </li>
        <li class="{{ request()->routeIs('history') ? 'active' : '' }}">
            <a href="{{ route('history') }}" class="nav-link">History</a>
        </li>
        <li class="{{ request()->routeIs('expenses') ? 'active' : '' }}">
            <a href="{{ route('expenses') }}" class="nav-link">Expenses</a>
        </li>
        <li class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
            <a href="{{ route('notifications') }}" class="nav-link">Notifications</a>
        </li>
        <li class="{{ request()->routeIs('account.settings') ? 'active' : '' }}">
            <a href="{{ route('account.settings') }}" class="nav-link">Account Information</a>
        </li>
        <li>
            <a href="#" class="logout-link no-hover" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            </form>
        </li>
      </ul>
    </div>
        
    <div class="main-content">    
        
    <div class="admin-profile">
      <span class="status-text">Good Day, Admin!</span>
      <img src="{{ auth()->user()->profile_pictures ? asset('profile_pictures/' . auth()->user()->profile_pictures) : asset('images/admin_icon.jpg') }}" alt="Admin" class="admin-img">
    </div>

    <div class="header"> 
        <img src="{{ asset('images/title.png') }}" alt="Header Image" class="header-image">
    </div>

    <div class="container mt-4">

    <!-- Filter Dropdown -->
    <div class="d-flex justify-content-end mb-3">
      <select class="form-select w-auto">
        <option selected>Filter with</option>
        <option value="all">All</option>
        <option value="orders">Orders</option>
      </select>
    </div>

    <!-- Notifications Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-info">
          <tr>
            <th scope="col">Date and Time</th>
            <th scope="col">Notification</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>04/01 10:09</td>
            <td>New user registered into your system.</td>
          </tr>
          <tr>
            <td>04/01 10:30</td>
            <td>WIN023 laundry completed.</td>
          </tr>
          <tr>
            <td>04/01 13:03</td>
            <td>John Doe sent a laundry order.</td>
          </tr>
          <tr>
            <td>04/02 09:34</td>
            <td>Jennifer Huh sent a laundry order.</td>
          </tr>
          <tr>
            <td>04/02 10:56</td>
            <td>WIN011 laundry completed.</td>
          </tr>
        </tbody>
      </table>
    </div>

</div>

</body>
</html>

