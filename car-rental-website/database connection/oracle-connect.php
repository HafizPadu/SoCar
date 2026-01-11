<?php

function getOracleConnection()
{
    $username = 'CAR_PROJECT';
    $password = 'CAR123';
    $connection_string = 'localhost/FREEPDB1';

    $conn = oci_pconnect($username, $password, $connection_string);

    if (!$conn) {
        $e = oci_error();
        die('Oracle connection failed: ' . htmlentities($e['message']));
    }

    return $conn;
    echo "DB FILE LOADED OK";
exit;
}
