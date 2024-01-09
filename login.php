<?php require_once "config/db.php"; ?>
<?php
if (isset($_SESSION['admin'])) {
    redirect('admin/index.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $error = [];

        if (empty($username)) {
            $error['username'] = 'Username is required';
        }

        if (empty($password)) {
            $error['password'] = 'Password is required';
        }

        if (empty($error)) {
            $sql = "SELECT * FROM users WHERE user_username = '$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['user_password'])) {
                    if ($user['user_role'] == 'admin') {
                        $_SESSION['admin'] = $user['user_id'];
                        redirect('admin/index.php');
                    } else {
                        $_SESSION['user'] = $user;
                        redirect('index.php');
                    }
                } else {
                    $error['password'] = 'Password is incorrect';
                }
            } else {
                $error['username'] = 'Username is incorrect';
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
    <title>ROOM RENTAL MANAGEMENT SYSTEM | Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css?v=3.2.0') ?>">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= base_url('login.php') ?>" class="h6"><b>ROOM RENTAL MANAGEMENT SYSTEM</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="post">
                    <?php if (isset($_SESSION['message'])) : ?>
                        <?= $_SESSION['message'] ?>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control <?= (isset($error['username'])) ? 'is-invalid' : '' ?>" placeholder="Username" value="<?= (isset($_POST['username'])) ? $_POST['username'] : '' ?>">
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
                        <input type="password" name="password" class="form-control <?= (isset($error['password'])) ? 'is-invalid' : '' ?>" placeholder="Password" value="<?= (isset($_POST['password'])) ? $_POST['password'] : '' ?>">
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
                    <div class="row">
                        <div class="col-4">
                            <button name="login" type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- forgot password -->
                        <div class="col-8 mt-1">
                            <span class="">Forgot password? <a href="<?= base_url('forgot-password.php') ?>">Reset</a></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <span>Don't have an account? <a href="<?= base_url('register.php') ?>">Register</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>

    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('assets/dist/js/adminlte.min.js?v=3.2.0') ?>"></script>
</body>

</html>