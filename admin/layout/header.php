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


$sql_rent = "SELECT * FROM `rents` JOIN `tenants` ON `rent_tenant_id` = `tenant_id` JOIN `rooms` ON `tenant_room_id` = `room_id` JOIN `houses` ON `room_house_id` = `house_id` JOIN `users` ON `tenant_user_id` = `user_id` JOIN `states` ON `house_state_id` = `state_id` WHERE `rent_deleted_at` IS NULL AND `tenant_deleted_at` IS NULL AND `room_deleted_at` IS NULL AND `house_deleted_at` IS NULL ORDER BY `rent_created_at` DESC";
$result_rent = mysqli_query($conn, $sql_rent);

$total_outstanding = 0;
foreach ($result_rent as $rent) {
    $rend_id = $rent['rent_id'];
    $sql_payment = "SELECT * FROM `payments` WHERE `payment_rent_id` = $rend_id";
    $result_payment = mysqli_query($conn, $sql_payment);
    $total_paid = 0;
    // calculate total need pay from month stay in the room - payments
    $rent_start_date = $rent['rent_start_date'];
    $current_date = date('Y-m-d');

    $diff = abs(strtotime($current_date) - strtotime($rent_start_date));

    $years = floor($diff / (365 * 60 * 60 * 24));

    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)) + 1;

    $total_month = $years * 12 + $months;

    $total_need_pay = $total_month * $rent['room_monthly_rental'];

    while ($payment = mysqli_fetch_assoc($result_payment)) {
        $total_paid += $payment['payment_amount'];
    }

    $payment_outstanding = $total_need_pay - $total_paid;

    $total_outstanding += $payment_outstanding;
}

$count = array(
    'user' => mysqli_num_rows($result_users),
    'admin' => $admin,
    'staff' => $staff,
    'tenant' => $tenant,
    'house' => mysqli_num_rows($result_houses),
    'room' => mysqli_num_rows($result_rooms),
    'total_outstanding' => $total_outstanding
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

    <!-- select2 -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include_once 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- siderbar -->
        <?php include_once 'sidebar.php'; ?>
        <!-- end sidebar -->