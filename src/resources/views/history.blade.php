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
    .card.shadow-6 {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }
    #ordersTable th, #ordersTable td {
        text-align: center;
    }
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

    <!-- Order Summary Table Section -->
    <div class="container mt-5">
        <!-- Adding the shadow to the card -->
        <div class="card shadow-6 p-4 rounded">
            <div class="card-body">
                <table id="ordersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Service Number</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Date Dropped Off</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">Total Load</th>
                            <th class="text-center">Service Type</th>
                            <th class="text-center">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="text-center">{{ $order->id }}</td>
                                <td class="text-center">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</td>
                                <td class="text-center">{{ $order->created_at->format('m/d/Y') }}</td>
                                <td class="text-center">{{ $order->created_at->format('H:i') }}</td>
                                
                                <td class="text-center">
                                    @foreach($order->items as $item)
                                        {{ $item->total_load }}<br> <!-- Display total load -->
                                    @endforeach
                                </td>

                                <td class="text-center">
                                    @foreach($order->items as $item)
                                        {{ $item->service_type }}<br> <!-- Display service type -->
                                    @endforeach
                                </td>

                                <td class="text-center">₱{{ number_format($order->grand_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS + JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        $('#ordersTable').DataTable({
            "pageLength": 10,
            "lengthChange": false,
            "ordering": true
        });
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
