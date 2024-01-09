<?php require_once "config/db.php"; ?>
<?php

if (isset($_SESSION['admin'])) {
    redirect('admin/index.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role'];

        $error = [];

        if (empty($username)) {
            $error['username'] = 'Username is required';
        } else if (strlen($username) < 6) {
            $error['username'] = 'Username must be at least 6 characters';
        }

        if (empty($email)) {
            $error['email'] = 'Email is required';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = 'Email is invalid';
        }

        if (empty($password)) {
            $error['password'] = 'Password is required';
        } else {
            $err_password = [];
            if (strlen($password) < 6) {
                $err_password[] = 'Password must be at least 6 characters';
            } else if (!preg_match("#[0-9]+#", $password)) {
                $err_password[] = 'Password must contain at least 1 number';
            } else if (!preg_match("#[A-Z]+#", $password)) {
                $err_password[] = 'Password must contain at least 1 uppercase letter';
            } else if (!preg_match("#[a-z]+#", $password)) {
                $err_password[] = 'Password must contain at least 1 lowercase letter';
            }

            if (!empty($err_password)) {
                $error['password'] = implode('<br>', $err_password);
            }
        }

        if (empty($confirm_password)) {
            $error['confirm_password'] = 'Confirm password is required';
        }

        if (empty($role)) {
            $error['role'] = 'Role is required';
        }

        if (empty($error)) {
            if ($password != $confirm_password) {
                $error['confirm_password'] = 'Confirm password is not match';
            } else {
                $sql = "SELECT * FROM users WHERE user_username = '$username'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $error['username'] = 'Username is already exist';
                } else {
                    $sql = "SELECT * FROM users WHERE user_email = '$email'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $error['email'] = 'Email is already exist';
                    } else {
                        $password = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO users (user_username, user_email, user_password, user_role) VALUES ('$username', '$email', '$password', '$role')";
                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            $_SESSION['message'] = alert('Register success, now you can login', 'success');
                        } else {
                            $_SESSION['message'] = alert('Register failed', 'danger');
                        }
                        redirect('login.php');
                    }
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROOM RENTAL MANAGEMENT SYSTEM | Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css?v=3.2.0') ?>">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= base_url('register.php') ?>" class="h1"><b>Rent a Room</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register new account</p>
                <?php if (isset($_SESSION['message'])) : ?>
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control <?= (isset($error['username'])) ? 'is-invalid' : '' ?>" placeholder="Username" name="username" id="username" value="<?= (isset($_POST['username'])) ? $_POST['username'] : '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php if (isset($error['username'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['username'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control <?= (isset($error['email'])) ? 'is-invalid' : '' ?>" placeholder="Email" name="email" id="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <?php if (isset($error['email'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['email'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control <?= (isset($error['password'])) ? 'is-invalid' : '' ?>" placeholder="Password" name="password" id="password" value="<?= (isset($_POST['password'])) ? $_POST['password'] : '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if (isset($error['password'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control <?= (isset($error['confirm_password'])) ? 'is-invalid' : '' ?>" placeholder="Retype password" name="confirm_password" id="confirm_password" value="<?= (isset($_POST['confirm_password'])) ? $_POST['confirm_password'] : '' ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php if (isset($error['confirm_password'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['confirm_password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group mb-3">
                        <select name="role" id="role" class="form-control">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="tenant">Tenant</option>
                        </select>
                        <?php if (isset($error['role'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['role'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
                <a href="login.php" class="text-center">I already have a account</a>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>

    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('assets/dist/js/adminlte.min.js?v=3.2.0') ?>"></script>

    <script>
        $('#username').keypress(function(e) {
            var regex = new RegExp("^[a-zA-Z0-9_]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    </script>
</body>

</html>