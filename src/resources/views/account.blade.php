<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
            border-radius: 10px;
            padding: 5px 20px;
            background-color: #578FCA;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(128, 128, 128, 0.3); /* bottom-only gray shadow */
        }
        .btn-custom:hover {
            background-color: #4176ad; /* Slightly darker shade */
            transform: translateY(-2px); /* Subtle lift effect */
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.25); /* Softer and deeper shadow */
        }
        .section-title {
            margin-top: 40px;
            margin-bottom: 30px;
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
        /* .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        } */
        /* .modal-container.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            max-height: 90vh;
            position: relative;
        } */

    </style>

    <div class="sidebar">
        <ul>
          <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
          <li><a href="{{ route('services') }}" class="nav-link services-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
          <li><a href="redirect(history.html)" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a></li>
          <li><a href="redirect(expenses.html)" class="nav-link {{ request()->routeIs('expenses') ? 'active' : '' }}">Expenses</a></li>
          <li><a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">Notification</a></li>
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
                <div class="section-title">Account Information</div>
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
                </form>
                
                <!-- Password Change Section -->
                <div class="section-title mt-4">Account Settings</div>
                <div class="subsection-title">Password Change</div>
                <form action="{{ route('account.changePassword') }}" method="POST">
                    @csrf

                    <input type="password" class="form-control" name="old_password" placeholder="Enter Old Password" required>
                    <input type="password" class="form-control" name="new_password" placeholder="Enter New Password" required>
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Re-enter New Password" required>
                    <button type="submit" class="btn-custom mt-3">Update Password</button>
                </form>

                    <!-- Display success message -->
                    @if(session('success'))
                    <script>
                        Toastify({
                            text: "{{ session('success') }}",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#28a745",
                        }).showToast();
                    </script>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <script>
                            @foreach ($errors->all() as $error)
                                Toastify({
                                    text: "{{ $error }}",
                                    duration: 5000,  // Longer duration for errors
                                    close: true,
                                    gravity: "top",
                                    position: "center",  // Matches success position
                                    backgroundColor: "#dc3545",  // Bootstrap's danger red
                                    offset: {
                                        y: 50  // Stacks below success messages
                                    },
                                    ariaLive: "polite",  // Accessibility
                                    className: "toastify-error",  // For custom styling
                                    style: {
                                        boxShadow: "none",
                                        fontSize: "14px",
                                        fontWeight: "500"
                                    }
                                }).showToast();
                            @endforeach
                        </script>
                    @endif

                <div class="form-group mt-5">
                    <!-- <label class="form-label">Last Update:</label> -->
                    <div class="alert alert-info" role="alert">
                        Last updated on {{ auth()->user()->updated_at->format('F d, Y \a\t h:i A') }}
                    </div>
                </div>


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
