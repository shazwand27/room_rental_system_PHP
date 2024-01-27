<?php require_once('../config/db.php'); ?>
<?php if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php')); ?>
<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['id'];

    $sql = "DELETE FROM `payments` WHERE `payment_id` = '$payment_id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = array(
            'status' => '1',
            'message' => 'Payment deleted successfully!',
        );
    } else {
        $data = array(
            'status' => '0',
            'message' => 'Failed to delete payment!',
        );
    }
    echo json_encode($data);
} else {
    $data = array(
        'status' => '0',
        'message' => 'Invalid request method!',
    );
    echo json_encode($data);
}
