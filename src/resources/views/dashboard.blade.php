<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <style>
    body {
      background-color: #FBFBFB;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      background-color: #C6E7FF;
      min-height: 100vh;
      width: 250px;
      padding: 20px 10px;
      position: fixed;
    }

    .sidebar h4 {
      text-align: center;
      color: #000;
      margin-bottom: 30px;
    }

    .sidebar button {
      background-color: #D4F6FF;
      border: none;
      margin: 8px 0;
      width: 100%;
      padding: 15px;
      text-align: left;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    .sidebar button.active {
      background-color: #FFDDAE;
    }

    .dashboard-content {
      margin-left: 260px;
      padding: 20px;
    }

    .top-bar {
      display: flex;
      justify-content: flex-end;
      padding: 10px;
      background-color: #C6E7FF;
    }

    .top-bar span {
      margin-right: 10px;
    }

    .top-bar img {
      border-radius: 50%;
      width: 60px;
      height: 40px;
    }

    .status-cards {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .status-cards .card {
      flex: 1;
      margin: 0 10px;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      font-size: 22px;
      font-weight: bold;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .status-cards .pending {
      background-color: #C6E7FF;
    }

    .status-cards .processing {
      background-color: #D4F6FF;
    }

    .status-cards .ready {
      background-color: #FBFBFB;
    }

    .status-cards .unclaimed {
      background-color: #FFDDAE;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      background-color: #ffffff;
    }

    .data-table th {
      background-color: #FFDDAE;
      padding: 10px;
      border: 1px solid #ccc;
    }

    .data-table td {
      padding: 10px;
      border: 1px solid #ccc;
    }

    .earnings-box {
      background-color: #FFDDAE;
      color: #000;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      font-weight: bold;
      margin-left: auto;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Laundry Shop</h4>
    <button class="active">Dashboard</button>
    <button>Notification</button>
    <button>Add Order</button>
    <button>Expenses</button>
    <button>Services</button>
    <button>Laundry Status</button>
    <button>History</button>
    <button>Account Info</button>
    <hr>
    <button>Settings</button>
    <button>Logout</button>
  </div>

  <!-- Main Content -->
  <div class="dashboard-content">
    <div class="top-bar">
      <span>ADMIN</span>
      <img src="https://via.placeholder.com/40" alt="Admin" />
    </div>

    <!-- Status Cards -->
    <div class="status-cards">
      <div class="card pending">Pending</div>
      <div class="card processing">Processing</div>
      <div class="card ready">Ready for Pickup</div>
      <div class="card unclaimed">Unclaimed</div>
    </div>

    <!-- Order Table -->
    <table class="data-table">
      <thead>
        <tr>
          <th>Service Number</th>
          <th>Status</th>
          <th>Date and Time Picked Up</th>
          <th>Grand Total</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>WIN001</td><td>Completed</td><td>04/11/25 16:00</td><td>450.00</td></tr>
        <tr><td>WIN002</td><td>Ready for Pickup</td><td></td><td>150.00</td></tr>
        <tr><td>WIN003</td><td>Ready for Pickup</td><td></td><td>600.00</td></tr>
        <tr><td>WIN004</td><td>In Progress</td><td></td><td>300.00</td></tr>
        <tr><td>WIN005</td><td>In Progress</td><td></td><td>550.00</td></tr>
        <tr><td>WIN006</td><td>In Progress</td><td></td><td>520.00</td></tr>
        <tr><td>WIN007</td><td>Pending</td><td></td><td>240.00</td></tr>
        <tr><td>WIN008</td><td>Pending</td><td></td><td>150.00</td></tr>
      </tbody>
    </table>

    <!-- Earnings Display -->
    <div class="earnings-box">
      1,600.00
    </div>
    <p style="text-align: right; margin-top: 10px;">Total Earnings: Today</p>
  </div>

</body>
</html>
