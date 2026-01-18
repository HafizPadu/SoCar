<?php
session_start();

require_once '../auth.php';
requireLogin();

require_once __DIR__ . '/../../database connection/oracle-connect.php';

$conn = getOracleConnection();

if (isset($_POST['update_booking'])) {
    $sql = "UPDATE booking
            SET StartDate = TO_DATE(:start_date, 'YYYY-MM-DD'),
                EndDate = TO_DATE(:end_date, 'YYYY-MM-DD'),
                PickupMethod = :pickup_method,
                Address = :address
            WHERE BookingID = :booking_id";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':start_date', $_POST['start_date']);
    oci_bind_by_name($stmt, ':end_date', $_POST['end_date']);
    oci_bind_by_name($stmt, ':pickup_method', $_POST['pickup_method']);
    oci_bind_by_name($stmt, ':address', $_POST['address']);
    oci_bind_by_name($stmt, ':booking_id', $_POST['booking_id']);

    oci_execute($stmt);
    header("Location: bookingAdmin.php");
    exit;
}


// Fetch booking records with joins
$sql = "
SELECT 
    b.BookingID,
    b.CustID,
    b.StaffID,
    b.CarID,
    b.StartDate,
    b.EndDate,
    b.PickupMethod,
    b.Address,

    c.CustName,
    c.CustPhoneNo,
    c.CustEmail,

    car.CarModel,
    car.CarType,
    car.PricePerDay,

    p.PaymentStatus,
    p.Amount

FROM booking b
LEFT JOIN customer c ON b.CustID = c.CustID
LEFT JOIN car ON b.CarID = car.CarID
LEFT JOIN payment p ON b.BookingID = p.BookingID
ORDER BY b.BookingID ASC
";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Booking Records</title>
    <link rel="icon" type="image/png" href="../images/socar.png">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #1f2937;
            color: white;
        }
        .btn {
            padding:6px 10px; 
            border-radius:4px; 
            color:white; 
            border:none; 
            cursor:pointer;
        }
        .btn-edit { background: #2563eb; }
        .btn-delete { background: #dc2626; }
    </style>

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <link rel="stylesheet" href="../../css/bookingAdmin.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Push frontend content below admin header */
        .page-container {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <header class="bookingAdmin-header">
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
                <a href="../../logout.php"
                    class="logout-btn"
                    onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

        <nav class="bookingAdmin-nav">
            <ul class="nav-menu">
                <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="bookingAdmin.php" class="active"> <i class="fas fa-calendar-check"></i> Booking</a></li>
                <li><a href="../cars.php"><i class="fas fa-car"></i> Car Fleet</a></li>
                <li><a href="../customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="../reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="../settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="../about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
            </ul>
        </nav>
    </header>

    <main class="page-container">
        <h2>📋 Booking Records</h2>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search booking, customer, car...">
    </div>

        <table>
            <thead>
                <tr>
                    <th>BookingID</th>
                    <th>CustID</th>
                    <th>StaffID</th>
                    <th>CarID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Pickup Method</th>
                    <th>Address</th>
                    <th>Amount (RM)</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($stmt): ?>
                    <?php while ($row = oci_fetch_assoc($stmt)): ?>

                        <tr>
                            <td><?= $row['BOOKINGID'] ?? '-' ?></td>

                            <td>
                                <?= htmlspecialchars($row['CUSTID']) ?>
                                <?= htmlspecialchars($row['CUSTNAME']) ?><br>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['STAFFID']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['CARID']) ?>
                                <?= htmlspecialchars($row['CARMODEL']) ?><br>
                            </td>

                            <td>
                                <?= date('d M Y', strtotime($row['STARTDATE'])) ?>
                            </td>

                            <td>
                                <?= date('d M Y', strtotime($row['ENDDATE'])) ?>        
                            </td>

                            <td>
                                <?= htmlspecialchars($row['PICKUPMETHOD']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['ADDRESS']) ?>
                            </td>

                            <td>
                                RM <?= number_format($row['PRICEPERDAY'] ?? 0, 2) ?>
                            </td>

                            <td>
                                <?= $row['PAYMENTSTATUS'] ?? 'Pending' ?>
                            </td>

                            <td>
                                <button class="btn btn-edit"
                                    data-id="<?= $row['BOOKINGID'] ?>"
                                    data-start="<?= date('Y-m-d', strtotime($row['STARTDATE'])) ?>"
                                    data-end="<?= date('Y-m-d', strtotime($row['ENDDATE'])) ?>"
                                    data-pickup="<?= $row['PICKUPMETHOD'] ?>"
                                    data-address="<?= htmlspecialchars($row['ADDRESS'], ENT_QUOTES) ?>"
                                    onclick="openEditModal(this)">
                                    Edit
                                </button>

                                <a class="btn btn-delete"
                                    href="bookingDelete.php?id=<?= $row['BOOKINGID'] ?>"
                                    onclick="return confirm('Delete this booking?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                    <td colspan="11">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Booking</h3>

            <form method="POST">
                <input type="hidden" name="booking_id" id="editBookingID">

                <label>Start Date</label>
                <input type="date" name="start_date" id="editStart" required>

                <label>End Date</label>
                <input type="date" name="end_date" id="editEnd" required>

                <label>Pickup Method</label>
                <input type="text" name="pickup_method" id="editPickup">

                <label>Address</label>
                <textarea name="address" id="editAddress"></textarea>

                <div style="margin-top:10px;">
                    <button type="submit" name="update_booking">Save</button>
                    <button type="button" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../js/bookingAdmin.js"></script>

</body>

    <footer class="footer">
        <!-- Same footer as index.html -->
    </footer>
</html>


<?php oci_close($conn); ?>


