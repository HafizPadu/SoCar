<?php
if (isset($_POST['add_car'])) {

    $carmodel    = $_POST['carmodel'];
    $cartype     = $_POST['cartype'];
    $priceperday = $_POST['priceperday'];
    $availability= $_POST['availability'];

    if (!empty($_FILES['car_image']['tmp_name'])) {

        $imageData = file_get_contents($_FILES['car_image']['tmp_name']);

        $sql = "INSERT INTO car
                (carid, carmodel, cartype, priceperday, availability, car_image, is_deleted)
                VALUES
                (car_seq.NEXTVAL, :carmodel, :cartype, :priceperday, :availability, EMPTY_BLOB(), 0)
                RETURNING car_image INTO :car_image";

        $stmt = oci_parse($conn, $sql);
        $lob  = oci_new_descriptor($conn, OCI_D_LOB);

        oci_bind_by_name($stmt, ':carmodel', $carmodel);
        oci_bind_by_name($stmt, ':cartype', $cartype);
        oci_bind_by_name($stmt, ':priceperday', $priceperday);
        oci_bind_by_name($stmt, ':availability', $availability);
        oci_bind_by_name($stmt, ':car_image', $lob, -1, OCI_B_BLOB);

        if (oci_execute($stmt, OCI_DEFAULT)) {
            $lob->write($imageData);
            oci_commit($conn);
            $lob->free();
        } else {
            $e = oci_error($stmt);
            die($e['message']);
        }

    } else {
        $sql = "INSERT INTO car
                (carid, carmodel, cartype, priceperday, availability, is_deleted)
                VALUES
                (car_seq.NEXTVAL, :carmodel, :cartype, :priceperday, :availability, 0)";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':carmodel', $carmodel);
        oci_bind_by_name($stmt, ':cartype', $cartype);
        oci_bind_by_name($stmt, ':priceperday', $priceperday);
        oci_bind_by_name($stmt, ':availability', $availability);

        oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    }

    echo "<script>alert('Car added successfully'); window.location='cars.php';</script>";
}
?>
