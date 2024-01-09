<?php
// check if user is logged in
include_once '../config/db.php';
if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php'));


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_room = "SELECT * FROM `users` WHERE `user_id` = '$id'";
    $result_room = mysqli_query($conn, $sql_room);

    if (mysqli_num_rows($result_room) == 0) {
        $_SESSION['message'] = alert('User not found.', 'danger');
        redirect('user.php');
    }

    $sql_user_delete = "DELETE FROM `users` WHERE `user_id` = '$id'";
    $result_user_delete = mysqli_query($conn, $sql_user_delete);

    if ($result_user_delete) {
        $_SESSION['message'] = alert('User deleted successfully.', 'success');
    } else {
        $_SESSION['message'] = alert('User deleted failed.', 'danger');
    }
    redirect('user.php?role=staff');
}
