<?php $title = 'Add House'; ?>
<?php include_once 'layout/header.php';
$sql_states = "SELECT * FROM `states`";
$result_states = mysqli_query($conn, $sql_states);

if (isset($_GET['id'])) {
    $house_id = $_GET['id'];
    $sql_house = "SELECT * FROM `houses` WHERE `house_id` = '$house_id'";
    $result_house = mysqli_query($conn, $sql_house);

    if (mysqli_num_rows($result_house) == 0) {
        redirect('house.php');
    }
    $house = mysqli_fetch_assoc($result_house);
} else {
    redirect('house.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $house_name = $_POST['house_name'];
    $house_type = $_POST['house_type'];
    $house_address = $_POST['house_address'];
    $house_city = $_POST['house_city'];
    $house_postcode = $_POST['house_postcode'];
    $house_state = $_POST['house_state'];
    $house_country = $_POST['house_country'];
    $house_description = $_POST['house_description'];

    $errors = [];

    if (empty($house_name)) {
        $errors['house_name'] = 'House name is required';
    }

    if (empty($house_type)) {
        $errors['house_type'] = 'House type is required';
    }
    if (empty($house_address)) {
        $errors['house_address'] = 'House address is required';
    }

    if (empty($house_city)) {
        $errors['house_city'] = 'House city is required';
    }

    if (empty($house_postcode)) {
        $errors['house_postcode'] = 'House postcode is required';
    }

    if (empty($house_state)) {
        $errors['house_state'] = 'House state is required';
    }

    if (empty($house_description)) {
        $errors['house_description'] = 'House description is required';
    }

    if (!empty($_FILES['house_image']['name'])) {
        $house_image = $_FILES['house_image'];
        $house_image_name = $house_image['name'];
        $house_image_tmp = $house_image['tmp_name'];
        $house_image_size = $house_image['size'];
        $house_image_error = $house_image['error'];
        $house_image_type = $house_image['type'];

        $house_image_ext = explode('.', $house_image_name);
        $house_image_actual_ext = strtolower(end($house_image_ext));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($house_image_actual_ext, $allowed)) {
            if ($house_image_error === 0) {
                if ($house_image_size < 1000000) {
                    $house_image_new_name = uniqid('', true) . '.' . $house_image_actual_ext;
                    $house_image_destination = '../uploads/' . $house_image_new_name;
                } else {
                    $errors['house_image'] = 'House image is too big';
                }
            } else {
                $errors['house_image'] = 'There was an error uploading your house image';
            }
        } else {
            $errors['house_image'] = 'You cannot upload files of this type';
        }
    } else {
        $house_image_new_name = $house['house_image'];
    }

    if (empty($errors)) {
        $sql = "UPDATE `houses` SET `house_name` = '$house_name', `house_address` = '$house_address', `house_postcode` = '$house_postcode', `house_city` = '$house_city', `house_state_id` = '$house_state', `house_type` = '$house_type', `house_description` = '$house_description', `house_image` = '$house_image_new_name' WHERE `house_id` = '$house_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (!empty($_FILES['house_image']['name'])) {
                move_uploaded_file($house_image_tmp, $house_image_destination);
            }
            set_flash_mesage('House updated successfully', 'success');
        } else {
            set_flash_mesage('Failed to update house', 'danger');
        }
        redirect('house.php');
    }
}
?>
<style>
    .img-thumbnail-error {
        border: 1px solid red;
    }

    .img-thumbnail-error:hover {
        border: 1px solid red;
    }

    .img-thumbnail-error:focus {
        border: 1px solid red;
    }

    .img-thumbnail-error:active {
        border: 1px solid red;
    }
</style>
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
                                House Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label for="house_name" class="col-sm-2 col-form-label">House Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control <?= isset($errors['house_name']) ? 'is-invalid' : '' ?>" id="house_name" name="house_name" placeholder="House Name" value="<?= $house['house_name'] ?>">
                                                <?php if (isset($errors['house_name'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_name'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="house_type" class="col-sm-2 col-form-label">House Type</label>
                                            <div class="col-sm-4">
                                                <select class="form-control <?= isset($errors['house_type']) ? 'is-invalid' : '' ?>" id="house_type" name="house_type">
                                                    <option value="">Select House Type</option>
                                                    <option value="apartment" <?= $house['house_type'] == 'apartment' ? 'selected' : '' ?>>Apartment</option>
                                                    <option value="condominium" <?= $house['house_type'] == 'condominium' ? 'selected' : '' ?>>Condominium</option>
                                                    <option value="terrace" <?= $house['house_type'] == 'terrace' ? 'selected' : '' ?>>Terrace</option>
                                                    <option value="bungalow" <?= $house['house_type'] == 'bungalow' ? 'selected' : '' ?>>Bungalow</option>
                                                    <option value="semi-detached" <?= $house['house_type'] == 'semi-detached' ? 'selected' : '' ?>>Semi-Detached</option>
                                                    <option value="detached" <?= $house['house_type'] == 'detached' ? 'selected' : '' ?>>Detached</option>
                                                </select>
                                                <?php if (isset($errors['house_type'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_type'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="house_address" class="col-sm-2 col-form-label">House Address</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control <?= isset($errors['house_address']) ? 'is-invalid' : '' ?>" id="house_address" name="house_address" rows="3" placeholder="House Address"><?= $house['house_address'] ?></textarea>
                                                <?php if (isset($errors['house_address'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_address'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="house_city" class="col-sm-2 col-form-label">House City</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control <?= isset($errors['house_city']) ? 'is-invalid' : '' ?>" id="house_city" name="house_city" placeholder="House City" value="<?= $house['house_city'] ?>">
                                                <?php if (isset($errors['house_city'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_city'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <label for="house_postcode" class="col-sm-2 col-form-label">House Postcode</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control <?= isset($errors['house_postcode']) ? 'is-invalid' : '' ?>" id="house_postcode" name="house_postcode" placeholder="House Postcode" value="<?= $house['house_postcode'] ?>">
                                                <?php if (isset($errors['house_postcode'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_postcode'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="house_state" class="col-sm-2 col-form-label">House State</label>
                                            <div class="col-sm-4">
                                                <select class="form-control <?= isset($errors['house_state']) ? 'is-invalid' : '' ?>" id="house_state" name="house_state">
                                                    <option value="">Select House State</option>
                                                    <?php while ($row_states = mysqli_fetch_assoc($result_states)) : ?>
                                                        <option value="<?= $row_states['state_id'] ?>" <?= $house['house_state_id'] == $row_states['state_id'] ? 'selected' : '' ?>><?= $row_states['state_name'] ?></option>
                                                    <?php endwhile ?>
                                                </select>
                                                <?php if (isset($errors['house_state'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_state'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <label for="house_country" class="col-sm-2 col-form-label">House Country</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="house_country" name="house_country" placeholder="House Country" value="Malaysia" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <label for="house_image">House Image</label>
                                            </div>
                                            <div class="text-center">
                                                <?php if (file_exists('../uploads/' . $house['house_image'])) : ?>
                                                    <img src="<?= base_url('uploads/' . $house['house_image']) ?>" class="img-fluid img-thumbnail w-100 <?= isset($errors['house_image']) ? 'img-thumbnail-error' : '' ?>" id="house_image_preview" alt="House Image" style="max-height: 300px; max-width: 300px;" onclick="clickHouseImage()">
                                                <?php else : ?>
                                                    <img src="https://via.placeholder.com/300x300" class="img-fluid img-thumbnail w-100 <?= isset($errors['house_image']) ? 'img-thumbnail-error' : '' ?>" id="house_image_preview" alt="House Image" style="max-height: 300px; max-width: 300px;" onclick="clickHouseImage()">
                                                <?php endif ?>
                                            </div>
                                            <div class="text-center">
                                                <input type="file" hidden name="house_image" id="house_image" class="form-control <?= isset($errors['house_image']) ? 'is-invalid' : '' ?>" accept="image/*">
                                                <?php if (isset($errors['house_image'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['house_image'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="house_description" class="col-sm-1 col-form-label">House Description</label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control <?= isset($errors['house_description']) ? 'is-invalid' : '' ?>" id="house_description" name="house_description" rows="3" placeholder="House Description"><?= $house['house_description'] ?></textarea>
                                        <?php if (isset($errors['house_description'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= $errors['house_description'] ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary offset-sm-1">Submit</button>
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

<script>
    function clickHouseImage() {
        document.getElementById('house_image').click();
    }

    document.getElementById('house_image').addEventListener('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('house_image_preview').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);

        // remove error class
        document.getElementById('house_image_preview').classList.remove('img-thumbnail-error');
    });


    $('#house_price').on('keypress', function(e) {
        // number only and decimal point
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>