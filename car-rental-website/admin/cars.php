<?php
session_start();
require_once 'auth.php';
requireLogin();
require_once('../database_connection/oracle-connect.php');
$conn = getOracleConnection();
require_once 'edit_car_logic.php';
require_once 'add_car_logic.php';

// Fetch cars from Oracle
$sql = "SELECT carid, carmodel, cartype, priceperday, availability FROM car WHERE is_deleted = 0 ORDER BY carid DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Car | SoCar Admin</title>
<link rel="icon" type="image/png" href="../images/socar.png">

<!-- Stylesheets -->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/responsive.css">
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Center text vertically and horizontally */
.table td, .table th {
    vertical-align: middle;
    text-align: center;
}

/* Fixed height for images, keep aspect ratio */
.table img {
    height: 80px;        /* all images same height */
    width: auto;         /* width adjusts automatically */
    object-fit: contain; /* prevents stretching */
    border-radius: 8px;
}

/* Optional: fixed row height */
.table tbody tr {
    height: 100px;       /* all rows same height */
}

/* Handle long text in table */
.table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Small margin for buttons */
.table td .btn {
    margin: 2px;
}
</style>

</head>
<body>

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
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../admin/booking-admin/bookingAdmin.php"> <i class="fas fa-calendar-check"></i> Booking</a></li>
                <li><a href="cars.php" class="active"><i class="fas fa-car"></i> Car</a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
            </ul>
        </nav>
    </header>

<main class="admin-main">
  <div class="page-header">
    <h1 class="page-title">Car Management</h1>
    <p class="page-subtitle">Monitor all cars available for rent</p>
  </div>

  <div class="table-container">
    <h2 style="color: #2c3e50; margin-bottom: 20px;">
      <i class="fas fa-car" style="color: #3498db; margin-right: 10px;"></i>
      Car List
    </h2>
    
    
    <div class="d-flex justify-content-end mb-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-car">
        <i class="fas fa-plus"></i> Add Car
      </button>
    </div>


    <table class="table table-striped">
      <thead>
        <tr>
          <th>Image</th>
          <th>Model</th>
          <th>Type</th>
          <th>Price / Day</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = oci_fetch_assoc($stmt)) { 
            $availability = trim(strtoupper($row['AVAILABILITY']));
        ?>
        <tr>
          <td><img src="car_image.php?id=<?php echo $row['CARID']; ?>" width="120" style="border-radius:8px;"></td>
          <td><?php echo htmlspecialchars($row['CARMODEL']); ?></td>
          <td><?php echo htmlspecialchars($row['CARTYPE']); ?></td>
          <td>RM <?php echo $row['PRICEPERDAY']; ?></td>
          <td>
            <?php if ($availability === 'AVAILABLE') { ?>
              <span class="badge bg-success">Available</span>
            <?php } else { ?>
              <span class="badge bg-danger">Unavailable</span>
            <?php } ?>
          </td>
          <td>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-car<?php echo $row['CARID']; ?>">Edit</button>
            <a href="delete_car.php?id=<?php echo $row['CARID']; ?>" 
               class="btn btn-danger" 
               onclick="return confirm('Are you sure you want to delete this car?')">Delete</a>
          </td>
        </tr>

        <!-- ADD CAR MODAL -->
        <div class="modal fade" id="modal-add-car" tabindex="-1">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">

              <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title">Add New Car</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                  <div class="row mb-3">
                    <div class="col-lg-4">
                      <label>Car Model</label>
                      <input type="text" class="form-control" name="carmodel" required>
                    </div>

                    <div class="col-lg-4">
                      <label>Car Type</label>
                      <input type="text" class="form-control" name="cartype" required>
                    </div>

                    <div class="col-lg-4">
                      <label>Availability</label>
                      <select class="form-control" name="availability">
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-lg-6">
                      <label>Price Per Day (RM)</label>
                      <input type="number" class="form-control" name="priceperday" required>
                    </div>

                    <div class="col-lg-6">
                      <label>Car Image</label>
                      <input type="file" class="form-control" name="car_image" accept="image/*">
                    </div>
                  </div>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="add_car" class="btn btn-success">Add Car</button>
                </div>
              </form>

            </div>
          </div>
        </div>
        <!-- END ADD CAR MODAL -->


        <!-- PER-ROW MODAL -->
        <div class="modal fade" id="modal-edit-car<?php echo $row['CARID']; ?>" tabindex="-1">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Car <span style="color:red"><?php echo $row['CARID']; ?></span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="carid" value="<?php echo $row['CARID']; ?>">

                  <div class="row mb-3">
                    <div class="col-lg-4">
                      <label>Car Model</label>
                      <input class="form-control" name="carmodel" value="<?php echo htmlspecialchars($row['CARMODEL']); ?>">
                    </div>
                    <div class="col-lg-4">
                      <label>Car Type</label>
                      <input class="form-control" name="cartype" value="<?php echo htmlspecialchars($row['CARTYPE']); ?>">
                    </div>
                    <div class="col-lg-4">
                      <label>Availability</label>
                      <select class="form-control" name="availability">
                        <option value="Available" <?php echo $row['AVAILABILITY']=='Available'?'selected':''; ?>>Available</option>
                        <option value="Unavailable" <?php echo $row['AVAILABILITY']=='Unavailable'?'selected':''; ?>>Unavailable</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-lg-6">
                      <label>Price Per Day (RM)</label>
                      <input class="form-control" name="priceperday" value="<?php echo $row['PRICEPERDAY']; ?>">
                    </div>
                    <div class="col-lg-6">
                      <label>Car Image</label>
                      <input type="file" class="form-control" name="car_image">
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" name="edit_car" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- END MODAL -->

        <?php } ?>
      </tbody>
    </table>
  </div>
</main>

<footer class="footer">
  <p style="text-align:center; padding:15px; color:#888;">&copy; <?php echo date('Y'); ?> SoCar Admin. All rights reserved.</p>
</footer>

</body>
</html>
