<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F6F0F0;
            margin: 0;
            padding: 0;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 250px;
            background-color: #27548A;
            color: white;
            display: flex;
            height: 100vh;
            margin-right: 100px;
            position: fixed;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            font-size: 20px;
            transition: all 0.3s ease;D
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding-left: 0;
            padding-top: 100px;
        }
        .sidebar li {
            padding: 20px 30px;
            cursor: pointer;
        }
        .sidebar li:hover {
            background-color: #213555;
            box-shadow: 6px 0 20px rgba(0, 0, 0, 0.2);
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }
        .header-image {
            width: 25%;      /* Makes the image fit the full sidebar width */
            height: auto;     /* Keep aspect ratio */
            object-fit: cover;
            object-position: right top; /* Align the image to the top right corner */
        }
        .status-text {
            font-weight: bold;
            font-size: 1.3rem;
            padding: 4px 8px;
            border-radius: 12px;
            color: #5a3e1b;
        }
        .submenu {
            padding-left: 10px;
            display: none;
        }
        .submenu li {
            margin-left: 25px;
        }
        .submenu.item {
            padding-left: 20px;
            color: #ccc;
            font-size: 0.94rem;
        }
        .submenu.show {
            display: block;
            padding-left: 30px;
        }
        .admin-profile {
            position: absolute;
            top: 20px;
            right: 30px;
            z-index: 999;
        }

        .admin-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #343a40;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .admin-img:hover {
            transform: scale(1.1);
        }
        .admin-header span {
            font-size: 25px;
            font-weight: 500;
            color: #333;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .card {
            margin-bottom: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 8px 10px;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-pending {
            background-color: #C6E7FF;
            color: #003049; /* Darker text for contrast */
        }

        .card-processing {
            background-color: #D4F6FF;
            color: #023e8a;
        }

        .card-ready {
            background-color: #FFDDAE;
            color: #7f5539;
        }

        .card-completed {
            background-color: #F5EEDD;
            color: #386641;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .badge-pending { background-color: #C6E7FF; color: #000; }
        .badge-processing { background-color: #023e8a; color: #fff; }
        .badge-ready { background-color: #F0A04B; color: #fff; }
        .badge-completed { background-color: #6c757d; color: #fff; }

        .circle-card {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: #60B5FF;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
              transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-left: auto;
            padding-top: 5px;
            margin-top: 180px;
            margin-left: 100px;
            margin-right: 100px;
        }
        .circle-card:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .d-flex-space {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .table {
            width: 100%;
            max-width: 100%; /* Prevent it from exceeding the container's width */
            min-width: 600px; /* Prevent table from shrinking too small */
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-container {
            width: 100%;
            margin: auto;
            overflow-x: auto;
            box-sizing: border-box;
        }
        .table th, .table td {
            font-size: 16px; /* Adjust font size */
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .table th {
            background-color: #2973B2;
            color: white;
        }

        .table td {
            background-color: #ffffff;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }
        .logout-link {
            position: absolute;
            bottom: 80px;  /* Adjust the distance from the bottom */
            right: 150px;   /* Adjust the distance from the right */
            color: #fff;   /* Text color */
            font-size: 1rem;  /* Text size */
            text-decoration: none;  /* Remove underline */
            font-weight: bold;     /* Bold text */
            transition: color 0.3s ease;
            font-family: 'Segoe UI', sans-serif; 
            font-size: 20px;
        }

        .logout-link:hover {
            color: #ff4d4d;  /* Change color on hover */
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .table {
                font-size: 14px;  /* Adjust font size on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .table {
                font-size: 12px;  /* Adjust font size on very small screens */
            }

            .table th, .table td {
                padding: 8px;  /* Adjust padding on very small screens */
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
        

    </style>
</head>
<body>
    <div class="sidebar">
            <ul>
        <!-- Search how to redirect these links from dashboard -->
                <li>Dashboard</li>
                <li onclick="redirect('services.html')">Services</li>
                <li onclick="redirect('laundry-status.html')">Laundry Status</li>
                <li onclick="redirect('history.html')">History</li>
                <li onclick="redirect('account-information.html')">Account Information</li>
                <a href="login.html" class="logout-link">Logout</a>
            </ul>
    </div>
    

    <div class="main-content">

        <div class="admin-profile">
            <span class="status-text">Good Day!</span>
            <img src="images\admin_icon.jpg" alt="Admin" class="admin-img">
        </div>


        <div class="header"> 
            <img src="images\title.png" alt="Header Image" class="header-image">
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-pending">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text h4">2</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-processing">
                    <div class="card-body">
                        <h5 class="card-title">Processing</h5>
                        <p class="card-text h4">3</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-ready">
                    <div class="card-body">
                        <h5 class="card-title">Ready</h5>
                        <p class="card-text h4">2</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-completed">
                    <div class="card-body">
                        <h5 class="card-title">Unclaimed</h5>
                        <p class="card-text h4">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex-space mb-4">
            <div class="card flex-grow-1 me-4">
                <div class="card-header">
                    <h5 class="mb-0">Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SERVICE NUMBER</th>
                                    <th>Status</th>
                                    <th>Date and Time Picked Up</th>
                                    <th>Grand Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>WIN001</td>
                                    <td><span class="status-badge badge-completed">Completed</span></td>
                                    <td>04/11/25 16:00</td>
                                    <td>450.00</td>
                                </tr>
                                <tr>
                                    <td>WIN002</td>
                                    <td><span class="status-badge badge-ready">Ready for Pickup</span></td>
                                    <td></td>
                                    <td>150.00</td>
                                </tr>
                                <tr>
                                    <td>WIN003</td>
                                    <td><span class="status-badge badge-ready">Ready for Pickup</span></td>
                                    <td></td>
                                    <td>600.00</td>
                                </tr>
                                <tr>
                                    <td>WIN004</td>
                                    <td><span class="status-badge badge-processing">In Progress</span></td>
                                    <td></td>
                                    <td>300.00</td>
                                </tr>
                                <tr>
                                    <td>WIN005</td>
                                    <td><span class="status-badge badge-processing">In Progress</span></td>
                                    <td></td>
                                    <td>550.00</td>
                                </tr>
                                <tr>
                                    <td>WIN006</td>
                                    <td><span class="status-badge badge-processing">In Progress</span></td>
                                    <td></td>
                                    <td>520.00</td>
                                </tr>
                                <tr>
                                    <td>WIN007</td>
                                    <td><span class="status-badge badge-pending">Pending</span></td>
                                    <td></td>
                                    <td>240.00</td>
                                </tr>
                                <tr>
                                    <td>WIN008</td>
                                    <td><span class="status-badge badge-pending">Pending</span></td>
                                    <td></td>
                                    <td>150.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="circle-card">
                <div>
                    <div> Total Earnings: </div>
                    <div class="h4">1,600.00</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            var submenu = document.getElementById(id);
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        }
    
        function redirect(url) {
            window.location.href = url;
        }

        function logout() {
            // Add logout logic here (e.g., redirect to login page, clear session)
            alert("Logging out...");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
