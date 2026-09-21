<?php
include 'db_connect.php'; // Ensure this has the .php extension

if (isset($_POST['register_child'])) {
    // Collect all data from the form
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $ext = $_POST['ext'];
    $dob = $_POST['dob'];
    $sex = $_POST['sex'];
    $blood_type = $_POST['blood_type'];
    $birth_place = $_POST['birth_place'];
    $b_weight = $_POST['b_weight'];
    $b_length = $_POST['b_length'];
    $b_head = $_POST['b_head'];
    $delivery_type = $_POST['delivery_type'];
    $attendant = $_POST['attendant'];
    
    // Checkboxes need special handling (1 if checked, 0 if not)
    $init_bcg = isset($_POST['init_bcg']) ? 1 : 0;
    $init_hepab = isset($_POST['init_hepab']) ? 1 : 0;
    
    $mother_name = $_POST['mother_name'];
    $father_name = $_POST['father_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    // The SQL Insert
    $sql = "INSERT INTO infants (lname, fname, mname, ext, dob, sex, blood_type, birth_place, b_weight, b_length, b_head, delivery_type, attendant, init_bcg, init_hepab, mother_name, father_name, address, contact) 
            VALUES ('$lname', '$fname', '$mname', '$ext', '$dob', '$sex', '$blood_type', '$birth_place', '$b_weight', '$b_length', '$b_head', '$delivery_type', '$attendant', '$init_bcg', '$init_hepab', '$mother_name', '$father_name', '$address', '$contact')";

    if ($conn->query($sql) === TRUE) {
        // This alert pops up, then sends them to the Vaccine 'Home' page
        echo "<script>
                alert('Registration Successful! Welcome to the Health Center.'); 
                window.location.href='vaccines.php';
            </script>";
        exit();
    }
    }
?>