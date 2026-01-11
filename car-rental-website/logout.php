<?php
session_start();

/* Destroy all session data */
$_SESSION = [];
session_destroy();

/* Redirect to login / index page */
header("Location: /SoCar-main/car-rental-website/admin/index.php");
exit;