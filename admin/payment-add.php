<?php require_once('../config/db.php'); ?>
<?php if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php')); ?>
<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_rent_id = $_POST['payment_rent_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO `payments` (`payment_rent_id`, `payment_amount`, `payment_method`,`payment_type`) VALUES ('$payment_rent_id', '$payment_amount', '$payment_method','rent')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = array(
            'status' => '1',
            'message' => 'Payment added successfully!',
        );
    } else {
        $data = array(
            'status' => '0',
            'message' => 'Failed to add payment!',
        );
    }
} else {
    $data = array(
        'status' => '0',
        'message' => 'Invalid request method!',
    );
}

echo json_encode($data);
