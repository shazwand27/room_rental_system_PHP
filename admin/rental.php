<?php $title = 'Rental'; ?>
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
                                        <th>Rent Details</th>
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
                                                    <strong>Room Name:</strong> <?= $rent['room_name'] ?><br>
                                                    <strong>Room Type:</strong> <?= $rent['room_type'] ?><br>
                                                    <strong>Room Monthly Rental:</strong> <?= $rent['room_monthly_rental'] ?><br>
                                                    <strong>Rent Start Date:</strong> <?= $rent['rent_start_date'] ?><br>
                                                    <strong>Rent End Date:</strong> <?= $rent['rent_end_date'] ?><br>
                                                    <strong>Rent Amount:</strong> <?= $rent['room_monthly_rental'] ?><br>
                                                </p>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/rental-edit.php?id=' . $rent['rent_id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> update
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