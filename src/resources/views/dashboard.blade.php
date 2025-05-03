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
    <div class="sidebar">
        <ul>
          <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
          <li><a href="{{ route('services') }}" class="nav-link services-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
          <li><a href="redirect(history.html)" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a></li>
          <li><a href="redirect(expenses.html)" class="nav-link {{ request()->routeIs('expenses') ? 'active' : '' }}">Expenses</a></li>
          <li><a href="{{ route('notifications') }}" class="nav-link notifications-link {{ request()->routeIs('notifications') ? 'active' : '' }}">Notification</a></li>
          <li><a href="{{ route('account.settings') }}" class="nav-link account-link {{ request()->routeIs('account.settings') ? 'active' : '' }}">Account Information</a></li>
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

    <div class="row mb-2">
            <div class="col-md-3">
                    <div class="card card-pending">
                        <div class="card-body">
                            <h5 class="card-title" id="pending">Pending</h5>
                            <p class="card-text h4">9</p>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-md-3">
                    <div class="card card-processing">
                        <div class="card-body">
                            <h5 class="card-title" id="processing">Processing</h5>
                            <p class="card-text h4">4</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                    <div class="card card-ready">
                        <div class="card-body">
                            <h5 class="card-title" id="ready">Ready for Pickup</h5>
                            <p class="card-text h4">2</p>
                        </div>
                    </div>
                </a>    
            </div>

            <div class="col-md-3">
                    <div class="card card-completed">
                        <div class="card-body">
                            <h5 class="card-title" id="unclaimed">Unclaimed</h5>
                            <p class="card-text h4">2</p>
                        </div>
                    </div>
                </a>
            </div>
    </div>

    <div class="d-flex-space mb-4">
        <div class="card flex-grow-1 me-4">
            <div class="card-header">
                <h5 class="mb-0">Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SERVICE NUMBER</th>
                                <th>Status</th>
                                <th>Date and Time Picked Up</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>WIN001</td>
                                <td><span class="status-badge badge-completed">Completed</span></td>
                                <td>04/11/25 16:00</td>
                                <td>450.00</td>
                            </tr>
                            <tr>
                                <td>WIN002</td>
                                <td><span class="status-badge badge-ready">Ready for Pickup</span></td>
                                <td></td>
                                <td>150.00</td>
                            </tr>
                            <tr>
                                <td>WIN003</td>
                                <td><span class="status-badge badge-ready">Ready for Pickup</span></td>
                                <td></td>
                                <td>600.00</td>
                            </tr>
                            <tr>
                                <td>WIN004</td>
                                <td><span class="status-badge badge-processing">In Progress</span></td>
                                <td></td>
                                <td>300.00</td>
                            </tr>
                            <tr>
                                <td>WIN005</td>
                                <td><span class="status-badge badge-processing">In Progress</span></td>
                                <td></td>
                                <td>550.00</td>
                            </tr>
                            <tr>
                                <td>WIN006</td>
                                <td><span class="status-badge badge-processing">In Progress</span></td>
                                <td></td>
                                <td>520.00</td>
                            </tr>
                            <tr>
                                <td>WIN007</td>
                                <td><span class="status-badge badge-pending">Pending</span></td>
                                <td></td>
                                <td>240.00</td>
                            </tr>
                            <tr>
                                <td>WIN008</td>
                                <td><span class="status-badge badge-pending">Pending</span></td>
                                <td></td>
                                <td>150.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="circle-card">
            <div>
                <div>Total Earnings:</div>
                <div class="h4">1,600.00</div>
            </div>
        </div>
    </div>


    <script>
        function toggleSubmenu(id) {
            var submenu = document.getElementById(id);
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        }
    
        function redirect(url) {
            window.location.href = url;
        }

        function logout() {
            // Add logout logic here (e.g., redirect to login page, clear session)
            alert("Logging out...");
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $(".nav-card").click(function() {
            var target = $(this).data('target');
            $(".table-section").hide();
            $("#" + target).fadeIn();
        });
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pendingCard = document.querySelector('.card-pending');
            if (pendingCard) {
                pendingCard.addEventListener('click', function () {
                    const modal = new bootstrap.Modal(document.getElementById('pendingModal'));
                    modal.show();
                });
            }
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
