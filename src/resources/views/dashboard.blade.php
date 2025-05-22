<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

    <div class="row mb-2">
        <div class="col-md-3">
                <div class="card card-pending" id="card-pending">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text h4" id="count-pending">0</p>
                    </div>
                </div>
        </div>
    
        <div class="col-md-3">
                <div class="card card-processing" id="card-processing">
                    <div class="card-body">
                        <h5 class="card-title">Processing</h5>
                        <p class="card-text h4" id="count-processing">0</p>
                    </div>
                </div>
        </div>

        <div class="col-md-3">
                <div class="card card-ready" id="card-ready">
                    <div class="card-body">
                        <h5 class="card-title">Ready for Pickup</h5>
                        <p class="card-text h4" id="count-ready">0</p>
                    </div>
                </div>  
        </div>

        <div class="col-md-3">
                <div class="card card-completed" id="card-unclaimed">
                    <div class="card-body">
                        <h5 class="card-title">Unclaimed</h5>
                        <p class="card-text h4" id="count-unclaimed">0</p>
                    </div>
                </div>
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
                                        {{ $order->category && $order->category->status === 'Completed'
                                            ? $order->category->updated_at->format('M d, Y h:i A')
                                            : 'Not yet picked up'
                                        }}
                                    </td>
                                    <td>₱{{ number_format($order->grand_total ?? 0, 2) }}</td>
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const weekInput = document.getElementById('weekSelector');
        const ctx = document.getElementById('weeklyOrdersChart').getContext('2d');
        let chart;

        function fetchAndRenderChart(week = null) {
            let url = '/dashboard/weekly-orders';
            if (week) {
                url += `?week=${week}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    renderPieChart(data.expenseLabels, data.expenseData);

                    if (chart) chart.destroy();
                    chart = new Chart(ctx, {
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
                            maintainAspectRatio: false,
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
        }

        fetchAndRenderChart();

        weekInput.addEventListener('change', function () {
            fetchAndRenderChart(this.value);
        });
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
    let pieChart;

    function renderPieChart(labels, data) {
        const ctx = document.getElementById('pieChart').getContext('2d');

        if (pieChart) pieChart.destroy();

        pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#7FDBFF', '#B10DC9'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const categoryTotals = {};
        @foreach($expenses as $exp)
            categoryTotals["{{ $exp->category }}"] = (categoryTotals["{{ $exp->category }}"] || 0) + {{ $exp->amount }};
        @endforeach

        const pieLabels = Object.keys(categoryTotals);
        const pieData = Object.values(categoryTotals);

        renderPieChart(pieLabels, pieData);
    });
</script>


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
                            <td>${new Date(order.category.updated_at).toLocaleString()}</td>
                            <td>${getActionButtons(order)}</td>
                        </tr>`;
                });

                tableHtml += `</tbody></table>`;

                tableHtml += `</div></div>`;

                document.getElementById('table-container').innerHTML = tableHtml;

                $(`#statusTable`).DataTable({
                    paging: true,
                    pageLength: 15,
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
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Ready for Pickup')"> Mark as Ready for Pickup</button>`;
            } else if (status === 'Ready for Pickup') {
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Completed', this)">Mark as Completed</button>
                        <button class="btn btn-sm btn-danger ms-2" onclick="updateOrderStatus(${order.id}, 'Unclaimed', this)">Mark as Unclaimed</button>`;
            } else if (status === 'Unclaimed') {
                buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Completed')">Mark as Claimed</button>`;
            }

            return buttons || 'No actions available';
        }
    });

    updateCardCounts();

        async function updateCardCounts() {
            const statuses = ['Pending', 'Processing', 'Ready for Pickup', 'Unclaimed'];
            for (const status of statuses) {
                try {
                    const response = await fetch(`/orders/status/${encodeURIComponent(status)}`);
                    if (!response.ok) throw new Error('Network error');
                    const result = await response.json();

                    const count = Array.isArray(result.data) ? result.data.length : 0;

                    const idMap = {
                        'Pending': 'count-pending',
                        'Processing': 'count-processing',
                        'Ready for Pickup': 'count-ready',
                        'Unclaimed': 'count-unclaimed'
                    };

                    const countElement = document.getElementById(idMap[status]);
                    if (countElement) {
                        countElement.textContent = count;
                    }
                } catch (error) {
                    console.error(`Failed to load count for ${status}:`, error);
                }
            }
        }

    function resetView() { buttons = `<button class="btn btn-sm btn-success" onclick="updateOrderStatus(${order.id}, 'Completed', this)">Mark as Completed</button>
                        <button class="btn btn-sm btn-danger ms-2" onclick="updateOrderStatus(${order.id}, 'Unclaimed', this)">Mark as Unclaimed</button>`;
        document.getElementById('table-container').style.display = 'none';
        document.getElementById('defaultView').style.display = 'block';
    }

    async function updateOrderStatus(orderId, newStatus, button) {
        try {

            const button = document.querySelector(`button[onclick*="updateOrderStatus(${orderId}"]`);
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;

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
                
                // Using SweetAlert
                await Swal.fire({
                    icon: 'success',
                    title: `Status Updated`,
                    text: `Order #${orderId} is now marked as "${data.category.status}"`,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Pag fade out
                const row = button.closest('tr');
                if (row) {
                    row.style.transition = 'opacity 0.5s ease-out';
                    row.style.opacity = 0;
                    setTimeout(() => row.remove(), 500);
                }


                const activeStatus = document.querySelector('h5.mb-0')?.textContent?.trim();
                if (activeStatus === `${newStatus} Orders`) {
                loadStatusTable(newStatus);
            }

             updateCardCounts();

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

<script>
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>