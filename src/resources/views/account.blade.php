<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>
    <style>
        .profile-icon {
            font-size: 80px;
            display: block;
            margin: 20px auto;
        }
        body {
        background-color: #f9f7f3;
        font-family: 'Segoe UI', sans-serif;
        }

        .form-container,
        .form-container1 {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #ccc;
            background-color: #fdfdfd;
        }

        .btn-pastel {
            background-color: #60B5FF;
            color: #000;
            border: none;
            padding: 10px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-pastel:hover {
            background-color: #3D90D7;
            color: #000;
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


    <!-- Redesigned Account Modal -->
    <div class="container my-5 px-2">
        <!-- Account Information Section -->
        <div class="form-container">
            <h5 class="mb-3">Account Information</h5>
            <form action="{{ route('account.updateInfo') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" value="{{ auth()->user()->first_name }}" placeholder="Enter first name" readonly />
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" value="{{ auth()->user()->last_name }}" placeholder="Enter last name" readonly />
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Enter email" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Profile Picture</label>
                    <input type="file" class="form-control" id="profilePicture" name="profile_picture" accept="image/*" />
                </div>

                <div class="text-start mt-3">
                    <button type="submit" class="btn btn-pastel">Update Information</button>
                </div>
            </form>
        </div>

        <!-- Divider -->
        <div class="my-4"></div>

        <!-- Account Settings Section -->
        <div class="form-container1">
            <h5 class="mb-3">Account Settings</h5>
            <form action="{{ route('account.changePassword') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Old Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="oldPassword" name="old_password" placeholder="Enter Old Password" required />
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleVisibility('oldPassword', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter New Password" required />
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleVisibility('newPassword', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Re-enter New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="newPasswordConfirmation" name="new_password_confirmation" placeholder="Re-enter New Password" required />
                        <button class="btn btn-outline-secondary" type="button" onclick="toggleVisibility('newPasswordConfirmation', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="text-start mt-3">
                    <button type="submit" class="btn btn-pastel">Update Password</button>
                </div>
            </form>
        </div>
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

    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    <script>
        function toggleVisibility(id, button) {
            const input = document.getElementById(id);
            const icon = button.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>
