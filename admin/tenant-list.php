<?php $title = 'Tenant List'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $sql_tenant = "SELECT * FROM `tenants` JOIN `rooms` ON `tenant_room_id` = `room_id` JOIN `houses` ON `room_house_id` = `house_id` JOIN `users` ON `tenant_user_id` = `user_id` WHERE `tenant_deleted_at` IS NULL AND `house_id` = '$house_id' ORDER BY `tenant_created_at` DESC";
    $result_tenant = mysqli_query($conn, $sql_tenant);
} else {
    $_SESSION['error'] = 'House not found!';
    redirect('tenant.php');
}
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
                    <?= display_flash_message() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Tenant List
                            </h4>
                            <div class="card-tools">
                                <a href="<?= base_url('admin/tenant-add.php?id=' . $house_id) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Tenant
                                </a>
                                <a href="<?= base_url('admin/tenant.php') ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tenants" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tenant Name</th>
                                        <th>Tenant Email</th>
                                        <th>Tenant Phone</th>
                                        <th>Room Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($result_tenant as $tenant) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $tenant['user_name'] ?></td>
                                            <td><?= $tenant['user_email'] ?></td>
                                            <td><?= $tenant['user_phone'] ?></td>
                                            <td><?= $tenant['room_name'] ?></td>
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