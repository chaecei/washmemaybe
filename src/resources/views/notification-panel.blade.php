<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications – Laundry Shop Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="flex bg-[#D4F6FF] min-h-screen font-sans">

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

    <div id="notificationsModal" class="modal-container">
      <div class="modal-content">
        {{-- Filter --}}
        <div class="flex justify-end mb-4">
          <select
            class="px-3 py-2 border border-gray-300 rounded bg-[#FBFBFB] focus:outline-none focus:ring-2 focus:ring-[#C6E7FF]"
          >
            <option>Filter with</option>
            <option value="all">All</option>
            <option value="orders">Orders</option>
            <option value="users">Users</option>
          </select>
        </div>

        {{-- Notifications Table --}}
        <div class="overflow-auto">
          <table class="min-w-full border border-gray-300">
            <thead class="bg-[#C6E7FF]">
              <tr>
                <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium">Date and Time</th>
                <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium">Notification</th>
              </tr>
            </thead>
            <tbody>
              {{-- Example static rows; replace with @foreach($notifications as $n) ... @endforeach --}}
              <tr class="bg-white even:bg-[#FBFBFB]">
                <td class="border border-gray-300 px-4 py-2 text-sm">04/01 10:09</td>
                <td class="border border-gray-300 px-4 py-2 text-sm">New user registered into your system.</td>
              </tr>
              <tr class="bg-white even:bg-[#FBFBFB]">
                <td class="border border-gray-300 px-4 py-2 text-sm">04/01 10:30</td>
                <td class="border border-gray-300 px-4 py-2 text-sm">WIN023 laundry completed.</td>
              </tr>
              <tr class="bg-white even:bg-[#FBFBFB]">
                <td class="border border-gray-300 px-4 py-2 text-sm">04/01 13:03</td>
                <td class="border border-gray-300 px-4 py-2 text-sm">John Doe sent a laundry order.</td>
              </tr>
              <tr class="bg-white even:bg-[#FBFBFB]">
                <td class="border border-gray-300 px-4 py-2 text-sm">04/02 09:34</td>
                <td class="border border-gray-300 px-4 py-2 text-sm">Jennifer Huh sent a laundry order.</td>
              </tr>
              <tr class="bg-white even:bg-[#FBFBFB]">
                <td class="border border-gray-300 px-4 py-2 text-sm">04/02 10:56</td>
                <td class="border border-gray-300 px-4 py-2 text-sm">WIN011 laundry completed.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
        function openNotificationsModal() {
            document.getElementById('servicesModal').classList.add('active');
        }

        function closeNotificationsModal() {
            document.getElementById('servicesModal').classList.remove('active');
        }
    </script>

</body>
</html>

