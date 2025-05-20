<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
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

    <div class="container mt-4">

    <!-- Notifications Table -->
    <div class="card border-0 shadow rounded-4">
      <div class="card-header bg-white border-bottom-0 d-flex align-items-center justify-content-between px-4 py-3">
        <h5 class="mb-0 fw-semibold text-black" style="font-size: 1.4rem;">Notifications</h5>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-striped align-middle nowrap" id="notificationsTable" style="width:100%">
            <thead class="table-info">
              <tr>
                <th class="text-center">Date & Time</th>
                <th class="text-center">Notification</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
      const table = $('#notificationsTable').DataTable({
          paging: true,
          searching: true,
          info: true,
          autoWidth: false,
          order: [[0, 'desc']] 
      });

      function loadNotifications() {
          $.ajax({
              url: "{{ route('notifications.fetch') }}",
              method: "GET",
              success: function (data) {
                  table.clear();
                  data.forEach(n => {
                      table.row.add([
                          new Date(n.created_at).toLocaleString(),
                          n.message
                      ]);
                  });
                  table.draw();
              },
              error: function (xhr, status, error) {
                  console.error("Error loading notifications:", error);
              }
          });
      }

      loadNotifications();
      setInterval(loadNotifications, 30000);
  });
</script>

<script>
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>

</body>
</html>

