<?php
if (isset($_POST['edit_car'])) {
    $carid = $_POST['carid'];
    $carmodel = $_POST['carmodel'];
    $cartype = $_POST['cartype'];
    $priceperday = $_POST['priceperday'];
    $availability = $_POST['availability'];

    if (!empty($_FILES['car_image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['car_image']['tmp_name']);

        // Step 1: Prepare statement to update with EMPTY_BLOB() and return LOB locator
        $sql = "UPDATE car 
                SET carmodel=:carmodel, cartype=:cartype, priceperday=:priceperday, availability=:availability, 
                    car_image=EMPTY_BLOB() 
                WHERE carid=:carid 
                RETURNING car_image INTO :car_image";
        $stmt = oci_parse($conn, $sql);

        // Create a LOB descriptor
        $lob = oci_new_descriptor($conn, OCI_D_LOB);

        // Bind variables
        oci_bind_by_name($stmt, ':carmodel', $carmodel);
        oci_bind_by_name($stmt, ':cartype', $cartype);
        oci_bind_by_name($stmt, ':priceperday', $priceperday);
        oci_bind_by_name($stmt, ':availability', $availability);
        oci_bind_by_name($stmt, ':carid', $carid);
        oci_bind_by_name($stmt, ':car_image', $lob, -1, OCI_B_BLOB);

        // Execute without committing yet
        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            // Step 2: Write image data to the LOB
            if (!$lob->write($imageData)) {
                $e = oci_error($stmt);
                echo "Error writing LOB: " . $e['message'];
                $lob->free();
                oci_rollback($conn);
                exit;
            }

            $lob->free();

            // Step 3: Commit transaction
            oci_commit($conn);

            echo "<script>alert('Car updated successfully'); window.location='cars.php';</script>";
        } else {
            $e = oci_error($stmt);
            echo "Error updating car: " . $e['message'];
            oci_rollback($conn);
        }

    } else {
        // No image uploaded, just update other fields
        $sql = "UPDATE car 
                SET carmodel=:carmodel, cartype=:cartype, priceperday=:priceperday, availability=:availability 
                WHERE carid=:carid";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':carmodel', $carmodel);
        oci_bind_by_name($stmt, ':cartype', $cartype);
        oci_bind_by_name($stmt, ':priceperday', $priceperday);
        oci_bind_by_name($stmt, ':availability', $availability);
        oci_bind_by_name($stmt, ':carid', $carid);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if ($result) {
            echo "<script>alert('Car updated successfully'); window.location='cars.php';</script>";
        } else {
            $e = oci_error($stmt);
            echo "Error updating car: " . $e['message'];
        }
    }
}
?>
