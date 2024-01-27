<?php
// Start session
session_start();

// Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// DB credentials.
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "db_rent_a_room";

// Establish database connection.
$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$base_url = "http://localhost:8080/";

function base_url($url = null)
{
    global $base_url;
    if ($url != null) {
        return $base_url . $url;
    } else {
        return $base_url;
    }
}

function redirect($url)
{
    echo "<script>window.location.href='" . $url . "';</script>";
    exit;
}

function alert($msg, $type = 'info')
{
    $text = "<div class='alert alert-" . $type . " alert-dismissible fade show' role='alert'>";
    $text .= $msg;
    $text .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
    $text .= "<span aria-hidden='true'>&times;</span>";
    $text .= "</button>";
    $text .= "</div>";

    return $text;
}

function get_user_by_id($id)
{
    global $conn;
    $sql = "SELECT * FROM `users` WHERE `user_id` = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function set_flash_mesage($msg, $type = 'info')
{
    $_SESSION['message'] = alert($msg, $type);
}

function display_flash_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function checkTenant($room_id)
{
    global $conn;

    $sql = "SELECT * FROM `tenants` WHERE `tenant_deleted_at` IS NULL AND `tenant_room_id` = '$room_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $tenant = mysqli_fetch_assoc($result);
        return $tenant;
    } else {
        return false;
    }
}