<?php $title = 'Update Tenant'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    $sql_room = "SELECT * FROM `rooms` JOIN `houses` ON `rooms`.`room_house_id` = `houses`.`house_id` JOIN `states` ON `houses`.`house_state_id` = `states`.`state_id` WHERE `room_deleted_at` IS NULL AND `room_id` = '$room_id' ORDER BY `room_created_at` DESC";
    $result_room = mysqli_query($conn, $sql_room);

    if (mysqli_num_rows($result_room) == 0) {
        $_SESSION['error'] = 'Room not found!';
        redirect('admin/room.php');
    }

    $room = mysqli_fetch_assoc($result_room);

    $house['house_id'] = $room['house_id'];
    $house['house_name'] = $room['house_name'];
    $house['house_address'] = $room['house_address'];
    $house['house_city'] = $room['house_city'];
    $house['house_postcode'] = $room['house_postcode'];
    $house['house_description'] = $room['house_description'];
    $house['house_image'] = $room['house_image'];
    $house['house_created_at'] = $room['house_created_at'];
    $house['state_name'] = $room['state_name'];

    $sql_users = "SELECT * FROM `users` WHERE `user_role` = 'tenant' AND NOT EXISTS (SELECT * FROM `tenants` WHERE `tenant_user_id` = `user_id` AND `tenant_deleted_at` IS NULL) ORDER BY `user_created_at` DESC";
    $result_users = mysqli_query($conn, $sql_users);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $tenant_user_id = $_POST['tenant_name'];

        $sql_tenant = "INSERT INTO `tenants` (`tenant_user_id`, `tenant_room_id`) VALUES ('$tenant_user_id', '$room_id')";
        $result_tenant = mysqli_query($conn, $sql_tenant);
        if ($result_tenant) {
            $tenant_id = $conn->insert_id;
            $sql_rent = "INSERT INTO `rents` (`rent_tenant_id`) VALUES ('$tenant_id')";
            $result_rent = mysqli_query($conn, $sql_rent);
            set_flash_mesage('Tenant added successfully!', 'success');
        } else {
            set_flash_mesage('Failed to add tenant!', 'danger');
        }
        redirect('tenant-update.php?id=' . $room_id);
    }

    // check tenant
    $sql_tenant = "SELECT * FROM `tenants` JOIN `users` ON `tenants`.`tenant_user_id` = `users`.`user_id` WHERE `tenant_deleted_at` IS NULL AND `tenant_room_id` = '$room_id' ORDER BY `tenant_created_at` DESC";
    $result_tenant = mysqli_query($conn, $sql_tenant);

    if (mysqli_num_rows($result_tenant) == 1) {
        $tenant = mysqli_fetch_assoc($result_tenant);
    }
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
                <div class="col-lg-6 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                House Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8 col-12">
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
                                <div class="col-lg-4 col-12">
                                    <img src="<?= base_url('uploads/' . $house['house_image']) ?>" alt="<?= $house['house_name'] ?>" class="img-fluid" style="max-height: 350px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Room Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8 col-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Room Name</th>
                                            <td><?= $room['room_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room Type</th>
                                            <td><?= $room['room_type'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room Price</th>
                                            <td><?= $room['room_price'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room Description</th>
                                            <td><?= $room['room_description'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room Created At</th>
                                            <td><?= date('d-m-Y h:i:s A', strtotime($room['room_created_at'])) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <img src="<?= base_url('assets/images/rooms/' . $room['room_image']) ?>" alt="<?= $room['room_name'] ?>" class="img-fluid" style="max-height: 350px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Tenant Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php if (mysqli_num_rows($result_tenant) == 1) : ?>
                                <div class="row">
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Tenant Name</th>
                                                <td><?= $tenant['user_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tenant Email</th>
                                                <td><?= $tenant['user_email'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tenant Phone</th>
                                                <td><?= $tenant['user_phone'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tenant Created At</th>
                                                <td><?= date('d-m-Y h:i:s A', strtotime($tenant['user_created_at'])) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <button type="button" class="btn btn-danger" onclick="deleteTenant(<?= $tenant['tenant_id'] ?>)">Delete Tenant</button>
                                        <div class="text-center align-self-center">
                                            <img src="<?= base_url('assets/dist/img/user1-128x128.jpg/') ?>" alt="<?= $tenant['user_name'] ?>" class="img-fluid" style="max-height: 350px;">
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="tenant_name">Tenant Name</label>
                                        <select name="tenant_name" id="tenant_name" class="form-control">
                                            <option value="">Select Tenant</option>
                                            <?php while ($user = mysqli_fetch_assoc($result_users)) : ?>
                                                <option value="<?= $user['user_id'] ?>"><?= $user['user_name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Add Tenant</button>
                                    </div>
                                </form>
                            <?php endif; ?>
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
        $('#tenant_name').select2({
            theme: 'bootstrap4'
        });
    });

    function deleteTenant(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this tenant?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/tenant-delete.php') ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "tenant-update.php?id=<?= $room_id ?>";
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            })
                        }
                    }
                });
            }
        });
    }
</script>