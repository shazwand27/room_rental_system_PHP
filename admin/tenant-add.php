<?php $title = 'Add Tenant'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $sql_house = "SELECT * FROM `houses` JOIN `states` ON `houses`.`house_state_id` = `states`.`state_id`  WHERE `house_deleted_at` IS NULL AND `house_id` = '$house_id' ORDER BY `house_created_at` DESC";
    $result_house = mysqli_query($conn, $sql_house);

    if (mysqli_num_rows($result_house) == 0) {
        $_SESSION['error'] = 'House not found!';
        redirect('admin/house.php');
    }

    $house = mysqli_fetch_assoc($result_house);

    $sql_room = "SELECT * FROM `rooms` WHERE `room_deleted_at` IS NULL AND `room_house_id` = '$house_id' ORDER BY `room_created_at` DESC";
    $result_room = mysqli_query($conn, $sql_room);
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                House Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>House Name</th>
                                            <td><?= $house['house_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House Address</th>
                                            <td><?= $house['house_address'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House State</th>
                                            <td><?= $house['state_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House City</th>
                                            <td><?= $house['house_city'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House Zipcode</th>
                                            <td><?= $house['house_postcode'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House Description</th>
                                            <td><?= $house['house_description'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>House Created At</th>
                                            <td><?= date('d-m-Y h:i:s A', strtotime($house['house_created_at'])) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-4 text-center">
                                    <img src="<?= base_url('uploads/' . $house['house_image']) ?>" alt="<?= $house['house_name'] ?>" class="img-fluid" style="max-height: 350px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Room Available
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <th>Room Name</th>
                                    <th>Room Type</th>
                                    <th>Room Furnishing</th>
                                    <th>Room Price</th>
                                    <th>Room Deposit</th>
                                    <th>Room Monthly</th>
                                    <th>Room Status</th>
                                    <th>Room Created At</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result_room) > 0) : ?>
                                        <?php while ($room = mysqli_fetch_assoc($result_room)) : ?>
                                            <tr>
                                                <td><?= $room['room_name'] ?></td>
                                                <td><?= $room['room_type'] ?></td>
                                                <td><?= $room['room_furnishing'] ?></td>
                                                <td><?= $room['room_price'] ?></td>
                                                <td><?= $room['room_deposit'] ?></td>
                                                <td><?= $room['room_monthly_rental'] ?></td>
                                                <td>
                                                    <?php if (checkTenant($room['room_id'])) : ?>
                                                        <span class="badge badge-warning">Occupied</span>
                                                    <?php else : ?>
                                                        <span class="badge badge-success">Available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d-m-Y h:i:s A', strtotime($room['room_created_at'])) ?></td>
                                                <td>
                                                    <?php if (!checkTenant($room['room_id'])) : ?>
                                                        <a href="<?= base_url('admin/tenant-update.php?id=' . $room['room_id']) ?>" class="btn btn-sm btn-primary">Add Tenant</a>
                                                    <?php else : ?>
                                                        <a href="<?= base_url('admin/tenant-update.php?id=' . $room['room_id']) ?>&action=update" class="btn btn-sm btn-warning">Update Tenant</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No Room Available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>

<?php include_once 'layout/footer.php'; ?>