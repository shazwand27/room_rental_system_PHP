<?php $title = 'Rental Edit'; ?>
<?php include_once 'layout/header.php'; ?>
<?php
if (isset($_GET['id'])) {
    $rent_id = $_GET['id'];
    $sql_rent = "SELECT * FROM `rents` JOIN `tenants` ON `rent_tenant_id` = `tenant_id` JOIN `rooms` ON `tenant_room_id` = `room_id` JOIN `houses` ON `room_house_id` = `house_id` JOIN `users` ON `tenant_user_id` = `user_id` JOIN `states` ON `house_state_id` = `state_id` WHERE `rent_deleted_at` IS NULL AND `tenant_deleted_at` IS NULL AND `room_deleted_at` IS NULL AND `house_deleted_at` IS NULL AND `rent_id` = '$rent_id' ORDER BY `rent_created_at` DESC";
    $result_rent = mysqli_query($conn, $sql_rent);

    if (mysqli_num_rows($result_rent) == 0) {
        set_flash_mesage('Rental not found!', 'warning');
        redirect('rental.php');
    }
    $rent = mysqli_fetch_assoc($result_rent);

    $sql_payments = "SELECT * FROM `payments` WHERE `payment_rent_id` = '$rent_id' AND `payment_deleted_at` IS NULL ORDER BY `payment_created_at` DESC";
    $result_payments = mysqli_query($conn, $sql_payments);


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rent_deposit = $_POST['rent_deposit'];
        $rent_start_date = $_POST['rent_start_date'];
        $rent_end_date = $_POST['rent_end_date'];

        // check if rent deposit is empty
        if (empty($rent_deposit)) {
            set_flash_mesage('Rent deposit is required!', 'warning');
            redirect('rental-edit.php?id=' . $rent_id);
        }

        if (empty($rent_start_date) || empty($rent_end_date)) {
            set_flash_mesage('All fields are required!', 'warning');
            redirect('rental-edit.php?id=' . $rent_id);
        }

        // check if rent start date is greater than rent end date
        if ($rent_start_date > $rent_end_date) {
            set_flash_mesage('Rent start date cannot be greater than rent end date!', 'warning');
            redirect('rental-edit.php?id=' . $rent_id);
        }

        $sql_rent_update = "UPDATE `rents` SET `rent_deposit` = '$rent_deposit', `rent_start_date` = '$rent_start_date', `rent_end_date` = '$rent_end_date' WHERE `rent_id` = '$rent_id'";
        $result_rent_update = mysqli_query($conn, $sql_rent_update);

        if ($result_rent_update) {
            set_flash_mesage('Rental updated successfully!', 'success');
            redirect('rental.php');
        } else {
            set_flash_mesage('Failed to update rental!', 'warning');
            redirect('rental.php');
        }
    }
} else {
    set_flash_mesage('Rental not found!', 'warning');
    redirect('admin/rental.php');
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
                                Rental Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <?= display_flash_message() ?>
                            <form action="" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="tenant_name">Tenant Name</label>
                                        <input type="text" name="tenant_name" id="tenant_name" class="form-control" value="<?= $rent['user_name'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="house_name">House Name</label>
                                        <input type="text" name="house_name" id="house_name" class="form-control" value="<?= $rent['house_name'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="room_name">Room Name</label>
                                        <input type="text" name="room_name" id="room_name" class="form-control" value="<?= $rent['room_name'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="rent_amount">Rent Amount</label>
                                        <input type="text" name="rent_amount" id="rent_amount" class="form-control" value="<?= $rent['room_monthly_rental'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="rent_deposit">Rent Deposit</label>
                                        <input type="text" name="rent_deposit" id="rent_deposit" class="form-control" value="<?= $rent['room_deposit'] ?>">
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="rent_month">Rent Monthly Rental</label>
                                        <input type="text" name="rent_month" id="rent_month" class="form-control" value="<?= $rent['room_monthly_rental'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="rent_start_date">Rent Start Date</label>
                                        <input type="date" name="rent_start_date" id="rent_start_date" class="form-control" value="<?= $rent['rent_start_date'] ?>">
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="rent_end_date">Rent End Date</label>
                                        <input type="date" name="rent_end_date" id="rent_end_date" class="form-control" value="<?= $rent['rent_end_date'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary">Update Rental</button>
                                    <a href="<?= base_url('admin/rental.php') ?>" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Payment Details
                            </h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPayment">
                                    <i class="fas fa-plus"></i> Add Payment
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="payments" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Payment Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment Created At</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($result_payments as $payment) : ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $payment['payment_amount'] ?></td>
                                                <td><?= $payment['payment_method'] ?></td>
                                                <td><?= date('d-m-Y h:i:s A', strtotime($payment['payment_created_at'])) ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deletePayment(<?= $payment['payment_id'] ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- Add Payment Modal -->
<div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="payment_amount">Payment Amount</label>
                    <input type="text" name="payment_amount" id="payment_amount" class="form-control">
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addPaymentBtn">Add Payment</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
<script>
    $('#rent_start_date').on('change', function() {
        var rent_start_date = $(this).val();
        var rent_end_date = $('#rent_end_date').val();
        var date = new Date(rent_start_date);
        var new_date = date.setFullYear(date.getFullYear() + 1);
        var new_date = new Date(new_date);
        var new_date = new_date.toISOString().slice(0, 10);
        $('#rent_end_date').val(new_date);
    });

    $('#addPaymentBtn').on('click', function() {
        var payment_amount = $('#payment_amount').val();
        var payment_method = $('#payment_method').val();

        if (payment_amount == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Payment amount is required!',
            });
            return false;
        }

        if (payment_method == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Payment method is required!',
            });
            return false;
        }

        $.ajax({
            url: '<?= base_url('admin/payment-add.php') ?>',
            type: 'POST',
            data: {
                payment_amount: payment_amount,
                payment_method: payment_method,
                payment_rent_id: <?= $rent_id ?>
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            }
        });
    });

    function deletePayment(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this payment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin/payment-delete.php') ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == "1") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    }
                });
            }
        })
    }
</script>