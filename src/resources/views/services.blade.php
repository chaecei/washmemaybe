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
        body {
        background-color: #f9f7f3;
        font-family: 'Segoe UI', sans-serif;
        }

        .form-container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
        }

        .order-block {
        background-color: #E8F9FF;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        .btn-pastel {
        background-color: #AFDDFF;
        color: #000;
        border: none;
        }

        .btn-pastel:hover {
        background-color: #3D90D7;
        color: #000;
        }

        .form-label {
        font-weight: 600;
        }

        .hidden {
        display: none;
        }

        .fade-in {
        animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
        }

    </style>

    <div class="sidebar">
        <ul>
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('services') }}" class="nav-link services-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
            <li><a href="redirect(history.html)" class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a></li>
            <li><a href="redirect(expenses.html)" class="nav-link {{ request()->routeIs('expenses') ? 'active' : '' }}">Expenses</a></li>
            <li><a href="{{ route('notifications') }}" class="nav-link {{ request()->routeIs('notifications') ? 'active' : '' }}">Notification</a></li>
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

    <!-- Account Modal -->
    <div id="servicesModal" class="modal-container">
        <div class="modal-content">

        <form id="fullServiceForm" action="{{ route('service.store') }}" method="POST">
            @csrf
            <div class="container-fluid px-0">
                <div class="row g-0">
                    <!-- Add User Form -->
                    <div class="col-12">
                        <div class="form-container">
                            <h5 class="mb-3">Add User</h5>
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="Enter name" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Enter name" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" placeholder="09xxxxxxxxx" maxlength="11" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required />
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="col-12 mt-5">
                        <div class="form-container">
                            <h5 class="mb-3">Order Information</h5>
                            <div id="orderContainer"></div>
                            <input type="hidden" name="orders[]" id="orderData" />
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="addOrder()">+ Add Order</button>
                                <button type="submit" class="btn btn-pastel">Submit Service</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Template -->
            <template id="orderTemplate">
                <div class="order-block fade-in mb-4">
                    <h6 class="mb-3">Order <span class="order-number"></span></h6>

                    <!-- Service Type -->
                    <div class="mb-3">
                        <label class="form-label">Service Type:</label><br />
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-type" type="radio" value="Wash and Fold" />
                            <label class="form-check-label">Wash and Fold</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-type" type="radio" value="Wash and Iron" />
                            <label class="form-check-label">Wash and Iron</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input service-type" type="radio" value="Dry Clean" />
                            <label class="form-check-label">Dry Clean</label>
                        </div>
                    </div>

                    <!-- Total Load -->
                    <div class="mb-3 hidden total-load-group">
                        <label class="form-label">Total Load:</label>
                        <input type="number" class="form-control total-load" placeholder="Enter load" />
                    </div>

                    <!-- Detergent Preferences -->
                    <div class="mb-3 hidden detergent-group">
                        <label class="form-label">Detergent Preference:</label>
                        <select class="form-select detergent">
                            <option disabled selected>Select detergent type</option>
                            <option>Regular Detergent</option>
                            <option>Hypoallergenic</option>
                            <option>Customer Provided</option>
                        </select>
                    </div>

                    <!-- Fabric Softener Options -->
                    <div class="mb-3 hidden softener-group">
                        <label class="form-label">Fabric Softener:</label>
                        <select class="form-select softener">
                            <option disabled selected>Select softener type</option>
                            <option>No Softener</option>
                            <option>Regular</option>
                            <option>Floral</option>
                            <option>Baby Powder</option>
                            <option>Unscented</option>
                        </select>
                    </div>
                </div>
            </template>
        </form>


<!-- Service Order Details -->
<div class="container">
    <h2>Service Order Submitted</h2>
    <p>Service Number: {{ $order->id }}</p>
    <p>Status: {{ $order->category->name ?? 'Unknown' }}</p>
    <p>Total Load: {{ $order->total_load }} kg</p>
    <p>Service Type: {{ $order->service_type }}</p>
    <p>Grand Total: ₱{{ number_format($order->total_load * 50, 2) }}</p> <!-- Assuming ₱50 per load -->

    <!-- Button to trigger the payment modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">
        Proceed to Payment
    </button>
</div>

<!-- Include Bootstrap JS and jQuery for Modal Functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal Script -->
<script>
    $(document).ready(function(){
        // Dynamically create the modal
        const modalHTML = `
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Payment for Service Order #{{ $order->id }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Payment form -->
                            <form action="{{ route('payment.store', ['order' => $order->id]) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Online Transfer">Online Transfer</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control" value="₱{{ number_format($order->total_load * 50, 2) }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="transaction_id">Transaction ID (Optional)</label>
                                    <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction ID if available">
                                </div>

                                <button type="submit" class="btn btn-success">Submit Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(modalHTML);

        // Activate Bootstrap Modal
        $('#paymentModal').modal('show');
    });
</script>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <script>
        let orderCount = 0;

        function addOrder() {
            orderCount++;

            const template = document.querySelector("#orderTemplate").content.cloneNode(true);
            const orderBlock = template.querySelector(".order-block");

            // Set order number
            orderBlock.querySelector(".order-number").textContent = orderCount;

            // Give unique names or classes if needed (optional)
            // Add logic to show/hide groups based on service type
            const serviceTypes = orderBlock.querySelectorAll(".service-type");
            const totalLoadGroup = orderBlock.querySelector(".total-load-group");
            const detergentGroup = orderBlock.querySelector(".detergent-group");
            const softenerGroup = orderBlock.querySelector(".softener-group");

            serviceTypes.forEach(radio => {
                radio.name = `service_type_${orderCount}`;
                radio.addEventListener("change", function () {
                    totalLoadGroup.classList.remove("hidden");
                    detergentGroup.classList.remove("hidden");
                    softenerGroup.classList.remove("hidden");
                });
            });

            document.querySelector("#orderContainer").appendChild(orderBlock);
        }

        // Handle form submission
        document.getElementById("fullServiceForm").addEventListener("submit", function (e) {
            const orderBlocks = document.querySelectorAll(".order-block");
            const orders = [];

            orderBlocks.forEach(block => {
                const serviceType = block.querySelector(".service-type:checked")?.value;
                const totalLoad = block.querySelector(".total-load").value;
                const detergent = block.querySelector(".detergent").value;
                const softener = block.querySelector(".softener").value;

                if (serviceType) {
                    orders.push({
                        service_type: serviceType,
                        total_load: totalLoad,
                        detergent: detergent,
                        softener: softener
                    });
                }
            });

            // Save orders as JSON into hidden input
            document.getElementById("orderData").value = JSON.stringify(orders);
        });
    </script>


    <script>
        function openServicesModal() {
            document.getElementById('servicesModal').classList.add('active');
        }

        function closeServicesModal() {
            document.getElementById('servicesModal').classList.remove('active');
        }
    </script>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        let orderCount = 0;
        const orderContainer = document.getElementById('orderContainer');
        const template = document.getElementById('orderTemplate');

        // Add first order by default
        addOrder();

        // Add order button
        document.getElementById('addOrderBtn').addEventListener('click', addOrder);

        // Submit form
        document.getElementById('submitOrder').addEventListener('click', submitOrder);

        function addOrder() {
            orderCount++;
            const clone = template.content.cloneNode(true);
            clone.querySelector('.order-number').textContent = orderCount;
            
            // Remove order button
            clone.querySelector('.remove-order').addEventListener('click', function() {
                if (orderCount > 1) {
                    this.closest('.order-card').remove();
                    orderCount--;
                } else {
                    alert('At least one order is required');
                }
            });

            orderContainer.appendChild(clone);
        }

        async function submitOrder() {
            const customerForm = document.getElementById('customerForm');
            const formData = new FormData(customerForm);

            // Validate customer info
            if (!formData.get('first_name') || !formData.get('mobile_number')) {
                alert('Please fill in required customer fields');
                return;
            }

            try {
                const response = await fetch("{{ route('service.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer: Object.fromEntries(formData),
                        orders: getOrderData()
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Order saved successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save order'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Network error - please try again');
            }
        }

        function getOrderData() {
            return Array.from(document.querySelectorAll('.order-card')).map(card => ({
                service_type: card.querySelector('[name*="service_type"]').value,
                total_load: parseFloat(card.querySelector('[name*="total_load"]').value),
                detergent: card.querySelector('[name*="detergent"]').value,
                softener: card.querySelector('[name*="softener"]').value
            }));
        }
    });

</script>


