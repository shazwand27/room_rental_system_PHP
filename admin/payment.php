<?php $title = 'Payment'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
$sql_rent = "SELECT * FROM `rents` JOIN `tenants` ON `rent_tenant_id` = `tenant_id` JOIN `rooms` ON `tenant_room_id` = `room_id` JOIN `houses` ON `room_house_id` = `house_id` JOIN `users` ON `tenant_user_id` = `user_id` JOIN `states` ON `house_state_id` = `state_id` WHERE `rent_deleted_at` IS NULL AND `tenant_deleted_at` IS NULL AND `room_deleted_at` IS NULL AND `house_deleted_at` IS NULL ORDER BY `rent_created_at` DESC";
$result_rent = mysqli_query($conn, $sql_rent);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                List of <?= $title ?>
                            </h4>
                            <div class="card-tools">
                                <a href="<?= base_url('admin/house-add.php') ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add <?= $title ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= display_flash_message() ?>
                            <table id="houses" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tenant Details</th>
                                        <th>House Details</th>
                                        <th>Paid Amount</th>
                                        <th>Total Need to Pay</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($result_rent as $rent) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <p>
                                                    <strong>Name:</strong> <?= $rent['user_name'] ?><br>
                                                    <strong>Email:</strong> <?= $rent['user_email'] ?><br>
                                                    <strong>Phone:</strong> <?= $rent['user_phone'] ?><br>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <strong>House Name:</strong> <?= $rent['house_name'] ?><br>
                                                    <strong>House Address:</strong> <?= $rent['house_address'] ?><br>
                                                    <strong>House State:</strong> <?= $rent['state_name'] ?><br>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <strong>Amount:</strong> <?= $rent['room_monthly_rental'] ?><br>
                                                </p>
                                            </td>
                                            <td>
                                                <?php
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
                                                ?>
                                                RM <?= number_format($payment_outstanding, 2) ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/rental-edit.php?id=' . $rent['rent_id']) ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Add Payment
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?php include_once 'layout/footer.php'; ?>