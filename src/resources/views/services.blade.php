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
        margin-top: 40px;
        }
        .form-container1 {
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
                                    <input
                                        type="text"
                                        name="mobile_number"
                                        id="mobile_number"
                                        class="form-control"
                                        placeholder="09xxxxxxxxx"
                                        maxlength="11"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        
                        <!-- Order Information -->
                        <div class="col-12 mt-5">
                            <div class="form-container1">
                                <h5 class="mb-3">Order Information</h5>
                                <div id="orderContainer"></div>
                                <input type="hidden" name="order_id" id="order_id_payment" />
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-outline-secondary me-2" id="addOrderButton" onclick="addOrder()">+ Add Order</button>
                                    <button type="submit" class="btn btn-pastel" id="submitServiceBtn" disabled>Submit</button>
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
                                        <input class="form-check-input service-type" name="service_type" type="radio" value="Wash and Fold" />
                                        <label class="form-check-label">Wash and Fold</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input service-type" name="service_type" type="radio" value="Wash and Iron" />
                                        <label class="form-check-label">Wash and Iron</label>
                                    </div>
                                </div>


                                <!-- Total Load -->
                                <div class="mb-3 hidden total-load-group">
                                    <label class="form-label">Total Load:</label>
                                    <input type="number" name="total_load" class="form-control total_load" min="1" required>
                                </div>

                                <!-- Detergent Preferences -->
                                <div class="mb-3 hidden detergent-group">
                                    <label class="form-label">Detergent Preference:</label>
                                    <select name="detergent" class="form-select detergent">
                                        <option disabled selected>Select detergent type</option>
                                        <option>Regular Detergent</option>
                                        <option>Hypoallergenic</option>
                                        <option>Customer Provided</option>
                                    </select>
                                </div>

                                <!-- Fabric Softener Options -->
                                <div class="mb-3 hidden softener-group">
                                    <label class="form-label">Fabric Softener:</label>
                                    <select name="softener" class="form-select softener">
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
                                <option value="cash">Cash</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" required min="1" step="any">
                        </div>

                        <!-- Order Summary -->
                        <div class="mb-3" id="paymentModalBody">
                            <strong>Name:</strong> <span id="paymentName"></span><br>
                            <strong>Mobile Number:</strong> <span id="paymentMobile"></span><br>
                            <br>

                            <div id="paymentOrderItems" class="mb-3">
                                <strong>Total Load:</strong> <span id="paymentTotalLoad"></span><br>
                                <strong>Detergent:</strong> <span id="paymentDetergent"></span><br>
                                <strong>Softener:</strong> <span id="paymentSoftener"></span><br>
                            </div>

                            <div id="paymentOrderSummary" class="mb-3"></div>
                                <strong>Grand Total:</strong> <span id="paymentGrandTotal">₱10.00</span><br>
                        </div>

                        <div id="paymentErrors" class="text-danger mb-2"></div>
                    </div>

                    <div class="modal-footer">
                        <button id="submitPaymentButton" type="submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span class="btn-text">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    const input = document.getElementById("mobile_number");

    // Set default value
    input.value = "09";

    // Prevent deletion of the default "09"
    input.addEventListener("keydown", function (e) {
        // Prevent deleting or moving cursor before index 2
        if ((input.selectionStart <= 2 && (e.key === "Backspace" || e.key === "Delete")) || 
            (e.key === "ArrowLeft" && input.selectionStart <= 2)) {
            e.preventDefault();
        }
    });

    // Prevent editing before index 2
    input.addEventListener("keypress", function (e) {
        if (input.selectionStart < 2) {
            e.preventDefault();
        }

        // Prevent typing more than 9 digits after "09"
        if (input.value.length >= 11) {
            e.preventDefault();
        }

        // Only allow numeric characters
        if (!/\d/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Ensure value always starts with "09"
    input.addEventListener("input", function () {
        if (!input.value.startsWith("09")) {
            input.value = "09";
        }
    });
</script>

<script>
    function addOrder() {
        const orderContainer = document.getElementById('orderContainer');
        const orderTemplate = document.getElementById('orderTemplate');
        const orderClone = orderTemplate.content.cloneNode(true);
        
        // Append the new order block
        orderContainer.appendChild(orderClone);

        // Update the order number
        const orderNumber = orderContainer.querySelectorAll('.order-block').length;
        orderContainer.querySelectorAll('.order-number').forEach((element, index) => {
            element.textContent = orderNumber;
        });

        // Enable submit button if all fields are filled
        checkFormValidity();
    }

    function checkFormValidity() {
        const submitButton = document.getElementById('submitServiceBtn');
        const orders = document.querySelectorAll('.order-block');
        let allFieldsFilled = true;

        orders.forEach(order => {
            const serviceType = order.querySelectorAll('.service-type:checked').length > 0;
            const totalLoad = order.querySelector('.total_load')?.value;
            const detergent = order.querySelector('.detergent')?.value;
            const softener = order.querySelector('.softener')?.value;

            if (!serviceType || !totalLoad || !detergent || !softener) {
                allFieldsFilled = false;
            }
        });

        // Enable submit button if all fields are filled, else disable it
        submitButton.disabled = !allFieldsFilled;
    }

    // Add event listeners to check form validity whenever a field is changed
    document.getElementById('orderContainer').addEventListener('input', checkFormValidity);
    document.getElementById('orderContainer').addEventListener('change', checkFormValidity);
</script>

<script>
    let orderCount = 1;

    
    function addOrder() {
        const orderTemplate = document.getElementById("orderTemplate").content.cloneNode(true);
        const orderBlock = orderTemplate.querySelector(".order-block");

        // Set the order number
        orderBlock.querySelector(".order-number").innerText = orderCount;

        // Update all radio inputs to share the same 'name' so only one can be selected per order
        const serviceTypeInputs = orderBlock.querySelectorAll(".service-type");
        serviceTypeInputs.forEach((input, index) => {
            input.name = `service_type_${orderCount}`; // unique name per order
        });

        // Add change listener to show fields when a service type is selected
        serviceTypeInputs.forEach(input => {
            input.addEventListener("change", function () {
                toggleOrderFields(orderBlock);
            });
        });

        // Append the new order
        document.getElementById("orderContainer").appendChild(orderTemplate);

        // Enable submit button after adding at least one order
        document.getElementById("submitServiceBtn").disabled = false;

        orderCount++;
    }


    function toggleOrderFields(orderBlock) {
        const totalLoadGroup = orderBlock.querySelector(".total-load-group");
        const detergentGroup = orderBlock.querySelector(".detergent-group");
        const softenerGroup = orderBlock.querySelector(".softener-group");

        totalLoadGroup.classList.remove('hidden');
        detergentGroup.classList.remove('hidden');
        softenerGroup.classList.remove('hidden');
    }

    $(document).ready(function () {
        $("#fullServiceForm").submit(function (e) {
            e.preventDefault();
            
            // Collect customer info
            const customerInfo = {
                first_name: $("input[name='first_name']").val(),
                last_name: $("input[name='last_name']").val(),
                mobile_number: $("input[name='mobile_number']").val()
            };

            // Collect order items
            const orders = [];
            let hasErrors = false;

            $(".order-block").each(function() {
                const orderBlock = $(this);
                const orderData = {
                    service_type: orderBlock.find(".service-type:checked").val(),
                    total_load: orderBlock.find(".total_load").val(),
                    detergent: orderBlock.find(".detergent").val(),
                    softener: orderBlock.find(".softener").val()
                };

                // Validate required fields
                if (!orderData.service_type || !orderData.total_load || !orderData.detergent || !orderData.softener) {
                    hasErrors = true;
                    return false; // Exit the loop
                }

                orders.push(orderData);
            });

            if (hasErrors) {
                alert("Please complete all order fields");
                return;
            }

            // Display basic info in modal immediately
            updatePaymentModal(customerInfo, orders);

            // Show the modal
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();

            // Calculate prices via AJAX
            calculatePrices(customerInfo, orders);
        });

        function updatePaymentModal(customerInfo, orders) {
            // Update customer info
            $("#paymentName").text(`${customerInfo.first_name} ${customerInfo.last_name}`);
            $("#paymentMobile").text(customerInfo.mobile_number);

            // Update order items
            let orderItemsHTML = '';
            orders.forEach((order, index) => {
                orderItemsHTML += `
                    <div class="order-item mb-3">
                        <h6>Order ${index + 1}</h6>
                        <p><strong>Service:</strong> ${order.service_type || 'Not specified'}</p>
                        <p><strong>Load:</strong> ${order.total_load || '0'}</p>
                        <p><strong>Detergent:</strong> ${order.detergent || 'Not specified'}</p>
                        <p><strong>Softener:</strong> ${order.softener || 'Not specified'}</p>
                    </div>
                `;
            });

            $("#paymentOrderItems").html(orderItemsHTML);
            $("#paymentGrandTotal").text("Calculating...");
        }

        function calculatePrices(customerInfo, orders) {
            $.ajax({
                url: '/order/calculate-prices',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    orders: orders
                },
                success: function(response) {
                    if (response.success) {
                        // Update the modal display
                        updatePriceDisplay(response);
                    } else {
                        showCalculationError(response.message);
                    }
                },
                error: function(xhr) {
                    showCalculationError(xhr.responseJSON?.message || "Server error");
                }
            });
        }

        function updatePriceDisplay(response) {
            // Update grand total
            $("#paymentGrandTotal").text(`₱${response.grand_total.toFixed(2)}`);
            $("input[name='amount']").val(response.grand_total.toFixed(2));

            // Update each order item with prices
            response.order_items.forEach((item, index) => {
                const orderElement = $(`#paymentOrderItems .order-item:nth-child(${index + 1})`);
                
                orderElement.find('.price-details').remove(); // Clear previous prices
                
                orderElement.append(`
                    <div class="price-details mt-2">
                        <p><strong>Load Price:</strong> ₱${item.total_load_price.toFixed(2)}</p>
                        <p><strong>Detergent:</strong> ₱${item.detergent_price.toFixed(2)}</p>
                        <p><strong>Softener:</strong> ₱${item.softener_price.toFixed(2)}</p>
                    </div>
                `);
            });

            // Enable the Submit Payment button
            $("#submitPaymentButton").prop('disabled', false);
        }

        // Use the existing "Submit Payment" button to save the order to the database
        $("#submitPaymentButton").click(function (e) {
            e.preventDefault();


            $(this).prop("disabled", true);

            const customerInfo = {
                first_name: $("input[name='first_name']").val(),
                last_name: $("input[name='last_name']").val(),
                mobile_number: $("input[name='mobile_number']").val()
            };

            const orders = [];
            $(".order-block").each(function () {
                const orderBlock = $(this);
                const orderData = {
                    service_type: orderBlock.find(".service-type:checked").val(),
                    total_load: parseInt(orderBlock.find(".total_load").val()) || 0,
                    detergent: orderBlock.find(".detergent").val(),
                    softener: orderBlock.find(".softener").val(),
                    total_load_price: parseFloat(orderBlock.find(".total_load_price").val()) || 0,
                    detergent_price: parseFloat(orderBlock.find(".detergent_price").val()) || 0,
                    softener_price: parseFloat(orderBlock.find(".softener_price").val()) || 0
                };

                orders.push(orderData);
            });

            $.ajax({
                url: '{{ route("order.save") }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    customer_info: customerInfo,
                    orders: orders,
                    grand_total: $("input[name='amount']").val()
                }),
                success: function (response) {
                    if (response.success) {
                        $("#paymentModal").modal('hide');
                        alert("Order saved successfully!");
                        window.location.href = "{{ route('dashboard') }}"; 
                    } else {
                        alert("Failed to save the order."); 
                        $("#submitPaymentButton").prop('disabled', false);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("Error saving the order.");
                    $("#submitPaymentButton").prop('disabled', false);
                },
            });
        });
        function showCalculationError(message) {
            $("#paymentErrors").text(message);
        }
    });


</script>


</body>
</html>
