<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('bootstrap-5.3.2-dist/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/global.css') ?>">
    <title>Admin Dashboard</title>
    <style>
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }

        .sidebar.d-none {
            margin-left: -250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                height: 100vh;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <div class="sidebar-header p-3 d-flex justify-content-between align-items-center">
            <h4>Admin Panel</h4>
            <button class="btn btn-link text-white d-md-none" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= route('dashboard') ?>" class="nav-link text-white">
                    <i class="fas fa-dashboard me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= route('dashboard.roles.index') ?>" class="nav-link text-white">
                    <i class="fas fa-user-tag me-2"></i> Roles
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= route('dashboard.users.index') ?>" class="nav-link text-white">
                    <i class="fas fa-users me-2"></i> Users
                </a>
            </li>
           
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content flex-grow-1 bg-light">
        <!-- Header -->
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4">
            <div class="container-fluid">
                <button class="btn btn-link" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                        </button>
                    </div>

                    <div class="dropdown user-menu">
                        <div class="dropdown-toggle text-dark" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?= route('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Breadcrumb Area -->
        <div class="px-4 pt-3">
            <!-- Alert Messages -->
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <?= session()->get('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php session()->remove('success'); ?>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <?= session()->get('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php session()->remove('error'); ?>
            <?php endif; ?>
            <?php $this->yield('breadcrumb'); ?>
        </div>

        <!-- Content Area -->
        <div class="p-4">
            <?php $this->yield('content'); ?>
        </div>

        <!-- Footer -->
        <footer class="bg-white text-center py-3 mt-auto border-top">
            <div class="container-fluid">
                <span class="text-muted">&copy; <?= date('Y') ?> Admin Panel. All rights reserved.</span>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/global.js') ?>"></script>
<script>

</script>
</body>
</html>