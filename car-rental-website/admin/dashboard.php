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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f7fb;
            min-height: 100vh;
        }
        
        /* Header */
        .admin-header {
            background: white;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #edf2f7;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .logo i {
            color: #3498db;
            font-size: 28px;
        }
        
        .logo span {
            color: #3498db;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #edf2f7;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        /* Navigation */
        .admin-nav {
            padding: 15px 0;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        
        .nav-menu a {
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-menu a:hover {
            background: #edf2f7;
            color: #2c3e50;
        }
        
        .nav-menu a.active {
            background: #3498db;
            color: white;
        }
        
        .nav-menu a i {
            font-size: 16px;
        }
        
        /* Main Content */
        .admin-main {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #718096;
            font-size: 16px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #3498db;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-title {
            color: #718096;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #e8f4fc, #d4e6f1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3498db;
            font-size: 22px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .stat-change.up {
            color: #27ae60;
        }
        
        .stat-change.down {
            color: #e74c3c;
        }
        
        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .welcome-text p {
            opacity: 0.9;
        }
        
        .welcome-icon {
            font-size: 48px;
            opacity: 0.8;
        }
        
        /* Table */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            padding: 15px;
            text-align: left;
            background: #f8fafc;
            color: #2c3e50;
            font-weight: 600;
            border-bottom: 2px solid #edf2f7;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #edf2f7;
            color: #4a5568;
        }
        
        tr:hover {
            background: #f8fafc;
        }
        
        .status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status.confirmed {
            background: #d5f4e6;
            color: #27ae60;
        }
        
        .status.pending {
            background: #fff3cd;
            color: #f39c12;
        }
    </style>
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
                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="cars.php"><i class="fas fa-car"></i> Car Fleet</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
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
                <div class="stat-value">$24,580</div>
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
                        <td>$135.00</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001249</td>
                        <td>Sarah Johnson</td>
                        <td>BMW X5</td>
                        <td>Jan 14-20, 2024</td>
                        <td>$720.00</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001248</td>
                        <td>Mike Chen</td>
                        <td>Honda Civic</td>
                        <td>Jan 16-19, 2024</td>
                        <td>$105.00</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#SR-001247</td>
                        <td>Emily Davis</td>
                        <td>Toyota RAV4</td>
                        <td>Jan 13-17, 2024</td>
                        <td>$260.00</td>
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
</body>
</html>