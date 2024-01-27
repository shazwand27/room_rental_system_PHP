<?php require_once('../config/db.php'); ?>
<?php if (!isset($_SESSION['admin'])) redirect(base_url('../admin/login.php')); ?>
<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $sql = "UPDATE `tenants` SET `tenant_deleted_at` = CURRENT_TIMESTAMP WHERE `tenant_id` = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = array(
            'status' => '1',
            'message' => 'Tenant deleted successfully!',
        );
    } else {
        $data = array(
            'status' => '0',
            'message' => 'Failed to delete tenant!',
        );
    }
} else {
    $data = array(
        'status' => '0',
        'message' => 'Invalid request method!',
    );
}
echo json_encode($data);