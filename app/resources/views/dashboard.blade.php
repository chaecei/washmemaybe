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
        @include('partials.sidebar')
    </div>
    
    <div class="main-content">
        
        <div class="admin-profile">
            @include('partials.admin')
        </div>


        <div class="header"> 
            @include('partials.header')
        </div>


            <div class="row mb-4">
                    <div class="col-md-3">
                        <a href="{{url('/dashboard/pending') }}">
                            <div class="card card-pending">
                                <div class="card-body">
                                    <h5 class="card-title">Pending</h5>
                                    <p class="card-text h4">6</p>
                                </div>
                            </div>
                        </a>
                    </div>
                
                    <div class="col-md-3">
                        <a href="{{url('/dashboard/processing') }}">
                            <div class="card card-processing">
                                <div class="card-body">
                                    <h5 class="card-title">Processing</h5>
                                    <p class="card-text h4">4</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{url('/dashboard/ready') }}">
                            <div class="card card-ready">
                                <div class="card-body">
                                    <h5 class="card-title">Ready</h5>
                                    <p class="card-text h4">2</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{url('/dashboard/unclaimed') }}">
                            <div class="card card-completed">
                                <div class="card-body">
                                    <h5 class="card-title">Unclaimed</h5>
                                    <p class="card-text h4">2</p>
                                </div>
                            </div>
                        </a>
                    </div>
            </div>

            <div class="dynamic-area">
                @if(Route::is('dashboard.index'))
                    @include('partials.orders-table')
                @elseif(Route::is('dashboard.pending'))
                    @include('partials.pending-table')
                @elseif(Route::is('dashboard.processing'))
                    @include('partials.processing-table')
                @elseif(Route::is('dashboard.ready'))
                    @include('partials.ready-table')
                @elseif(Route::is('dashboard.unclaimed'))
                    @include('partials.unclaimed-table')
                @endif
            </div>

            <!-- <div class="dynamic-area"> 
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
                </div> -->

                <div class="dynamic-content">
                    @yield('dynamic-content')
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
