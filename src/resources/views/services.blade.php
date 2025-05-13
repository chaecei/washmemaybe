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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

            <form id="fullServiceForm" action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="container-fluid px-0">
                    <div class="row g-0">
                        <!-- Add User Form -->
                        <div class="col-12">
                            <div class="form-container">
                                <h5 class="mb-3">Add Customer Information</h5>
                                <div class="mb-3">
                                    <input type="hidden" id="customer_id" value="1">
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
                            </div>
                        </div>

                        
                        <!-- Order Information -->
                        <div class="col-12 mt-5">
                            <div class="form-container">
                                <h5 class="mb-3">Order Information</h5>
                                <div id="orderContainer"></div>
                                <input type="hidden" name="order_id" id="order_id_payment" />
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-outline-secondary me-2" id="addOrderButton" onclick="addOrder()">+ Add Order</button>
                                    <button type="submit" class="btn btn-pastel" id="submitServiceBtn">Submit</button>

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
                            <input type="number" id="total_load" min="1" required>
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

        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="paymentForm">
                <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Make a Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                @csrf
                <input type="hidden" name="order_id" id="payment_order_id"> <!-- Set this via JS -->

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-select" name="payment_method" required>
                    <option value="gcash">GCash</option>
                    <option value="cash">Cash</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control" required min="1" step="any">
                </div>

                <div id="paymentErrors" class="text-danger mb-2"></div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit Payment</button>
                </div>
            </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div id="orderContainer"></div> <!-- Container to hold all orders -->

<!-- Hidden order template (to be cloned when adding new orders) -->
<template id="orderTemplate">
    <div class="order-block">
        <p>Order #<span class="order-number"></span></p>
        <label>Service Type</label>
        <select class="service-type">
            <option value="full">Full Service</option>
            <option value="partial">Partial Service</option>
        </select>

        <div class="total-load-group hidden">
            <label for="total_load">Total Load</label>
            <input type="number" id="total_load" name="total_load">
        </div>

        <div class="detergent-group hidden">
            <label for="detergent">Detergent</label>
            <input type="text" class="detergent" name="detergent">
        </div>

        <div class="softener-group hidden">
            <label for="softener">Softener</label>
            <input type="text" class="softener" name="softener">
        </div>
    </div>
</template>

<script>
    let orderCount = 1;

    function addOrder() {
        // Add a new order block (clone the template)
        const orderTemplate = document.getElementById("orderTemplate").content.cloneNode(true);
        orderTemplate.querySelector(".order-number").innerText = orderCount;
        orderTemplate.querySelectorAll(".service-type").forEach(option => {
            option.addEventListener('change', function () {
                toggleOrderFields(this.closest('.order-block'));
            });
        });

        document.getElementById('orderContainer').appendChild(orderTemplate);
        orderCount++;

        // Enable the submit button once the first order is added
        document.getElementById("submitServiceBtn").disabled = false;
    }

    function toggleOrderFields(orderBlock) {
        const totalLoadGroup = orderBlock.querySelector(".total-load-group");
        const detergentGroup = orderBlock.querySelector(".detergent-group");
        const softenerGroup = orderBlock.querySelector(".softener-group");

        // Show fields based on the selected service type
        totalLoadGroup.classList.remove('hidden');
        detergentGroup.classList.remove('hidden');
        softenerGroup.classList.remove('hidden');
    }

    $(document).ready(function () {
        // Full service form submission
        $("#fullServiceForm").submit(function (e) {
            e.preventDefault(); // Prevent default form submission

            // Get form data
            var formData = new FormData(this);

            // Loop through order details and append to formData
            const orders = [];
            $(".order-block").each(function () {
                const order = {
                    service_type: $(this).find(".service-type:checked").val(),
                    total_load: $(this).find("#total_load").val(),
                    detergent: $(this).find(".detergent").val(),
                    softener: $(this).find(".softener").val(),
                };
                orders.push(order);
            });
            formData.append("orders", JSON.stringify(orders));

            // Make AJAX request to send the data to the backend
            $.ajax({
                url: '{{ route('order.store') }}', // Ensure the correct route here
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        alert("Orders saved successfully!");
                        $('#payment_order_id').val(response.order_id); // Ensure backend returns order_id

                        // Show the payment modal
                        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                        paymentModal.show();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("AJAX error: " + error);
                }
            });
        });

        // Payment form submission
        $("#paymentForm").submit(function (e) {
            e.preventDefault(); // Prevent default form submission

            // Get form data
            var formData = new FormData(this);

            // Make AJAX request to send the data to the backend
            $.ajax({
                url: '{{ route('payments.store') }}', // Ensure this route is correct
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        alert("Payment saved successfully!");
                        location.reload(); // Reload the page or handle success
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("AJAX error: " + error);
                }
            });
        });
    });
</script>

</body>
</html>
