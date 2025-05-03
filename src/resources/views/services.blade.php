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
        background-color: #fef6f9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        .btn-pastel {
        background-color: #ffc8dd;
        color: #000;
        border: none;
        }

        .btn-pastel:hover {
        background-color: #ffb3cd;
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

            <div class="container py-4">
                <div class="row g-4">
                    <!-- Add User Form -->
                    <div class="col-md-4">
                        <div class="form-container">
                            <h5 class="mb-3">Add User</h5>
                            <form action="{{ route('storeCustomer') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" placeholder="Enter name" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" placeholder="09xxxxxxxxx" maxlength="11" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" placeholder="email@example.com" />
                                </div>
                                <button type="submit" class="btn btn-pastel w-100">Save User</button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="col-md-8">
                        <div class="form-container">
                            <h5 class="mb-3">Order Information</h5>
                            <form action="{{ route('storeOrder') }}" method="POST">
                                @csrf
                                <div id="orderContainer"></div>
                                <input type="hidden" name="orders[]" id="orderData" />
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="addOrder()">+ Add Another Order</button>
                                    <button type="submit" class="btn btn-pastel">Submit Order</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <template id="orderTemplate">
                <div class="order-block fade-in">
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
                        <label class="form-label">Total Load (kg):</label>
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
        </div>
    </div>

    <script>
        let orderCount = 0;

        function addOrder() {
            orderCount++;
            const container = document.getElementById('orderContainer');
            const template = document.getElementById('orderTemplate');
            const clone = template.content.cloneNode(true);

            const orderBlock = clone.querySelector('.order-block');
            const serviceRadios = clone.querySelectorAll('.service-type');
            const totalLoadGroup = clone.querySelector('.total-load-group');
            const detergentGroup = clone.querySelector('.detergent-group');
            const softenerGroup = clone.querySelector('.softener-group');
            const orderNumberSpan = clone.querySelector('.order-number');

            orderNumberSpan.textContent = orderCount;

            // Unique radio group per order
            serviceRadios.forEach((radio) => {
            radio.name = `serviceType${orderCount}`;
            radio.addEventListener('change', () => {
                totalLoadGroup.classList.remove('hidden');
            });
            });

            clone.querySelector('.total-load').addEventListener('input', function () {
            if (this.value > 0) {
                detergentGroup.classList.remove('hidden');
            } else {
                detergentGroup.classList.add('hidden');
                softenerGroup.classList.add('hidden');
            }
            });

            clone.querySelector('.detergent').addEventListener('change', () => {
            softenerGroup.classList.remove('hidden');
            });

            container.appendChild(clone);
        }

        // Load first order by default
        window.onload = addOrder;
    </script>

    <script>
        function openServicesModal() {
            document.getElementById('servicesModal').classList.add('active');
        }

        function closeServicesModal() {
            document.getElementById('servicesModal').classList.remove('active');
        }
    </script>




