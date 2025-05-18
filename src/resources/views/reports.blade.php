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
<style>
  /* Main container */
  .report-container {
    max-width: 1700px;  /* Slightly wider */
    height: 750px;
    margin: 2rem auto;
    padding: 1rem 1.5rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    font-family: Arial, sans-serif;
  }

  /* Filter form */
  .filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem 2rem;
    align-items: center;
    margin-bottom: 2rem;
    background: #f4f6f8;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    box-shadow: inset 0 0 8px rgba(0,0,0,0.05);
  }

  .filter-form label {
    font-weight: 600;
    color: #333;
    min-width: 40px;
  }

  .filter-form select {
    min-width: 110px;
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
    background: white;
    cursor: pointer;
  }

  .filter-form button {
    background-color: #2563EB;
    color: white;
    font-weight: 600;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .filter-form button:hover {
    background-color: #1D4ED8;
  }

  /* Content: chart left, summary right */
  .content-wrapper {
    display: flex;
    gap: 2rem;
    justify-content: center;
    flex-wrap: wrap;
  }

  /* Bigger Chart Box */
  .chart-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    padding: 2rem 2rem 1rem;
    padding-left: 1rem;
    width: 1200px;       /* bigger width */
    height: 570px;      /* bigger height */
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .chart-box h2 {
    margin-bottom: 1.5rem;
    font-weight: 700;
    color: #222;
  }

  /* Canvas fills container */
  .chart-box canvas {
    width: 100% !important;
    height: 400px !important;
  }

  /* Summary smaller, right side */
  .summary-box {
    background: #f8fafc;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    padding: 2rem 1.8rem;
    width: 300px;       /* same width, slightly wider */
    color: #2c3e50;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .summary-box p {
    margin-bottom: 1.3rem;
    font-size: 1.15rem;
  }

  /* Responsive: stack on small screens */
  @media (max-width: 1080px) {
    .content-wrapper {
      justify-content: center;
    }
    .chart-box, .summary-box {
      width: 100%;
      max-width: 100%;
      height: auto;
    }
    .chart-box {
      padding-bottom: 2rem;
    }
    .chart-box canvas {
      height: 320px !important;
    }
    .summary-box {
      margin-top: 1.8rem;
      padding: 1.6rem;
      text-align: center;
    }
  }
</style>
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

        <!-- Filter Form -->
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

            <button type="submit">Filter</button>
        </form>

        <!-- Content: Chart left, summary right -->
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
        maintainAspectRatio: false,  // makes height controlled by CSS (canvas style)
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
