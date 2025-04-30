<ul>
<!-- Search how to redirect these links from dashboard -->
    <li><a href="{{ route('dashboard.index') }}" class="nav-link">Dashboard</a></li>
    <li onclick="redirect('services.html')">Services</li>
    <li onclick="redirect('history.html')">History</li>
    <li onclick="redirect('expenses.html')">Expenses</li>
    <li onclick="redirect('notification.html')">Notification</li>
    <li><a href="{{ route('account.settings') }}" class="nav-link"> Account Information</a></li>
    <a href="login.html" class="logout-link">Logout</a>
</ul>