<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('admin/') ?>" class="brand-link">
        <img src="<?= base_url('assets/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">RRMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= get_user_by_id($_SESSION['admin'])['user_username'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/index.php') ?>" class="nav-link <?= $title == 'Dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- users tree -->
                <li class="nav-item has-treeview <?= ($title == 'List Tenant' || $title == 'Staff' || $title == 'Admin') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($title == 'List Tenant' || $title == 'Staff' || $title == 'Admin') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $count['user'] ?></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- admin -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/user.php?role=admin') ?>" class="nav-link <?= $title == 'Admin' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin</p>
                                <span class="badge badge-info right"><?= $count['admin'] ?></span>
                            </a>
                        </li>
                        <!-- staff -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/user.php?role=staff') ?>" class="nav-link <?= $title == 'Staff' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Staff</p>
                                <span class="badge badge-info right"><?= $count['staff'] ?></span>
                            </a>
                        </li>
                        <!-- tenants -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/user.php?role=tenant') ?>" class="nav-link <?= $title == 'List Tenant' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tenant</p>
                                <span class="badge badge-info right"><?= $count['tenant'] ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- manage house -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/house.php') ?>" class="nav-link <?= ($title == 'House') || ($title == 'Add House') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            House
                            <span class="badge badge-info right"><?= $count['house'] ?></span>
                        </p>
                    </a>
                </li>

                <!-- manage room -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/room.php') ?>" class="nav-link <?= $title == 'Room' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Room
                            <span class="badge badge-info right"><?= $count['room'] ?></span>
                        </p>
                    </a>
                </li>

                <!-- manage tenant -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/tenant.php') ?>" class="nav-link <?= $title == 'Tenant' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Tenant
                        </p>
                    </a>
                </li>

                <!-- manage rental -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/rental.php') ?>" class="nav-link <?= $title == 'Rental' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            Rental
                        </p>
                    </a>
                </li>

                <!-- manage payment -->
                <li class="nav-item">
                    <a href="<?= base_url('admin/payment.php') ?>" class="nav-link <?= $title == 'Payment' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            Payment
                        </p>
                    </a>
                </li>

                <!-- logout -->
                <li class="nav-item">
                    <a href="#<?= base_url('logout.php') ?>" class="nav-link" onclick="logout()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>