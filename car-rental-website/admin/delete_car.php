<?php
session_start();
require_once 'auth.php';
requireLogin(); // ensure only logged-in admins can delete

require_once('../database_connection/oracle-connect.php');
$conn = getOracleConnection();

// Check if 'id' is passed via GET
if (!isset($_GET['id'])) {
    die("No car specified to delete.");
}

$carid = intval($_GET['id']); // sanitize input

try {
    // Prepare DELETE statement
    $sql = "DELETE FROM car WHERE carid = :carid";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':carid', $carid);

    $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

    if ($result) {
        // Success
        echo "<script>
            alert('Car deleted successfully.');
            window.location.href = 'cars.php';
        </script>";
    } else {
        $e = oci_error($stmt);
        echo "<script>
            alert('Error deleting car: {$e['message']}');
            window.location.href = 'cars.php';
        </script>";
    }

} catch (Exception $ex) {
    echo "<script>
        alert('Exception: {$ex->getMessage()}');
        window.location.href = 'cars.php';
    </script>";
}
?>
