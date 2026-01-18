<?php
session_start();
require_once 'auth.php';

// Require login for this page
requireLogin();

// Handle logout
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SoCar Admin</title>
    <link rel="icon" type="image/png" href="../images/socar.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        
    </style>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/dashboard.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="header-top">
            <div class="logo">
                <i class="fas fa-car"></i>
                So<span>Car</span> Admin
            </div>
            
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></div>
                        <div style="font-size: 12px; color: #718096;">Administrator</div>
                    </div>
                </div>
                <a href="?logout=true" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
        
        <nav class="admin-nav">
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../admin/booking-admin/bookingAdmin.php"> <i class="fas fa-calendar-check"></i> Booking</a></li>
                <li><a href="cars.php"><i class="fas fa-car"></i> Car Fleet</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Welcome Message -->
        <div class="welcome-message">
            <div class="welcome-text">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>! 👋</h2>
                <p>You are logged in as Administrator. Last login: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            <div class="welcome-icon">
                <i class="fas fa-car"></i>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Monitor your car rental business performance</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">RM24,580</div>
                <div class="stat-change up">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% from last month</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Active Bookings</div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value">24</div>
                <div class="stat-change up">
                    <i class="fas fa-arrow-up"></i>
                    <span>3 new today</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Available Cars</div>
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                </div>
                <div class="stat-value">18/25</div>
                <div class="stat-change down">
                    <i class="fas fa-arrow-down"></i>
                    <span>7 in maintenance</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Customers</div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-change up">
                    <i class="fas fa-arrow-up"></i>
                    <span>8 new this week</span>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="table-container">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">
                <i class="fas fa-calendar-check" style="color: #3498db; margin-right: 10px;"></i>
                Recent Bookings
            </h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Car</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#SR-001250</td>
                        <td>John Smith</td>
                        <td>Toyota Camry</td>
                        <td>Jan 15-18, 2024</td>
                        <td>RM135.00</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001249</td>
                        <td>Sarah Johnson</td>
                        <td>BMW X5</td>
                        <td>Jan 14-20, 2024</td>
                        <td>RM720.00</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001248</td>
                        <td>Mike Chen</td>
                        <td>Honda Civic</td>
                        <td>Jan 16-19, 2024</td>
                        <td>RM105.00</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001247</td>
                        <td>Emily Davis</td>
                        <td>Toyota RAV4</td>
                        <td>Jan 13-17, 2024</td>
                        <td>RM260.00</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Session Info (for debugging) -->
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 8px; font-size: 12px; color: #666;">
            <strong>Debug Info:</strong> Session ID: <?php echo session_id(); ?> | 
            Logged in: <?php echo isLoggedIn() ? 'Yes' : 'No'; ?> |
            <a href="?debug=1" style="color: #3498db;">Refresh Session</a>
        </div>
    </main>

    <script>
        // Auto-refresh session warning
        let sessionTimeout = 30 * 60 * 1000; // 30 minutes
        
        setTimeout(function() {
            if (confirm('Your session will expire soon. Continue session?')) {
                // Refresh the page to extend session
                window.location.reload();
            }
        }, sessionTimeout - 60000); // Warn 1 minute before
        
        // Check if user is logged in (client-side check)
        function checkLogin() {
            fetch('check-auth.php')
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => {
                    console.error('Auth check failed:', error);
                });
        }
        
        // Check every 5 minutes
        setInterval(checkLogin, 5 * 60 * 1000);
    </script>

<footer class="footer">
    <!-- Same footer as index.html -->
</footer>

</body>
</html>