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
            <a href="{{ route('expenses.index') }}" class="nav-link">Expenses</a>
        </li>
        <li class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
            <a href="{{ route('notifications') }}" class="nav-link">Notifications</a>
        </li>
        <li class="{{ request()->routeIs('account.settings') ? 'active' : '' }}">
            <a href="{{ route('account.settings') }}" class="nav-link">Account Information</a>
        </li>
        <li class="{{ request()->routeIs('customers') ? 'active' : '' }}">
            <a href="{{ route('customers') }}" class="nav-link">Customers</a>
        </li>
        <li class="{{ request()->routeIs('reports') ? 'active' : '' }}">
            <a href="{{ route('reports') }}" class="nav-link">Reports</a>
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


        <!-- Account Modal -->
    <div class="container my-5 p-4 border rounded-5 shadow-lg bg-white">
        <!-- Account Information Section -->
        <h4 class="mb-4 text-center">Account Information</h4>

        <form action="{{ route('account.updateInfo') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="firstName" name="first_name" value="{{ auth()->user()->first_name }}" placeholder="Enter first name" style="background-color: #f0f8ff;">
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="lastName" name="last_name" value="{{ auth()->user()->last_name }}" placeholder="Enter last name" style="background-color: #f0f8ff;">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Enter email" style="background-color: #f0f8ff;">
            </div>

            <div class="mb-3">
                <label for="profilePicture" class="form-label">Upload Profile Picture</label>
                <input type="file" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="profilePicture" name="profile_picture" accept="image/*" style="background-color: #f0f8ff;">
            </div>

            <div class="d-flex justify-content-start    mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm" style="background-color: #578fca; border-color: #add8e6;">
                    Update Information
                </button>
            </div>
        </form>

        <hr class="my-4">

        <!-- Account Settings Section -->
        <h4 class="mb-4 text-center">Account Settings</h4>

        <form action="{{ route('account.changePassword') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="oldPassword" class="form-label">Old Password</label>
                <input type="password" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="oldPassword" name="old_password" placeholder="Enter Old Password" required style="background-color: #f0f8ff;">
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="newPassword" name="new_password" placeholder="Enter New Password" required style="background-color: #f0f8ff;">
            </div>

            <div class="mb-3">
                <label for="newPasswordConfirmation" class="form-label">Re-enter New Password</label>
                <input type="password" class="form-control form-control-lg rounded-3 shadow-none border-0 bg-light"
                    id="newPasswordConfirmation" name="new_password_confirmation" placeholder="Re-enter New Password" required style="background-color: #f0f8ff;">
            </div>

            <div class="d-flex justify-content-start mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm" style="background-color: #578fca; border-color: #add8e6;">
                    Update Password
                </button>
            </div>
        </form>
    </div>

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


            <div class="mt-5">
                <div class="alert alert-info text-center" role="alert" style="background-color: #d1e7dd; border-color: #badbcc;">
                    <strong>Last updated on:</strong> {{ auth()->user()->updated_at->format('F d, Y \a\t h:i A') }}
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
