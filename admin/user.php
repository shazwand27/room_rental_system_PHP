<?php

if (isset($_GET['role'])) {
    $role = $_GET['role'];

    if ($role == 'tenant') {
        $title = 'List Tenant';
    } else {
        $title = ucfirst($role);
    }

    $sql_user = "SELECT * FROM `users` WHERE `user_role` = '$role'";
} else {
    $sql_user = "SELECT * FROM `users`";
}
?>
<?php include_once 'layout/header.php'; ?>
<?php
$result_user = mysqli_query($conn, $sql_user);
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
                            <div class="div card-tools">
                                <a href="<?= base_url('admin/user-add.php?role=' . $role) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add <?= $title ?>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= display_flash_message() ?>
                            <table id="users" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <?php if ($role != 'tenant') : ?>
                                            <th>Username</th>
                                        <?php else : ?>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Emergency Contact</th>
                                            <th>Work Details</th>
                                        <?php endif ?>
                                        <th>Email</th>
                                        <?php if ($role != 'tenant') : ?>
                                            <th>Role</th>
                                        <?php endif ?>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result_user as $key => $user) : ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <?php if ($role != 'tenant') : ?>
                                                <td><?= $user['user_username'] ?></td>
                                            <?php else : ?>
                                                <td><?= $user['user_name'] ?></td>
                                                <td><?= $user['user_phone'] ?></td>
                                                <td><?= $user['user_emergency_contact'] ?></td>
                                                <td>
                                                    <ul>
                                                        <li>Ocupation: <?= $user['user_occupation'] ?></li>
                                                        <li>Work Address: <?= $user['user_work_address'] ?></li>
                                                        <li>Work Phone: <?= $user['user_work_contact'] ?></li>
                                                    </ul>
                                                </td>
                                            <?php endif ?>
                                            <td><?= $user['user_email'] ?></td>
                                            <?php if ($role != 'tenant') : ?>
                                                <td>
                                                    <?php if ($user['user_role'] == 'admin') : ?>
                                                        <span class="badge badge-success"><i class="fas fa-user-shield"></i> <?= $user['user_role'] ?></span>
                                                    <?php else : ?>
                                                        <span class="badge badge-primary"><i class="fas fa-user"></i> <?= $user['user_role'] ?></span>
                                                    <?php endif ?>
                                                </td>
                                            <?php endif ?>
                                            <td><?= date('d/m/Y h:i A', strtotime($user['user_created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/user-edit.php?id=' . $user['user_id']) ?>&role=<?= $role ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="userDelete(<?= $user['user_id'] ?>)"><i class="fas fa-trash-alt"></i></button>
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
    $(document).ready(function() {
        $('#users').DataTable();
    });

    function userDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "user-delete.php?id=" + id;
            }
        })
    }
</script>