<?php $title = 'Room'; ?>
<?php include_once 'layout/header.php'; ?>
<?php

$sql_house = "SELECT * FROM `houses`";
$result_house = mysqli_query($conn, $sql_house);

$sql_room = "SELECT * FROM `rooms`";
$result_room = mysqli_query($conn, $sql_room);

$room_types = array(
    'Single',
    'Double',
    'Triple',
    'Quad',
    'Queen',
    'King',
    'Twin',
    'Double-double',
    'Studio',
    'Master',
);

$room_furnishings = array(
    'Fully Furnished',
    'Partially Furnished',
    'Unfurnished',
);

$room_bathrooms = array(
    'Private',
    'Shared',
);

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    $sql_room_edit = "SELECT * FROM `rooms` WHERE `room_id` = '$room_id'";
    $result_room_edit = mysqli_query($conn, $sql_room_edit);

    if (mysqli_num_rows($result_room_edit) > 0) {
        $row_room_edit = mysqli_fetch_assoc($result_room_edit);

        $room_house_id = $row_room_edit['room_house_id'];
        $room_name = $row_room_edit['room_name'];
        $room_type = $row_room_edit['room_type'];
        $room_furnishing = $row_room_edit['room_furnishing'];
        $room_price = $row_room_edit['room_price'];
        $room_bathroom = $row_room_edit['room_bathroom'];
        $room_deposit = $row_room_edit['room_deposit'];
        $room_monthly_rental = $row_room_edit['room_monthly_rental'];
        $room_description = $row_room_edit['room_description'];
        $room_image = $row_room_edit['room_image'];
    } else {
        set_flash_mesage('Room not found.', 'warning');
        redirect('room.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_house_id = $_POST['room_house_id'];
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type'];
    $room_type = strtolower($room_type);
    $room_furnishing = $_POST['room_furnishing'];
    $room_price = $_POST['room_price'];
    $room_bathroom = $_POST['room_bathroom'];
    $room_deposit = $_POST['room_deposit'];
    $room_monthly_rental = $_POST['room_monthly_rental'];
    $room_description = $_POST['room_description'];

    $errors = [];

    if (empty($room_house_id)) {
        $errors['room_house_id'] = 'House is required';
    }

    if (empty($room_name)) {
        $errors['room_name'] = 'Room name is required';
    }

    if (empty($room_type)) {
        $errors['room_type'] = 'Room type is required';
    }

    if (empty($room_furnishing)) {
        $errors['room_furnishing'] = 'Room furnishing is required';
    } else if (!in_array($room_furnishing, $room_furnishings)) {
        $errors['room_furnishing'] = 'Room furnishing is invalid';
    } else {
        $room_furnishing = strtolower($room_furnishing);
    }

    if (empty($room_price)) {
        $errors['room_price'] = 'Room price is required';
    }

    if (empty($room_bathroom)) {
        $errors['room_bathroom'] = 'Room bathroom is required';
    } else if (!in_array($room_bathroom, $room_bathrooms)) {
        $errors['room_bathroom'] = 'Room bathroom is invalid';
    } else {
        $room_bathroom = strtolower($room_bathroom);
    }

    if (empty($room_deposit)) {
        $errors['room_deposit'] = 'Room deposit is required';
    } else if (!is_numeric($room_deposit)) {
        $errors['room_deposit'] = 'Room deposit is invalid';
    }

    if (empty($room_monthly_rental)) {
        $errors['room_monthly_rental'] = 'Room monthly rental is required';
    } else if (!is_numeric($room_monthly_rental)) {
        $errors['room_monthly_rental'] = 'Room monthly rental is invalid';
    }

    if (empty($room_description)) {
        $errors['room_description'] = 'Room description is required';
    }

    if (!empty($_FILES['room_image']['name'])) {
        $room_image = $_FILES['room_image']['name'];
        $room_image_tmp = $_FILES['room_image']['tmp_name'];

        $room_image_extension = pathinfo($room_image, PATHINFO_EXTENSION);

        $room_image_new_name = uniqid() . '.' . $room_image_extension;

        $room_image_destination = '../assets/images/rooms/' . $room_image_new_name;

        if (!in_array($room_image_extension, ['jpg', 'jpeg', 'png'])) {
            $errors['room_image'] = 'Room image extension is invalid';
        }

        if ($_FILES['room_image']['size'] > 10000000) {
            $errors['room_image'] = 'Room image size exceeds limit';
        }

        if (file_exists($room_image_destination)) {
            $errors['room_image'] = 'Room image already exists';
        }

        if (empty($errors)) {
            unlink('../assets/images/rooms/' . $room_image);
        } else {
            $room_image_new_name = $room_image;
        }
    } else {
        $room_image_new_name = $room_image;
    }



    if (empty($errors)) {
        $sql_room_update = "UPDATE `rooms` SET `room_house_id` = '$room_house_id', `room_name` = '$room_name', `room_type` = '$room_type', `room_furnishing` = '$room_furnishing', `room_price` = '$room_price', `room_bathroom` = '$room_bathroom', `room_deposit` = '$room_deposit', `room_monthly_rental` = '$room_monthly_rental', `room_description` = '$room_description', `room_image` = '$room_image_new_name' WHERE `room_id` = '$room_id'";

        if (mysqli_query($conn, $sql_room_update)) {
            if (!empty($_FILES['room_image']['name'])) {
                move_uploaded_file($room_image_tmp, $room_image_destination);
            }
            set_flash_mesage('Room updated successfully.', 'success');
        } else {
            set_flash_mesage('Room updation failed.', 'danger');
        }
        redirect('room.php');
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
                                Room Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <?= display_flash_message() ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group row">
                                            <label for="room_name" class="col-sm-3 col-form-label">House Name</label>
                                            <div class="col-sm-9">
                                                <select name="room_house_id" id="room_house_id" class="form-control <?= isset($errors['room_house_id']) ? 'is-invalid' : '' ?>">
                                                    <option value="">-- Select House --</option>
                                                    <?php while ($row_house = mysqli_fetch_assoc($result_house)) : ?>
                                                        <option value="<?= $row_house['house_id'] ?>" <?= isset($room_house_id) && $room_house_id == $row_house['house_id'] ? 'selected' : '' ?>><?= $row_house['house_name'] ?></option>
                                                    <?php endwhile ?>
                                                </select>
                                                <?php if (isset($errors['room_house_id'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_house_id'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_name" class="col-sm-3 col-form-label">Room Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control <?= isset($errors['room_name']) ? 'is-invalid' : '' ?>" id="room_name" name="room_name" placeholder="Room Name" value="<?= isset($room_name) ? $room_name : '' ?>">
                                                <?php if (isset($errors['room_name'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_name'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_type" class="col-sm-3 col-form-label">Room Type</label>
                                            <div class="col-sm-3">
                                                <select name="room_type" id="room_type" class="form-control <?= isset($errors['room_type']) ? 'is-invalid' : '' ?>">
                                                    <option value="">-- Select Room Type --</option>
                                                    <?php foreach ($room_types as $roomType) : ?>
                                                        <option value="<?= $roomType ?>" <?= isset($room_type) && strtolower($room_type) == strtolower($roomType) ? 'selected' : '' ?>><?= $roomType ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <?php if (isset($errors['room_type'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_type'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <label for="room_furnishing" class="col-sm-3 col-form-label">Room Furnishing</label>
                                            <div class="col-sm-3">
                                                <select name="room_furnishing" id="room_furnishing" class="form-control <?= isset($errors['room_furnishing']) ? 'is-invalid' : '' ?>">
                                                    <option value="" class="text-center">-- Select --</option>
                                                    <?php foreach ($room_furnishings as $roomFurnishing) : ?>
                                                        <option value="<?= $roomFurnishing ?>" <?= isset($room_furnishing) && strtolower($room_furnishing) == strtolower($roomFurnishing) ? 'selected' : '' ?>><?= $roomFurnishing ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <?php if (isset($errors['room_furnishing'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_furnishing'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_price" class="col-sm-3 col-form-label">Room Price</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control <?= isset($errors['room_price']) ? 'is-invalid' : '' ?>" id="room_price" name="room_price" placeholder="Room Price" value="<?= isset($room_price) ? $room_price : '' ?>" min="0" step="0.01">
                                                <?php if (isset($errors['room_price'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_price'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <label for="room_bathroom" class="col-sm-3 col-form-label">Room Bathroom</label>
                                            <div class="col-sm-3">
                                                <select name="room_bathroom" id="room_bathroom" class="form-control <?= isset($errors['room_bathroom']) ? 'is-invalid' : '' ?>">
                                                    <option value="" class="text-center">-- Select --</option>
                                                    <?php foreach ($room_bathrooms as $roomBathroom) : ?>
                                                        <option value="<?= $roomBathroom ?>" <?= isset($room_bathroom) && strtolower($room_bathroom) == strtolower($roomBathroom) ? 'selected' : '' ?>><?= $roomBathroom ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <?php if (isset($errors['room_bathroom'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_bathroom'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_deposit" class="col-sm-3 col-form-label">Room Deposit</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control <?= isset($errors['room_deposit']) ? 'is-invalid' : '' ?>" id="room_deposit" name="room_deposit" placeholder="Room Deposit" value="<?= isset($room_deposit) ? $room_deposit : '' ?>" min="0" step="0.01">
                                                <?php if (isset($errors['room_deposit'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_deposit'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <label for="room_monthly_rental" class="col-sm-3 col-form-label">Room Monthly Rental</label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control <?= isset($errors['room_monthly_rental']) ? 'is-invalid' : '' ?>" id="room_monthly_rental" name="room_monthly_rental" placeholder="Room Monthly Rental" value="<?= isset($room_monthly_rental) ? $room_monthly_rental : '' ?>" min="0" step="0.01">
                                                <?php if (isset($errors['room_monthly_rental'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_monthly_rental'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="room_description" class="col-sm-3 col-form-label">Room Description</label>
                                            <div class="col-sm-9">
                                                <textarea name="room_description" id="room_description" rows="3" class="form-control"><?= isset($room_description) ? $room_description : '' ?></textarea>
                                                <?php if (isset($errors['room_description'])) : ?>
                                                    <div class="invalid-feedback">
                                                        <?= $errors['room_description'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-9 offset-sm-3">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <a href="room.php" class="btn btn-secondary">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <label for="room_image">Room Image</label>
                                            </div>
                                            <div class="text-center">
                                                <?php if (file_exists('../assets/images/rooms/' . $room_image)) : ?>
                                                    <img src="../assets/images/rooms/<?= $room_image ?>" class="img-fluid img-thumbnail w-100 <?= isset($errors['room_image']) ? 'img-thumbnail-error' : '' ?>" id="room_image_preview" alt="Room Image" style="max-height: 300px; max-width: 300px;" onclick="clickRoomImage()">
                                                <?php else : ?>
                                                    <img src="https://via.placeholder.com/300x300" class="img-fluid img-thumbnail w-100 <?= isset($errors['room_image']) ? 'img-thumbnail-error' : '' ?>" id="room_image_preview" alt="Room Image" style="max-height: 300px; max-width: 300px;" onclick="clickRoomImage()">
                                                <?php endif ?>
                                            </div>
                                            <div class="text-center">
                                                <input type="file" hidden name="room_image" id="room_image" class="form-control <?= isset($errors['room_image']) ? 'is-invalid' : '' ?>" accept="image/*">
                                                <?php if (isset($errors['room_image'])) : ?>
                                                    <div class="invalid-feedback font-weight-bold">
                                                        <?= $errors['room_image'] ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
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
    function clickRoomImage() {
        document.querySelector('#room_image').click();
    }

    function previewRoomImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.querySelector('#room_image_preview').setAttribute('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.querySelector('#room_image').addEventListener('change', function() {
        previewRoomImage(this);
    });
</script>