<?php $title = 'Room'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
$sql_room = "SELECT * FROM `rooms` JOIN `houses` ON `room_house_id` = `house_id` WHERE `room_deleted_at` IS NULL AND `house_deleted_at` IS NULL";
$result_room = mysqli_query($conn, $sql_room);
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
                                <a href="<?= base_url('admin/room-add.php') ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add <?= $title ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= display_flash_message() ?>
                            <table id="rooms" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Room Name</th>
                                        <th>Room Details</th>
                                        <th>Room Description</th>
                                        <th>Room Image</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($result_room as $room) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $room['room_name'] ?></td>
                                            <td>
                                                <ul>
                                                    <li>House Name: <?= $room['house_name'] ?></li>
                                                    <li>Room Type: <?= $room['room_type'] ?></li>
                                                    <li>Room Price: <?= $room['room_price'] ?></li>
                                                    <li>Room Furnishing: <?= $room['room_furnishing'] ?></li>
                                                    <li>Room Bathroom: <?= $room['room_bathroom'] ?></li>
                                                    <li>Room Deposit: <?= $room['room_deposit'] ?></li>
                                                    <li>Room Monthly Rental: <?= $room['room_monthly_rental'] ?></li>
                                                </ul>
                                            </td>
                                            <td><?= $room['room_description'] ?></td>
                                            <td>
                                                <img src="<?= base_url('assets/images/rooms/' . $room['room_image']) ?>" alt="<?= $room['room_name'] ?>" class="img-fluid img-thumbnail" width="100px" onclick="openImageModal('<?= base_url('assets/images/rooms/' . $room['room_image']) ?>')">
                                            </td>
                                            <td><?= date('d-M-Y h:i A', strtotime($room['room_created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/room-edit.php?id=' . $room['room_id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="roomDelete(<?= $room['room_id'] ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
        $("#rooms").DataTable({
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
            imageAlt: 'Room Image',
            confirmButtonText: 'Close',
        })
    }

    function roomDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this room?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/room-delete.php?id=') ?>" + id;
            }
        })
    }
</script>