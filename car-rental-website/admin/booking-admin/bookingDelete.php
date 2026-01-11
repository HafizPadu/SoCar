<?php
session_start();
require_once '../auth.php';
requireLogin();

require_once __DIR__ . '/../../database connection/oracle-connect.php';
$conn = getOracleConnection();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: bookingAdmin.php");
    exit;
}

/* Start transaction (manual commit) */
oci_execute(oci_parse($conn, "BEGIN NULL; END;"), OCI_NO_AUTO_COMMIT);

/* 1️⃣ Delete from PAYMENT (if exists) */
$sqlPayment = "DELETE FROM payment WHERE BookingID = :id";
$stmtPayment = oci_parse($conn, $sqlPayment);
oci_bind_by_name($stmtPayment, ':id', $id);
oci_execute($stmtPayment, OCI_NO_AUTO_COMMIT);

/* 2️⃣ Delete from CUSTOMER_BOOKING */
$sqlCB = "DELETE FROM customer_booking WHERE BookingID = :id";
$stmtCB = oci_parse($conn, $sqlCB);
oci_bind_by_name($stmtCB, ':id', $id);
oci_execute($stmtCB, OCI_NO_AUTO_COMMIT);

/* 3️⃣ Finally delete BOOKING */
$sqlBooking = "DELETE FROM booking WHERE BookingID = :id";
$stmtBooking = oci_parse($conn, $sqlBooking);
oci_bind_by_name($stmtBooking, ':id', $id);

if (!oci_execute($stmtBooking, OCI_NO_AUTO_COMMIT)) {
    $e = oci_error($stmtBooking);
    oci_rollback($conn);
    die("Delete failed: " . $e['message']);
}

/* ✅ Commit everything */
oci_commit($conn);

/* Cleanup */
oci_free_statement($stmtPayment);
oci_free_statement($stmtCB);
oci_free_statement($stmtBooking);
oci_close($conn);

/* Redirect back */
header("Location: bookingAdmin.php");
exit;
