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
        <li class="{{ request()->routeIs('expenses.index') ? 'active' : '' }}">
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

      <!-- Content Wrapper -->
    <div class="content-wrapper px-4 py-5">
        <h2 class="mb-4">Laundry Shop Expenses</h2>

        {{-- CREATE & EDIT FORM --}}
        @if($mode === 'create' || $mode === 'edit')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $mode === 'create' ? 'Add New Expense' : 'Edit Expense' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $mode === 'create' ? route('expenses.store') : route('expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @if($mode === 'edit') @method('PUT') @endif

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" id="date"
                                value="{{ old('date', $expense->date ?? now()->format('Y-m-d')) }}" required>
                            @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- CATEGORY DROPDOWN -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="" disabled {{ old('category', $expense->category ?? '') ? '' : 'selected' }}>Select category</option>
                                <option value="utilities" {{ old('category', $expense->category ?? '') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="washing supplies" {{ old('category', $expense->category ?? '') == 'washing supplies' ? 'selected' : '' }}>Washing Supplies</option>
                                <option value="equipment maintenance and repairs" {{ old('category', $expense->category ?? '') == 'equipment maintenance and repairs' ? 'selected' : '' }}>Equipment Maintenance and Repairs</option>
                                <option value="salaries and wages" {{ old('category', $expense->category ?? '') == 'salaries and wages' ? 'selected' : '' }}>Salaries and Wages</option>
                                <option value="cleaning supplies" {{ old('category', $expense->category ?? '') == 'cleaning supplies' ? 'selected' : '' }}>Cleaning Supplies</option>
                                <option value="other business expenses" {{ old('category', $expense->category ?? '') == 'other business expenses' ? 'selected' : '' }}>Other Business Expenses</option>
                            </select>
                            @error('category') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- DESCRIPTION (used for sub-category/type input) -->
                        <div class="mb-3" id="descriptionContainer">
                            <label for="description" class="form-label">Type</label>
                            <select name="description" id="description" class="form-select" required>
                                <!-- options populated by JS -->
                            </select>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" id="amount"
                                value="{{ old('amount', $expense->amount ?? '') }}" required>
                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ $mode === 'create' ? 'Save Expense' : 'Update Expense' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif



        {{-- EXPENSE LIST --}}
        @if($mode === 'index')
            <div class="card mb-4">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <span class="h5 mb-2 mb-md-0">Expenses Overview</span>
                    <form method="GET" action="{{ route('expenses.index') }}" class="d-flex flex-column flex-md-row gap-2 align-items-center">
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Reset</a>
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Expense
                        </a>
                    </form>
                </div>
                <div class="card-body">
                    @if($expenses->isEmpty())
                        <p class="text-muted">No expenses recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table id="expensesTable" class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Amount (₱)</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                                            <td>{{ ucfirst($expense->description) }}</td>
                                            <td>{{ ucfirst($expense->category) }}</td>
                                            <td>₱{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline delete-expense-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-expense-btn">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $expenses->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#expensesTable').DataTable();
    });
</script>

<script>
  $('#expensesTable').DataTable({
      pageLength: 10,
      order: [[0, 'desc']],
      language: {
          search: "Search expenses:",
          emptyTable: "No expenses recorded yet."
          dom: 'Bfrtip',
          buttons: ['copy', 'csv', 'excel', 'print']
      }
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        const descriptionContainer = document.getElementById('descriptionContainer');

        // Escape quotes in old value for description
        const oldDescription = `{{ old('description', $expense->description ?? '') }}`.replace(/"/g, '&quot;');

        const subCategories = {
            'utilities': ['Electricity Bills', 'Water Bills', 'Internet Cost'],
            'washing supplies': ['Laundry Detergents', 'Fabric Softeners', 'Stain Removers'],
            'equipment maintenance and repairs': ['Washing Machine Repair', 'Dryer Repair', 'Other Repairs'],
            'salaries and wages': ['Monthly Salaries', 'Overtime Pay', 'Bonuses'],
            'cleaning supplies': ['Cleaning Products'],
            'other business expenses': [] // special case: text input
        };

        function populateDescription(selectedCategory, selectedDescription = '') {
            if (selectedCategory === 'other business expenses') {
                // Text input for 'Other Business Expenses'
                descriptionContainer.innerHTML = `
                    <label for="description" class="form-label">Specify Type</label>
                    <input type="text" name="description" id="description" class="form-control" value="${selectedDescription}" required>
                `;
            } else if (subCategories[selectedCategory]) {
                // Dropdown for other categories
                let options = '<option value="" disabled selected>Select type</option>';
                subCategories[selectedCategory].forEach(item => {
                    const selected = item === selectedDescription ? 'selected' : '';
                    options += `<option value="${item}" ${selected}>${item}</option>`;
                });

                descriptionContainer.innerHTML = `
                    <label for="description" class="form-label">Type</label>
                    <select name="description" id="description" class="form-select" required>
                        ${options}
                    </select>
                `;
            } else {
                // Clear description input if no category selected
                descriptionContainer.innerHTML = '';
            }
        }

        categorySelect.addEventListener('change', (e) => {
            populateDescription(e.target.value);
        });

        // Populate on page load (edit form or after validation error)
        populateDescription(categorySelect.value, oldDescription);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-expense-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const message = @json(session('success'));

            let title = 'Success';
            let icon = 'success';

            if (message.toLowerCase().includes('deleted')) {
                title = 'Deleted';
            } else if (message.toLowerCase().includes('added')) {
                title = 'Added';
            } else if (message.toLowerCase().includes('updated')) {
                title = 'Updated';
            }

            await Swal.fire({
                icon: icon,
                title: title,
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif

<script>
    window.addEventListener("pageshow", function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>


</body>
</html>

