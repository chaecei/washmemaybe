<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
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

    
    <div class="report-container">

        <form method="GET" action="{{ route('reports') }}" class="filter-form">
            <label for="year">Year:</label>
            <select name="year" id="year">
                @for ($y = $maxYear; $y >= $minYear; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <label for="month">Month:</label>
            <select name="month" id="month">
                <option value="">All</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
            </select>

            <label for="week" id="weekLabel" style="display:none; margin-left: 1rem;">Week:</label>
            <select name="week" id="week" style="display:none;">
                <option value="1" {{ request('week') == '1' ? 'selected' : '' }}>Week 1 (Days 1-7)</option>
                <option value="2" {{ request('week') == '2' ? 'selected' : '' }}>Week 2 (Days 8-14)</option>
                <option value="3" {{ request('week') == '3' ? 'selected' : '' }}>Week 3 (Days 15-21)</option>
                <option value="4" {{ request('week') == '4' ? 'selected' : '' }}>Week 4 (Days 22-End)</option>
            </select>

            <button type="submit" style="margin-left: 1rem;">Filter</button>
        </form>

        <!-- Content is: Chart left, summary right -->
        <div class="content-wrapper">
            <div class="chart-box">
            <h2>Monthly Report - {{ $year }} @if($month) - {{ \Carbon\Carbon::create($year, (int)$month, 1)->format('F') }} @endif</h2>
            <canvas id="reportChart"></canvas>
            </div>

            <div class="summary-box">
            <p><strong>Total Earnings:</strong> ₱{{ number_format($totalEarnings, 2) }}</p>
            <p><strong>Total Expenses:</strong> ₱{{ number_format($totalExpenses, 2) }}</p>
            <p><strong>Profit:</strong> ₱{{ number_format($profit, 2) }}</p>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthSelect = document.getElementById('month');
            const weekSelect = document.getElementById('week');
            const weekLabel = document.getElementById('weekLabel');

            function toggleWeekSelector() {
                if (monthSelect.value) {
                    weekSelect.style.display = 'inline-block';
                    weekLabel.style.display = 'inline-block';
                } else {
                    weekSelect.style.display = 'none';
                    weekLabel.style.display = 'none';
                    weekSelect.value = '';
                }
            }

            toggleWeekSelector();

            monthSelect.addEventListener('change', toggleWeekSelector);
        });
    const ctx = document.getElementById('reportChart').getContext('2d');
    const reportChart = new Chart(ctx, {
        type: 'bar',
        data: {
        labels: {!! json_encode($labels) !!},
        datasets: [
            {
            label: 'Earnings',
            backgroundColor: '#4caf50',
            data: {!! json_encode($earnings) !!}
            },
            {
            label: 'Expenses',
            backgroundColor: '#f44336',
            data: {!! json_encode($expenses) !!}
            }
        ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
            title: {
                display: true,
                text: {!! $month ? "'Day'" : "'Month'" !!}
            }
            },
            y: {
            beginAtZero: true,
            title: {
                display: true,
                text: '₱ Amount'
            }
            }
        },
        plugins: {
            title: {
            display: true,
            text: 'Earnings vs Expenses'
            },
            tooltip: {
            callbacks: {
                label: function(context) {
                return '₱' + parseFloat(context.raw).toLocaleString(undefined, {minimumFractionDigits: 2});
                }
            }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
        }
    });
    </script>

    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

</body>
</html>