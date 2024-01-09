<?php $title = 'Tenant'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
$sql_house = "SELECT * FROM `houses` JOIN `states` ON `houses`.`house_state_id` = `states`.`state_id`  WHERE `house_deleted_at` IS NULL ORDER BY `house_created_at` DESC";
$result_house = mysqli_query($conn, $sql_house);
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
                                        <th>House Details</th>
                                        <th>Total Room</th>
                                        <th>Total Tenant</th>
                                        <th>House Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($result_house as $house) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <b>House Name:</b> <?= $house['house_name'] ?><br>
                                                <b>House Type:</b> <?= $house['house_type'] ?><br>
                                                <div class="row">
                                                    <div class="col-3">
                                                        <b>

                                                            House Location:
                                                        </b>
                                                    </div>
                                                    <div class="col-9">
                                                        <?= $house['house_address'] ?> <br> <?= $house['house_postcode'] ?> <?= $house['house_city'] ?>, <br><?= $house['state_name'] ?><br>
                                                    </div>
                                                </div>
                                                <b>House Description:</b> <?= $house['house_description'] ?><br>
                                                <b>House Created At:</b> <?= date('d M Y', strtotime($house['house_created_at'])) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $house_id = $house['house_id'];
                                                $sql_room = "SELECT * FROM `rooms` WHERE `room_house_id` = '$house_id' AND `room_deleted_at` IS NULL";
                                                $result_room = mysqli_query($conn, $sql_room);
                                                $count_room = mysqli_num_rows($result_room);
                                                ?>
                                                <?= $count_room ?>
                                            </td>
                                            <td>
                                                <?php
                                                // SELECT `tenant_id`, `tenant_user_id`, `tenant_room_id`, `tenant_status`, `tenant_created_at`, `tenant_updated_at`, `tenant_deleted_at` FROM `tenants` WHERE 1
                                                $sql_tenant = "SELECT * FROM `tenants` JOIN `rooms` ON `tenant_room_id` = `room_id` WHERE `room_house_id` = '$house_id' AND `tenant_deleted_at` IS NULL";
                                                $result_tenant = mysqli_query($conn, $sql_tenant);
                                                $count_tenant = mysqli_num_rows($result_tenant);
                                                ?>
                                                <?= $count_tenant ?>
                                            </td>
                                            <td>
                                                <img src="<?= base_url('uploads/' . $house['house_image']) ?>" alt="<?= $house['house_name'] ?>" class="img-fluid img-thumbnail" width="100px" onclick="openImageModal('<?= base_url('uploads/' . $house['house_image']) ?>')">
                                            </td>
                                            <td>
                                                <a href="tenant-add.php?id=<?= $house['house_id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus"></i> Add Tenant
                                                </a>
                                                <a href="tenant-list.php?id=<?= $house['house_id'] ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-list"></i> Tenant List
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

<script>
    $(function() {
        $("#houses").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    // sweet alert open image
    function openImageModal(src) {
        swal.fire({
            imageUrl: src,
            imageWidth: 600,
            imageHeight: 400,
            imageAlt: 'House Image',
            confirmButtonText: 'Close',
        })
    }

    function houseDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this house?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/house-delete.php?id=') ?>" + id;
            }
        })
    }
</script>