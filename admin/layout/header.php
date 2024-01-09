<?php require_once('../config/db.php'); ?>
<?php if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php')); ?>
<?php
$sql_users = "SELECT * FROM `users`";
$result_users = mysqli_query($conn, $sql_users);

$admin = 0;
$staff = 0;
$tenant = 0;
while ($row = mysqli_fetch_assoc($result_users)) {
    if ($row['user_role'] == 'admin') {
        $admin++;
    } else if ($row['user_role'] == 'staff') {
        $staff++;
    } else if ($row['user_role'] == 'tenant') {
        $tenant++;
    }
}

$sql_houses = "SELECT * FROM `houses` WHERE `house_deleted_at` IS NULL";
$result_houses = mysqli_query($conn, $sql_houses);

$sql_rooms = "SELECT * FROM `rooms` WHERE `room_deleted_at` IS NULL";
$result_rooms = mysqli_query($conn, $sql_rooms);

$count = array(
    'user' => mysqli_num_rows($result_users),
    'admin' => $admin,
    'staff' => $staff,
    'tenant' => $tenant,
    'house' => mysqli_num_rows($result_houses),
    'room' => mysqli_num_rows($result_rooms),
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROOM RENTAL MANAGEMENT SYSTEM - <?= $title ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/jqvmap/jqvmap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker.css') ?>">
    <!-- summernote -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/summernote/summernote-bs4.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">

    <!-- owl carousel 2 -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/owlcarousel2/assets/owl.carousel.min.css') ?>">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include_once 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- siderbar -->
        <?php include_once 'sidebar.php'; ?>
        <!-- end sidebar -->