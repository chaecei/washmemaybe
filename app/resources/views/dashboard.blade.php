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
                <a href="{{ route('dashboard.pending') }}">
                    <div class="card card-pending">
                        <div class="card-body">
                            <h5 class="card-title" id="pending">Pending</h5>
                            <p class="card-text h4">9</p>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-md-3">
                <a href="{{ route('dashboard.processing') }}">
                    <div class="card card-processing">
                        <div class="card-body">
                            <h5 class="card-title" id="processing">Processing</h5>
                            <p class="card-text h4">4</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('dashboard.ready') }}">
                    <div class="card card-ready">
                        <div class="card-body">
                            <h5 class="card-title" id="processing">Ready for Pickup</h5>
                            <p class="card-text h4">2</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('dashboard.unclaimed') }}">
                    <div class="card card-completed">
                        <div class="card-body">
                            <h5 class="card-title" id="processing">Unclaimed</h5>
                            <p class="card-text h4">2</p>
                        </div>
                    </div>
                </a>
            </div>
    </div>

            <!-- Cards for navigation -->
            <!-- <div class="cards">
                <div class="card" id="pending">Pending</div>
                <div class="card" id="processing">Processing</div>
                <div class="card" id="ready">Ready for Pickup</div>
                <div class="card" id="unclaimed">Unclaimed</div>
            </div> -->

            <!-- Tables (preloaded and hidden initially) -->
            <!-- <div id="tables-area"> -->

                <!-- Pending Table (hidden initially) -->
                <!-- <div class="card card-pending nav-card" data-target="pending-table">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <p class="card-text h4">4</p>
                    </div>
                </div> -->
                <!-- Processing Table (hidden initially) -->
                <!-- <div class="card card-processing nav-card" data-target="processing-table">
                    <div class="card-body">
                        <h5 class="card-title">Processing</h5>
                        <p class="card-text h4">5</p>
                    </div>
                </div> -->
                <!-- Ready Table (hidden initially) -->
                <!-- <div class="card card-ready nav-card" data-target="ready-table">
                    <div class="card-body">
                        <h5 class="card-title">Ready for Pickup</h5>
                        <p class="card-text h4">3</p>
                    </div>
                </div> -->

                <!-- Unclaimed Table (hidden initially) -->
                <!-- <div class="card card-completed nav-card" data-target="unclaimed-table">
                    <div class="card-body">
                        <h5 class="card-title">Unclaimed</h5>
                        <p class="card-text h4">2</p>
                    </div>
                </div>

            </div> -->

            <div id="tables-area">
                <div id="pending-table" class="table-section" style="display: none;">
                    @include('partials.pending-table')
                </div>

                <div id="processing-table" class="table-section" style="display: none;">
                    @include('partials.processing-table')
                </div>

                <div id="ready-table" class="table-section" style="display: none;">
                    @include('partials.ready-table')
                </div>

                <div id="unclaimed-table" class="table-section" style="display: none;">
                    @include('partials.unclaimed-table')
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

                <div class="dynamic-content">
                    @yield('dynamic-content')
                </div>

                <div class="container">
                    @yield('content')
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $(".nav-card").click(function() {
        var target = $(this).data('target');
        $(".table-section").hide();
        $("#" + target).fadeIn();
    });
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
