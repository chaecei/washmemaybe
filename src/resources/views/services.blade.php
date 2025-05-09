<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        background-color: #60B5FF;
        color: #000;
        border: 'none';
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
            <a href="{{ route('expenses') }}" class="nav-link">Expenses</a>
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

    <!-- Account Modal -->
    <div id="servicesModal" class="modal-container">
        <div class="modal-content">

        <form id="fullServiceForm" action="{{ route('payment.store') }}" method="POST">
            @csrf
            <div class="container-fluid px-0">
                <div class="row g-0">
                    <!-- Add User Form -->
                    <div class="col-12">
                        <div class="form-container">
                            <h5 class="mb-3">Add Customer Information</h5>
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
                            <input type="hidden" name="order_id" id="order_id_payment" />
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="addOrder()">+ Add Order</button>
                                <button type="submit" class="btn btn-pastel" id="submitOrderBtn">Submit Service</button>
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

        <!-- Payment Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Payment Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <<form id="paymentForm" action="{{ route('payment.store', ['order' => $order->id]) }}" method="POST">

                        @csrf
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-pastel">Submit Payment</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>


    <script>
        function openPaymentModal(orderId) {
            document.getElementById('payment_order_id').value = orderId;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        // Handle form submit via AJAX
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const orderId = document.getElementById('payment_order_id').value;
            const amount = document.getElementById('payment_amount').value;
            const paymentMethod = document.getElementById('payment_method').value;
            const token = document.querySelector('input[name="_token"]').value;

            fetch(`/payments/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    amount: amount,
                    payment_method: paymentMethod
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closePaymentModal();
                    location.reload(); // Reload to update dashboard and history
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                alert("Payment failed. Check console.");
            });
        });
    </script>



<!-- Include Bootstrap JS and jQuery for Modal Functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>




<script>
    document.getElementById('submitOrderBtn').addEventListener('click', function () {
        const customerId = document.querySelector('[name="customer_id"]').value;
        const totalLoad = document.querySelector('[name="total_load"]').value;

        // Send AJAX request to save the order
        fetch('{{ route('orders.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: customerId,
                total_load: totalLoad
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Store order ID temporarily for payment
                document.getElementById('order_id_payment').value = data.order_id;

                // Show the payment modal
                const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                paymentModal.show();
            } else {
                alert('Something went wrong.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the order.');
        });
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
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('showOrderSummary'))
                let orderModal = new bootstrap.Modal(document.getElementById('orderSummaryModal'));
                orderModal.show();
            @endif
        });

    </script>


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
    document.addEventListener('DOMContentLoaded', function () {
        @if(session()->has('customer') || isset($order))
            var myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            myModal.show();
        @endif
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceForm = document.getElementById('fullServiceForm');
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), { keyboard: false });

        serviceForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop default form submission

            // OPTIONAL: Validate required fields here before showing payment modal
            // Example: Check if orders are filled

            paymentModal.show(); // Show payment modal
        });

        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener('submit', function (e) {
            // Let the payment form submit normally
            // Or you can do AJAX submission here
        });
    });
</script>

<script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json(); // if your controller returns JSON
            } else {
                throw new Error('Payment failed.');
            }
        })
        .then(data => {
            // Success actions here
            alert("Payment successful!");
            location.href = '/dashboard'; // Or wherever you want to redirect
        })
        .catch(error => {
            alert("Payment failed: " + error.message);
        });
    });
</script>


</body>



