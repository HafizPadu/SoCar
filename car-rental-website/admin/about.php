<?php
session_start();

require_once 'auth.php';
requireLogin();

if (isset($_GET['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SoCar</title>
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
    </style>

    <!-- FRONTEND STYLES (FIXED PATHS) -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/about.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- ADMIN ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Push frontend content below admin header */
        .page-container {
            margin-top: 160px;
        }
    </style>
</head>
<body>

<!-- ================= ABOUT HEADER ================= -->
<header class="about-header">
        <div class="header-top">
            <div class="logo">
                <i class="fas fa-car"></i>
                <span>SoCar</span> Admin
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
        
        <nav class="about-nav">
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../admin/booking-admin/bookingAdmin.php"> <i class="fas fa-calendar-check"></i> Booking</a></li>
                <li><a href="cars.php"><i class="fas fa-car"></i> Car Fleet</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="about.php"  class="active"><i class="fas fa-info-circle"></i> About Us</a></li>
            </ul>
        </nav>
</header>


<!-- ===== ABOUT PAGE CONTENT ===== -->

<main class="page-container">

    <!-- Main Banner -->
    <section class="about-main">
        <div class="container">
            <div class="main-content">
                <h1>Driving Excellence Since 2010</h1>
                <p>Your trusted partner for premium car rental services</p>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="our-story">
        <div class="container">
            <div class="story-grid">
                <div class="story-content">
                    <h2>Our Story</h2>
                    <p>
                        Founded in 2010, SpeedRent began with a simple mission: to make car rental easy,
                        affordable, and reliable for everyone.
                    </p>
                    <p>
                        With over a decade of experience, we've served thousands of satisfied customers,
                        from business travelers to vacationing families.
                    </p>
                </div>
                <div class="story-image">
                    <div class="image-placeholder">
                        <i class="fas fa-history"></i>
                        <p>Our Journey</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-vision">
        <div class="container">
            <div class="mv-grid">
                <div class="mission-card">
                    <div class="mv-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>
                        To provide exceptional car rental experiences through reliable vehicles,
                        transparent pricing, and outstanding customer service.
                    </p>
                </div>

                <div class="vision-card">
                    <div class="mv-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>
                        To become the most trusted and preferred car rental service globally,
                        setting new standards for quality and innovation.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="our-values">
        <div class="container">
            <h2 class="section-title">Our Core Values</h2>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Reliability</h3>
                    <p>Every vehicle is maintained to ensure your safety.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Trust</h3>
                    <p>Transparent pricing with no hidden fees.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Customer Focus</h3>
                    <p>24/7 support for all customers.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>Eco-friendly vehicles and practices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="our-team">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <p class="section-subtitle">Professionals behind SoCar</p>

            <div class="team-grid">
                <div class="team-card">
                    <div class="team-image"><i class="fas fa-user-tie"></i></div>
                    <h3>Akmal Rizal</h3>
                    <p class="team-role">Founder & CEO</p>
                </div>

                <div class="team-card">
                    <div class="team-image"><i class="fas fa-user-tie"></i></div>
                    <h3>Muhammad Hafiz</h3>
                    <p class="team-role">Operations Manager</p>
                </div>

                <div class="team-card">
                    <div class="team-image"><i class="fas fa-user-tie"></i></div>
                    <h3>Adam Hafizy</h3>
                    <p class="team-role">Fleet Director</p>
                </div>

                <div class="team-card">
                    <div class="team-image"><i class="fas fa-user-tie"></i></div>
                    <h3>Muhammad Adil</h3>
                    <p class="team-role">Customer Support Lead</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" data-count="10000">0</div>
                    <div class="stat-label">Happy Customers</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number" data-count="500">0</div>
                    <div class="stat-label">Vehicles</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number" data-count="50">0</div>
                    <div class="stat-label">Cities</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number" data-count="13">0</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

<!-- CTA Section -->
    <section class="about-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Experience Premium Car Rental?</h2>
                <p>Join thousands of satisfied customers who trust SpeedRent for their transportation needs.</p>
                <div class="cta-buttons">
                    <a href="booking.html" class="btn-primary">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </a>
                    <a href="contact.html" class="btn-secondary">
                        <i class="fas fa-phone"></i> Contact Us
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<footer class="footer">
    <!-- Same footer as index.html -->
</footer>


<!-- SCRIPTS -->
    <script src="../js/main.js"></script>
    <script src="../js/about.js"></script>

</body>
</html>
