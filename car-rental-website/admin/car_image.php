<?php
require_once('../database_connection/oracle-connect.php');
$conn = getOracleConnection();

$carid = intval($_GET['id'] ?? 1);

$sql = "SELECT car_image FROM car WHERE carid = :id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $carid);
oci_execute($stmt);

$row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_LOBS);

if (!empty($row['CAR_IMAGE'])) {
    $img = $row['CAR_IMAGE'];

    // Try detecting MIME type
    $mimeType = 'application/octet-stream'; // default fallback
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detected = finfo_buffer($finfo, $img);
        if ($detected) $mimeType = $detected;
        finfo_close($finfo);
    }

    // Manual fallback based on signature bytes
    if (substr($img, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") {
        $mimeType = 'image/png';
    } elseif (substr($img, 0, 3) === "\xFF\xD8\xFF") {
        $mimeType = 'image/jpeg';
    } elseif (substr($img, 0, 6) === "GIF87a" || substr($img, 0, 6) === "GIF89a") {
        $mimeType = 'image/gif';
    } elseif (substr($img, 0, 4) === "RIFF" && substr($img, 8, 4) === "WEBP") {
        $mimeType = 'image/webp';
    }

    header("Content-Type: $mimeType");
    header("Content-Length: " . strlen($img));
    echo $img;
} else {
    // fallback placeholder
    $placeholder = '../images/placeholder.png';
    if (file_exists($placeholder)) {
        header("Content-Type: image/png");
        readfile($placeholder);
    } else {
        echo "No image available";
    }
}

oci_free_statement($stmt);
