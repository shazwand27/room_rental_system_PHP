<?php
// check if user is logged in
include_once '../config/db.php';
if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php'));


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_house = "SELECT * FROM `houses` WHERE `house_id` = '$id' AND `house_deleted_at` IS NULL";
    $result_house = mysqli_query($conn, $sql_house);

    if (mysqli_num_rows($result_house) == 0) {
        $_SESSION['message'] = alert('House not found.', 'danger');
        redirect('house.php');
    }

    $sql_house_delete = "UPDATE `houses` SET `house_deleted_at` = NOW() WHERE `house_id` = '$id'";
    $result_house_delete = mysqli_query($conn, $sql_house_delete);

    if ($result_house_delete) {
        $_SESSION['message'] = alert('House deleted successfully.', 'success');
        // DELETE IMAGE
        $row = mysqli_fetch_assoc($result_house);
        $image = $row['house_image'];
        unlink('../uploads/' . $image);
    } else {
        $_SESSION['message'] = alert('House deleted failed.', 'danger');
    }
    redirect('house.php');
}
