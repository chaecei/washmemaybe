<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
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
                <div class="card card-pending" id="card-pending">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text h4">9</p>
                    </div>
                </div>
            </a>
        </div>
    
        <div class="col-md-3">
                <div class="card card-processing" id="card-processing">
                    <div class="card-body">
                        <h5 class="card-title">Processing</h5>
                        <p class="card-text h4">4</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
                <div class="card card-ready" id="card-ready">
                    <div class="card-body">
                        <h5 class="card-title">Ready for Pickup</h5>
                        <p class="card-text h4">2</p>
                    </div>
                </div>
            </a>    
        </div>

        <div class="col-md-3">
                <div class="card card-completed" id="card-unclaimed">
                    <div class="card-body">
                        <h5 class="card-title">Unclaimed</h5>
                        <p class="card-text h4">2</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div id="dynamicContent" class="d-flex flex-grow-1 gap-3">
        <div class="card flex-grow-1 me-4" id="defaultView">
            <div class="card-header">
                <h5 class="mb-0">Orders Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="ordersTable">
                        <thead>
                            <tr>
                                <th>SERVICE NUMBER</th>
                                <th>Status</th>
                                <th>Date and Time Picked Up</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr data-order-id="{{ $order->id }}">
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        {{ $order->category->status ?? 'No Status' }}
                                    </td>
                                    <td>
                                        {{ $order->picked_up_at ? $order->picked_up_at->format('M d, Y h:i A') : 'Not yet picked up' }}
                                    </td>
                                    <td>₱{{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="totalEarningsCard" class="circle-card">
            <div>
                <div>Total Earnings:</div>
                <div class="h4">₱{{ number_format($grandTotal, 2) }}</div>
            </div>
        </div>
        <div id="table-container" class="flex-grow-1" style="display: none;"></div>
    </div>

    <div style="max-width: 1600px; margin: 0 auto;">
        <div style="display: flex; gap: 30px; justify-content: center; align-items: center; flex-wrap: wrap; padding: 20px;">
            
            <!-- Weekly Reports Chart Card -->
            <div id="weeklyOrders" class="card mt-4" style="flex: 1 1 48%; max-width: 800px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                <div class="card-header">
                    Weekly Reports
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="chart-container" style="height: 500px; width: 100%; display: flex; justify-content: center; align-items: center; padding: 15px;">
                        <canvas id="weeklyOrdersChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Expenses Chart Card -->
            <div id="totalExpenses" class="card mt-4" style="flex: 1 1 48%; max-width: 800px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                <div class="card-header">
                    Expenses Chart
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="chart-container" style="height: 500px; width: 100%; display: flex; justify-content: center; align-items: center; padding: 15px;">
                        <canvas id="expenseChart" style="width: 100% !important; height: 100% !important;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch('/dashboard/weekly-orders')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('weeklyOrdersChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Number of Orders',
                            data: data.data,
                            backgroundColor: 'rgba(38, 120, 192, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error loading chart:', error));
    });
</script>

@if($mode === 'index')
    <script>
        const categoryTotals = {};
        @foreach($expenses as $exp)
            categoryTotals["{{ $exp->category }}"] = (categoryTotals["{{ $exp->category }}"] || 0) + {{ $exp->amount }};
        @endforeach

        const labels = Object.keys(categoryTotals);
        const data = Object.values(categoryTotals);

        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Amount (₱) per Category',
                    data: data,
                    backgroundColor: 'rgba(38, 120, 192, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Card click listeners
        document.getElementById('card-pending').addEventListener('click', () => loadStatusTable('Pending'));
        document.getElementById('card-processing').addEventListener('click', () => loadStatusTable('Processing'));
        document.getElementById('card-ready').addEventListener('click', () => loadStatusTable('Ready for Pickup'));
        document.getElementById('card-unclaimed').addEventListener('click', () => loadStatusTable('Unclaimed'));

        async function loadStatusTable(status, pageUrl = null) {
            try {
                document.getElementById('defaultView').style.display = 'none';
                document.getElementById('table-container').innerHTML = '<p></p>';
                document.getElementById('table-container').style.display = 'block';
                document.getElementById('totalEarningsCard').style.display = 'none';
                document.getElementById('weeklyOrders').style.display = 'none';
                document.getElementById('totalExpenses').style.display = 'none';


                const url = pageUrl || `/orders/status/${encodeURIComponent(status)}`;
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();

                if (!result.data) {
                    throw new Error('Invalid data format received');
                }

                const orders = Array.isArray(result.data) ? result.data : [];

                let tableHtml = `
                    <div class="card flex-grow-1 me-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">${status} Orders</h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="statusTable" class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service Number</th>
                                        <th>Status</th>
                                        <th>Grand Total</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                orders.forEach(order => {
                    tableHtml += `
                        <tr>
                            <td>${order.id}</td>
                            <td><span class="badge bg-${getStatusBadgeColor(order.category.status)}">
                                ${order.category.status}
                            </span></td>
                            <td>₱${order.grand_total.toFixed(2)}</td>
                            <td>${new Date(order.created_at).toLocaleString()}</td>
                            <td>${new Date(order.updated_at).toLocaleString()}</td>
                            <td>${getActionButtons(order)}</td>
                        </tr>`;
                });

                tableHtml += `</tbody></table>`;

                tableHtml += `</div></div>`; // Close card-body and card

                document.getElementById('table-container').innerHTML = tableHtml;

                $('#statusTable').DataTable({
                    paging: true,
                    pageLength: 10,
                    lengthChange: false,
                    ordering: true
                });

            } catch (error) {
                console.error('Error loading orders:', error);
                document.getElementById('table-container').innerHTML = `
                    <div class="alert alert-danger">Error loading orders: ${error.message}</div>`;
            }
        }

        function getStatusBadgeColor(status) {
            const colors = {
                'Pending': 'warning',
                'Processing': 'info',
                'Ready for Pickup': 'success',
                'Unclaimed': 'danger'
            };
            return colors[status] || 'secondary';
        }

        function getActionButtons(order) {
            const status = order.category.status;
            let buttons = '';

            if (status === 'Pending') {
                buttons = `<button class="btn btn-sm btn-primary" onclick="updateOrderStatus(${order.id}, 'Processing')">Start Processing</button>`;
            } else if (status === 'Processing') {
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Ready for Pickup')">Mark as Ready</button>`;
            } else if (status === 'Ready for Pickup') {
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Completed')">Mark as Completed</button>
                        <button class="btn btn-sm btn-danger ms-2" onclick="updateOrderStatus(${order.id}, 'Unclaimed')">Mark as Unclaimed</button>`;
            } else if (status === 'Unclaimed') {
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Completed')">Mark as Claimed</button>`;
            }

            return buttons || 'No actions available';
        }
    });

    function resetView() {
        document.getElementById('table-container').style.display = 'none';
        document.getElementById('defaultView').style.display = 'block';
    }

    async function updateOrderStatus(orderId, newStatus) {
        try {
            const response = await fetch(`/categories/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            });

            const contentType = response.headers.get('content-type');

            if (response.ok && contentType.includes('application/json')) {
                const data = await response.json();
                alert(`Order updated to ${data.category.status}`);
            } else {
                const errorText = await response.text();
                console.error('Server error:', errorText);
                alert('Server error, check console.');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Network error, check console.');
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>