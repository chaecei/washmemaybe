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

    <div id="dynamicContent" class="d-flex flex-grow-1 gap-3">
        <div class="card flex-grow-1 me-4">
            <div class="card-header">
                <h5 class="mb-0">Orders</h5>
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
                              <tr>
                                  <td>{{ $order->id }}</td>
                                  <td>{{ $order->category->name ?? 'Pending' }}</td>
                                  <td>{{ $order->picked_up_at ? $order->picked_up_at->format('M d, Y h:i A') : 'Not yet picked up' }}</td>
                                  <td>
                                      @php
                                          $pricePerLoad = 50; // you can later move this to config or DB
                                          $grandTotal = $order->total_load * $pricePerLoad;
                                      @endphp
                                      ₱{{ number_format($grandTotal, 2) }}
                                  </td>
                              </tr>
                            @endforeach
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
  // Handle status changes from other tables
  function updateDashboard(orderData) {
      const row = document.querySelector(`tr[data-order-id="${orderData.id}"]`);
      
      if (!row) {
          // Add new row at top if not exists
          const tbody = document.querySelector('#ordersTable tbody');
          tbody.insertAdjacentHTML('afterbegin', `
              <tr data-order-id="${orderData.id}">
                  <td>${orderData.service_number}</td>
                  <td><span class="status-badge badge-${orderData.status.replace(' ', '')}">
                      ${orderData.status.charAt(0).toUpperCase() + orderData.status.slice(1)}
                  </span></td>
                  <td>${new Date(orderData.updated_at).toLocaleString()}</td>
                  <td>${orderData.picked_up_at ? new Date(orderData.picked_up_at).toLocaleString() : '-'}</td>
                  <td>₱${orderData.grand_total.toFixed(2)}</td>
              </tr>
          `);
      } else {
          // Update existing row
          row.querySelector('.status-badge').className = `status-badge badge-${orderData.status.replace(' ', '')}`;
          row.querySelector('.status-badge').textContent = 
              orderData.status.charAt(0).toUpperCase() + orderData.status.slice(1);
          row.cells[2].textContent = new Date(orderData.updated_at).toLocaleString();
          row.cells[3].textContent = orderData.picked_up_at ? 
              new Date(orderData.picked_up_at).toLocaleString() : '-';
      }
  }

  // Listen for updates (using Pusher or Echo for real-time)
  Echo.channel('order-updates')
      .listen('OrderStatusUpdated', (data) => {
          updateDashboard(data.order);
      });
</script>

<script>
  async function updateOrderStatus(orderId, newStatus) {
      try {
          const response = await fetch(`/orders/${orderId}/status`, {
              method: 'PUT',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({ status: newStatus })
          });

          const data = await response.json();
          
          if (data.success) {
              // Update UI or let the real-time event handle it
              console.log('Status updated:', data.order);
          }
      } catch (error) {
          console.error('Error:', error);
      }
  }

  // Example usage (attach this to your status dropdowns):
  document.querySelectorAll('.status-dropdown').forEach(dropdown => {
      dropdown.addEventListener('change', function() {
          const orderId = this.dataset.orderId;
          const newStatus = this.value;
          updateOrderStatus(orderId, newStatus);
      });
  });
</script>

<script>
  window.Echo.channel('order-updates')
    .listen('OrderStatusUpdated', (data) => {
        // Find and update the row or prepend a new one
        const row = document.querySelector(`tr[data-order-id="${data.order.id}"]`);
        
        if (row) {
            // Update existing row
            row.querySelector('.status-badge').className = `status-badge badge-${data.order.status}`;
            row.querySelector('.status-badge').textContent = data.order.status;
            row.cells[2].textContent = new Date(data.order.updated_at).toLocaleString();
            row.cells[3].textContent = data.order.picked_up_at 
                ? new Date(data.order.picked_up_at).toLocaleString() 
                : '-';
        } else {
            // Add new row at top
            document.querySelector('#ordersTable tbody').insertAdjacentHTML('afterbegin', `
                <tr data-order-id="${data.order.id}">
                    <td>${data.order.service_number}</td>
                    <td><span class="status-badge badge-${data.order.status}">
                        ${data.order.status}
                    </span></td>
                    <td>${new Date(data.order.updated_at).toLocaleString()}</td>
                    <td>${data.order.picked_up_at ? new Date(data.order.picked_up_at).toLocaleString() : '-'}</td>
                    <td>₱${data.order.grand_total.toFixed(2)}</td>
                </tr>
            `);
        }
    });
</script>
  <!-- Pending Card Table -->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const cardPending = document.querySelector('.card-pending');

    if (!cardPending) {
      console.error("'.card-pending' element not found.");
      return;
    }

    cardPending.addEventListener('click', function () {
      fetch('/category/pending') // Relative to current dashboard route
        .then(response => {
          if (!response.ok) throw new Error('Network error');
          return response.json();
        })
        .then(data => {
          const container = document.getElementById('dynamicContent');
          if (!container) {
            console.error("'#dynamicContent' container not found.");
            return;
          }

          if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = "<p>No pending orders found.</p>";
            return;
          }

          let rows = '';
          data.forEach(category => {

            const createdAt = new Date(category.created_at);
            const updatedAt = new Date(category.updated_at);

            const formattedCreatedAt = formatDate(createdAt);
            const formatUpdatedAt = formatDate(updatedAt);

            rows += `
              <tr>
                <td>${category.service_number}</td>
                <td><span class="badge bg-warning text-dark">${category.status}</span></td>
                <td>₱${parseFloat(category.grand_total).toFixed(2)}</td>
                <td>${category.created_at ?? 'N/A'}</td>
                <td>${category.updated_at ?? 'N/A'}</td>
                <td><button class="btn btn-sm btn-primary move-to-processing" data-id="${category.id}">Move to Processing</button></td>
              </tr>
            `;
          });

          container.innerHTML = `
            <div class="card flex-grow-1">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Orders Table</h5>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
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
                  <tbody>
                    ${rows}
                  </tbody>
                </table>
              </div>
            </div>
          `;

          // const moveButtons = document.querySelectorAll('.move-to-processing');
          // moveButtons.forEach(button => {
          //   button.addEventListener('click', function () {
          //     const orderId = this.getAttribute('data-id');
          //     const row = this.closest('tr');
          //     const processingTableBody = document.querySelector('#processingTable tbody');

          //     fetch(`/category/move-to-processing/${orderId}`, {
          //       method: 'PATCH',
          //       headers: {
          //         'Content-Type': 'application/json',
          //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          //       },
          //     })
          //     .then(response => response.json())
          //     .then(result => {
          //       if(result.success) {
          //         processingTableBody.appendChild(row);

          //         row.querySelector('span.badge').textContent = 'Processing';
          //         row.querySelector('span.badge').className = 'badge bg-info text-dark';

          //         this.disabled = true;
          //         this.textContent = 'Already in Processing';
          //       } else {
          //         console.error('Failed to move order to processing:', result.message);
          //       }
          //     })
          //     .catch(error => console.error('Error moving order to processing:', error));
          //   });
          // });
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });

        function formatDate(date) {
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const day = String(date.getDate()).padStart(2, '0');
          const year = String(date.getFullYear()).slice(-2);
          const hours = String(date.getHours()).padStart(2, '0');
          const minutes = String(date.getMinutes()).padStart(2, '0');

          return `${month}/${day}/${year} ${hours}:${minutes}`;
        }
    });
  });
</script>


<!-- Processing Card Table -->
<script>

  document.addEventListener('DOMContentLoaded', function () {
    const cardProcessing = document.querySelector('.card-processing');

    if (!cardProcessing) {
      console.error("'.card-processing' element not found.");
      return;
    }

    cardProcessing.addEventListener('click', function () {
      fetch('/category/processing') // Calls the route for processing status
        .then(response => {
          if (!response.ok) throw new Error('Network error');
          return response.json();
        })
        .then(data => {
          const container = document.getElementById('dynamicContent');
          if (!container) {
            console.error("'#dynamicContent' container not found.");
            return;
          }

          if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = "<p>No processing orders found.</p>";
            return;
          }

          let rows = '';
          data.forEach(category => {
            rows += `
              <tr>
                <td>${category.service_number}</td>
                <td><span class="badge bg-info text-dark">${category.status}</span></td>
                <td>₱${parseFloat(category.grand_total).toFixed(2)}</td>
                <td>${category.created_at ?? 'N/A'}</td>
                <td>${category.updated_at ?? 'N/A'}</td>
                <td><button class="btn btn-sm btn-success move-to-ready" data-id="${category.id}">Move to Ready for Pickup</button></td>
              </tr>
            `;
          });

          container.innerHTML = `
            <div class="card flex-grow-1">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Processing Orders Table</h5>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
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
                  <tbody>
                    ${rows}
                  </tbody>
                </table>
              </div>
            </div>
          `;

          // Optional: Event listener for the move-to-ready button (you can wire this up later)
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    });
  });
</script>

</script>

<!-- Ready for Pickup Card Table -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const cardReady = document.querySelector('.card-ready');

    if (!cardReady) {
      console.error("'.card-ready' element not found.");
      return;
    }

    cardReady.addEventListener('click', function () {
      fetch('/category/ready for pickup') // Calls the route with the status
        .then(response => {
          if (!response.ok) throw new Error('Network error');
          return response.json();
        })
        .then(data => {
          const container = document.getElementById('dynamicContent');
          if (!container) {
            console.error("'#dynamicContent' container not found.");
            return;
          }

          if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = "<p>No ready for pickup orders found.</p>";
            return;
          }

          let rows = '';
          data.forEach(category => {
            rows += `
              <tr>
                <td>${category.service_number}</td>
                <td><span class="badge bg-success">${category.status}</span></td>
                <td>₱${parseFloat(category.grand_total).toFixed(2)}</td>
                <td>${category.created_at ?? 'N/A'}</td>
                <td>${category.updated_at ?? 'N/A'}</td>
                <td><button class="btn btn-sm btn-dark" disabled>Move to Unclaimed</button></td>
              </tr>
            `;
          });

          container.innerHTML = `
            <div class="card flex-grow-1">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ready for Pickup Table</h5>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
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
                  <tbody>
                    ${rows}
                  </tbody>
                </table>
              </div>
            </div>
          `;
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    });
  });
</script>

</script>

<script>
  document.querySelector('.card-completed').addEventListener('click', function () {
    const container = document.getElementById('dynamicContent');
    container.innerHTML = `
      <div class="card flex-grow-1">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Unclaimed Orders Table</h5>
          <button class="btn btn-sm btn-secondary" onclick="location.reload()">Back</button>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>Service Number</th>
                <th>Status</th>
                <th>Grand Total</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Days Overdue</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>WIN022</td>
                <td><span class="badge bg-danger">Unclaimed</span></td>
                <td>₱220.00</td>
                <td>2025-04-25 10:10</td>
                <td>2025-04-26 08:00</td>
                <td>8</td> <!-- Days Overdue -->
              </tr>
              <tr>
                <td>WIN023</td>
                <td><span class="badge bg-danger">Unclaimed</span></td>
                <td>₱350.00</td>
                <td>2025-04-24 12:30</td>
                <td>2025-04-25 14:00</td>
                <td>9</td> <!-- Days Overdue -->
              </tr>
              <tr>
                <td>WIN024</td>
                <td><span class="badge bg-danger">Unclaimed</span></td>
                <td>₱180.00</td>
                <td>2025-04-23 11:45</td>
                <td>2025-04-24 09:15</td>
                <td>10</td> <!-- Days Overdue -->
              </tr>
              <tr>
                <td>WIN025</td>
                <td><span class="badge bg-danger">Unclaimed</span></td>
                <td>₱290.00</td>
                <td>2025-04-22 15:30</td>
                <td>2025-04-23 17:00</td>
                <td>11</td> <!-- Days Overdue -->
              </tr>
              <tr>
                <td>WIN026</td>
                <td><span class="badge bg-danger">Unclaimed</span></td>
                <td>₱410.00</td>
                <td>2025-04-20 13:00</td>
                <td>2025-04-21 10:00</td>
                <td>13</td> <!-- Days Overdue -->
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    `;
  });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
