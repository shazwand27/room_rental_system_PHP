<?php
require_once 'config/db.php';

if (isset($_SESSION['admin'])) {
    redirect('admin/index.php');
} else {
    redirect('login.php');
}
