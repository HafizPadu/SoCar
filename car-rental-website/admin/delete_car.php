<?php
session_start();
require_once 'auth.php';
requireLogin();

require_once('../database_connection/oracle-connect.php');
$conn = getOracleConnection();

if (!isset($_GET['id'])) {
    die("No car specified.");
}

$carid = intval($_GET['id']);

$sql = "UPDATE car 
        SET is_deleted = 1 
        WHERE carid = :carid";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':carid', $carid);

$result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

if ($result) {
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
?>
