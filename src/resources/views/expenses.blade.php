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
        <div class="container mt-4">

            <!-- (1) Add Item Button -->
            <div class="mb-3 text-start"> <!-- Changed text-end to text-start -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>

            <!-- (2) Items Table -->
            <div class="card">
                <div class="card-header">
                    <strong>Item List</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th>Actions</th> <!-- (3) Edit/Delete -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $expense->item_name }}</td>
                                    <td>{{ $expense->price }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-warning edit-button" 
                                            data-id="{{ $expense->id }}" 
                                            data-name="{{ $expense->item_name }}" 
                                            data-price="{{ $expense->price }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editItemModal">
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- (4) Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Use the route for storing a new expense -->
                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf <!-- Include CSRF token for security -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="itemName" name="item_name" maxlength="50" required>
                            </div>
                            <div class="mb-3">
                                <label for="itemPrice" class="form-label">Price</label>
                                <input type="number" class="form-control" id="itemPrice" name="price" min="1" max="1000000" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reusable Edit Item Modal -->
        <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editItemForm" method="POST">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updates -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editItemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="editItemName" name="item_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editItemPrice" class="form-label">Price</label>
                                <input type="number" class="form-control" id="editItemPrice" name="price" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Admin Profile and Header -->
        <div class="admin-profile">
            <span class="status-text">Good Day, Admin!</span>
            <img src="{{ auth()->user()->profile_pictures ? asset('profile_pictures/' . auth()->user()->profile_pictures) : asset('images/admin_icon.jpg') }}" alt="Admin" class="admin-img">
        </div>

        <div class="header">
            <img src="{{ asset('images/title.png') }}" alt="Header Image" class="header-image">
        </div>
    </div>

    <!-- Bootstrap JS (for modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function populateEditModal(actionUrl, itemName, itemPrice) {
            const form = document.getElementById('editItemForm');
            form.action = actionUrl;
            document.getElementById('editItemName').value = itemName;
            document.getElementById('editItemPrice').value = itemPrice;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-button');
            const editItemModal = document.getElementById('editItemModal');
            const editItemForm = document.getElementById('editItemForm');
            const editItemName = document.getElementById('editItemName');
            const editItemPrice = document.getElementById('editItemPrice');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Get data attributes from the clicked button
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const price = this.getAttribute('data-price');

                    // Populate the modal fields
                    editItemName.value = name;
                    editItemPrice.value = price;

                    // Update the form action dynamically
                    editItemForm.action = `/expenses/${id}`;
                });
            });
        });
    </script>
</body>
</html>
