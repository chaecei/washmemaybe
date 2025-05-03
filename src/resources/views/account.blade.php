<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <style>
        .account-panel {
            width: 400px;
            height: 100%;
            background-color: #f9f9f9;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            position: absolute;
            top: 0;
            right: 0;
            display: none; /* Hidden by default */
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        .account-panel.active {
            display: block;
            transform: translateX(0);
        }

        .account-panel .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .form-control, .form-select {
            border: 1px solid black;
            border-radius: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
        }
        .btn-custom {
            border: 1px solid black;
            border-radius: 10px;
            padding: 5px 20px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            background-color: white;
        }
        .section-title {
            margin-top: 40px;
            font-weight: bold;
            font-size: 24px;
        }
        .subsection-title {
            font-weight: bold;
            font-size: 14px;
        }
        .profile-icon {
            font-size: 80px;
            display: block;
            margin: 20px auto;
        }

    </style>

    <div class="sidebar">
        <ul>
          <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
          <li><a href="{{ route('services') }}" class="nav-link services-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
          <li><a href="redirect(history.html)" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a></li>
          <li><a href="redirect(expenses.html)" class="nav-link {{ request()->routeIs('expenses') ? 'active' : '' }}">Expenses</a></li>
          <li><a href="redirect(notification.html)" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">Notification</a></li>
          <li><a href="#" onclick="openAccountModal()" id="accountLink" class="nav-link">Account Information</a></li>
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


        <!-- Account Modal -->
        <div id="accountModal" class="modal-container">
            <div class="modal-content">
    
                <!-- Account Information -->
                <form action="{{ route('account.updateInfo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" name="first_name" placeholder="First Name"
                        value="{{ auth()->user()->first_name }}">
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name"
                        value="{{ auth()->user()->last_name }}">
                    <input type="email" class="form-control" name="email" placeholder="Email Address"
                        value="{{ auth()->user()->email }}">

                    <label for="profilePicture" class="form-label">Upload Profile Picture</label>
                    <input type="file" class="form-control" id="profilePicture" name="profile_picture">

                    <button type="submit" class="btn-custom mt-3">Confirm</button>

                    <div class="section-title">Other Information</div>
                    <!-- <select class="form-select" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select> -->
                    <input type="text" class="form-control" placeholder="Last Password Change Date" readonly>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Password Change Section -->
                <div class="section-title mt-4">Account Settings</div>
                <div class="subsection-title">Password Change</div>
                <form action="{{ route('account.changePassword') }}" method="POST">
                    @csrf
                    <input type="password" class="form-control" name="old_password" placeholder="Enter Old Password">
                    <input type="password" class="form-control" name="new_password" placeholder="Enter New Password">
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Re-enter New Password">
                    <button type="submit" class="btn-custom mt-3">Update Password</button>
                </form>
            </div>
        </div>

    <script>
        function openAccountModal() {
            document.getElementById('accountModal').classList.add('active');
        }

        function closeAccountModal() {
            document.getElementById('accountModal').classList.remove('active');
        }
    </script>

</body>
</html>
