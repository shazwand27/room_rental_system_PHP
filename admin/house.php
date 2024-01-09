<?php $title = 'House'; ?>
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
                                        <th>House Name</th>
                                        <th>House Type</th>
                                        <th>House Location</th>
                                        <th>House Description</th>
                                        <th>House Image</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    while ($row_house = mysqli_fetch_assoc($result_house)) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row_house['house_name'] ?></td>
                                            <td><?= $row_house['house_type'] ?></td>
                                            <td>
                                                <?= $row_house['house_address'] ?>,<br>
                                                <?= $row_house['house_city'] . " " . $row_house['house_postcode'] ?>, <br>
                                                <?= $row_house['state_name'] ?>
                                            </td>
                                            <td><?= nl2br($row_house['house_description']) ?></td>
                                            <td>
                                                <img src="<?= base_url('uploads/' . $row_house['house_image']) ?>" alt="<?= $row_house['house_name'] ?>" class="img-fluid" width="100" height="100" onclick="openImageModal(this.src)">
                                            </td>
                                            <td><?= date('d/m/Y h:i A', strtotime($row_house['house_created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/house-edit.php?id=' . $row_house['house_id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="houseDelete(<?= $row_house['house_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
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