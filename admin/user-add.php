<?php

if (isset($_GET['role'])) {
    $role = $_GET['role'];

    if ($role == 'tenant') {
        $title = 'List Tenant';
    } else {
        $title = ucfirst($role);
    }
} else {
    $role = '';
    $title = 'User';
}
?>
<?php
include_once 'layout/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if ($role != 'tenant') {
        $username = $_POST['username'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
    } else {
        $emergency_contact = $_POST['emergency_contact'];
        $occupation = $_POST['occupation'];
        $work_address = $_POST['work_address'];
        $work_contact = $_POST['work_contact'];
    }

    $error = [];

    if (empty($name)) {
        $error['name'] = 'Name is required';
    } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $error['name'] = 'Only letters and white space allowed';
    }

    if (empty($phone)) {
        $error['phone'] = 'Phone is required';
    } else if (!is_numeric($phone)) {
        $error['phone'] = 'Phone must be numeric';
    }



    if (empty($email)) {
        $error['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email';
    } else {
        $sql = "SELECT * FROM users WHERE user_email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error['email'] = 'Email already exists';
        }
    }
    if ($role != 'tenant') {
        if (empty($username)) {
            $error['username'] = 'Username is required';
        } else {
            $sql = "SELECT * FROM users WHERE user_username = '$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $error['username'] = 'Username already exists';
            }
        }
        if (empty($role)) {
            $error['role'] = 'Role is required';
        } else if ($role != 'admin' && $role != 'staff') {
            $error['role'] = 'Invalid role';
        }

        if (empty($password)) {
            $error['password'] = 'Password is required';
        } else if (strlen($password) < 6) {
            $error['password'] = 'Password must be at least 6 characters';
        } else {
            $error_password = array();
            if (!preg_match("#[0-9]+#", $password)) {
                $error_password[] = "Password must include at least one number!";
            }
            if (!preg_match("#[a-zA-Z]+#", $password)) {
                $error_password[] = "Password must include at least one letter!";
            }
            if (!preg_match("#[A-Z]+#", $password)) {
                $error_password[] = "Password must include at least one uppercase letter!";
            }

            if (!empty($error_password)) {
                $error['password'] = implode('<br>', $error_password);
            }
        }

        if (empty($confirm_password)) {
            $error['confirm_password'] = 'Confirm password is required';
        } else if ($password != $confirm_password) {
            $error['confirm_password'] = 'Confirm password does not match';
        }
    } else {
        if (empty($emergency_contact)) {
            $error['emergency_contact'] = 'Emergency contact is required';
        } else if (!is_numeric($emergency_contact)) {
            $error['emergency_contact'] = 'Emergency contact must be numeric';
        }

        if (empty($occupation)) {
            $error['occupation'] = 'Occupation is required';
        }

        if (empty($work_address)) {
            $error['work_address'] = 'Work address is required';
        }

        if (empty($work_contact)) {
            $error['work_contact'] = 'Work contact is required';
        } else if (!is_numeric($work_contact)) {
            $error['work_contact'] = 'Work contact must be numeric';
        }
    }


    if (empty($error)) {
        if ($role != 'tenant') {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user_name, user_phone, user_username, user_email, user_role, user_password) VALUES ('$name', '$phone', '$username', '$email', '$role', '$password')";
        } else {
            $sql = "INSERT INTO users (user_name, user_phone, user_email, user_emergency_contact, user_occupation, user_work_address, user_work_contact, user_role) VALUES ('$name', '$phone', '$email', '$emergency_contact', '$occupation', '$work_address', '$work_contact', '$role')";
        }
        $result = mysqli_query($conn, $sql);

        if ($result) {
            set_flash_mesage($role . ' has been added.', 'success');
        } else {
            set_flash_mesage('Failed to add user.', 'danger');
        }
        redirect('user.php?role=' . $role);
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <?= $title ?> Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control <?= (isset($error['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
                                        <?php if (isset($error['name'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= $error['name'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-4">
                                        <input type="email" class="form-control <?= (isset($error['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                                        <?php if (isset($error['email'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= $error['email'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="col-sm-2 col-form-label">Phone Number</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control <?= (isset($error['phone'])) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
                                        <?php if (isset($error['phone'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= $error['phone'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($role != 'tenant') : ?>
                                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control <?= (isset($error['username'])) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
                                            <?php if (isset($error['username'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['username'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <label for="emergency_contact" class="col-sm-2 col-form-label">Emergency Contact</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control <?= (isset($error['emergency_contact'])) ? 'is-invalid' : '' ?>" id="emergency_contact" name="emergency_contact" value="<?= isset($_POST['emergency_contact']) ? $_POST['emergency_contact'] : '' ?>">
                                            <?php if (isset($error['emergency_contact'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['emergency_contact'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($role != 'tenant') : ?>
                                    <div class="form-group row">
                                        <label for="role" class="col-sm-2 col-form-label">Role</label>
                                        <div class="col-sm-4">
                                            <select name="role" id="role" class="form-control <?= (isset($error['role'])) ? 'is-invalid' : '' ?>">
                                                <option value="">Select Role</option>
                                                <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                                <option value="staff" <?= (isset($_POST['role']) && $_POST['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                                            </select>
                                            <?php if (isset($error['role'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['role'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" class="form-control <?= (isset($error['password'])) ? 'is-invalid' : '' ?>" id="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                                            <?php if (isset($error['password'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['password'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" class="form-control <?= (isset($error['confirm_password'])) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" value="<?= isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '' ?>">
                                            <?php if (isset($error['confirm_password'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['confirm_password'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="form-group row">
                                        <label for="occupation" class="col-sm-2 col-form-label">Occupation</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control <?= (isset($error['occupation'])) ? 'is-invalid' : '' ?>" id="occupation" name="occupation" value="<?= isset($_POST['occupation']) ? $_POST['occupation'] : '' ?>">
                                            <?php if (isset($error['occupation'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['occupation'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <!-- work address -->
                                        <label for="work_address" class="col-sm-2 col-form-label">Work Address</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control <?= (isset($error['work_address'])) ? 'is-invalid' : '' ?>" id="work_address" name="work_address" value="<?= isset($_POST['work_address']) ? $_POST['work_address'] : '' ?>">
                                            <?php if (isset($error['work_address'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['work_address'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="work_contact" class="col-sm-2 col-form-label">Work Contact</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control <?= (isset($error['work_contact'])) ? 'is-invalid' : '' ?>" id="work_contact" name="work_contact" value="<?= isset($_POST['work_contact']) ? $_POST['work_contact'] : '' ?>">
                                            <?php if (isset($error['work_contact'])) : ?>
                                                <div class="invalid-feedback">
                                                    <?= $error['work_contact'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group row">
                                    <!-- submit -->
                                    <div class="col-sm-2 offset-sm-2">
                                        <button type="submit" class="btn btn-primary btn-block">Add <?= ucfirst($role) ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<?php include_once 'layout/footer.php'; ?>