<?php
// check if user is logged in
include_once '../config/db.php';
if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php'));


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_room = "SELECT * FROM `rooms` WHERE `room_id` = '$id' AND `room_deleted_at` IS NULL";
    $result_room = mysqli_query($conn, $sql_room);

    if (mysqli_num_rows($result_room) == 0) {
        $_SESSION['message'] = alert('Room not found.', 'danger');
        redirect('room.php');
    }

    $sql_room_delete = "UPDATE `rooms` SET `room_deleted_at` = NOW() WHERE `room_id` = '$id'";
    $result_room_delete = mysqli_query($conn, $sql_room_delete);

    if ($result_room_delete) {
        $_SESSION['message'] = alert('Room deleted successfully.', 'success');
        // DELETE IMAGE
        $row = mysqli_fetch_assoc($result_room);
        $image = $row['room_image'];
        unlink('../assets/images/rooms/' . $image);
    } else {
        $_SESSION['message'] = alert('Room deleted failed.', 'danger');
    }
    redirect('room.php');
}
