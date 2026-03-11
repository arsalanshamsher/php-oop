<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.users.index') ?>">Users</a></li>
                <li class="breadcrumb-item active">List</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid">
        <!-- Header with Create Button -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2 mb-0">User Management</h1>
                    <a href="<?= route('dashboard.users.create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create New User
                    </a>
                </div>
            </div>
        </div>
         <!-- Filters and Search Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-5">
                                <div class="search-box position-relative">
                                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input class="form-control ps-5"
                                           name="search"
                                           id="searchInput"
                                           placeholder="Search roles by name or description..."
                                           type="text"
                                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                    <?php if (!empty($_GET['search'])): ?>
                                        <a href="<?= route('dashboard.roles.index') ?>"
                                           class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                            <i class="fa-solid fa-times-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <select class="form-select w-auto" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                                            Active
                                        </option>
                                        <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                            Inactive
                                        </option>
                                    </select>

                                    <select class="form-select w-auto" id="sortBy">
                                        <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>
                                            Newest First
                                        </option>
                                        <option value="oldest" <?= ($_GET['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>
                                            Oldest First
                                        </option>
                                        <option value="name_asc" <?= ($_GET['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>
                                            Name A-Z
                                        </option>
                                        <option value="name_desc" <?= ($_GET['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>
                                            Name Z-A
                                        </option>
                                    </select>

                                    <button class="btn btn-outline-primary" type="button" id="applyFilters">
                                        <i class="fa-solid fa-filter me-1"></i>Apply
                                    </button>
                                    <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-rotate-right me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <!-- <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <span class="text-muted">Total Users: <?= count($users) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="80">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th width="180">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="px-4 fw-bold">#<?= $user->id ?></td>
                                            <td><?= htmlspecialchars($user->name) ?></td>
                                            <td><?= htmlspecialchars($user->email) ?></td>
                                            <td><?= date('d M, Y', strtotime($user->created_at ?? 'now')) ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= route('dashboard.users.edit', ['id' => $user->id]) ?>"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                                    </a>
                                                    <form action="<?= route('dashboard.users.destroy', ['id' => $user->id]) ?>"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        <?= method_field('DELETE') ?>
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa-solid fa-trash-can me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Users Table Card -->

         <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <!-- Card Header - Export Options -->
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-list me-2 text-primary"></i>
                            Roles List
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="exportTable('csv')">
                                <i class="fa-solid fa-file-csv me-1"></i>CSV
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="exportTable('pdf')">
                                <i class="fa-solid fa-file-pdf me-1"></i>PDF
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="exportTable('excel')">
                                <i class="fa-solid fa-file-excel me-1"></i>Excel
                            </button>
                        </div>
                    </div>

                    <!-- Card Body - Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="rolesTable">
                                <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="60">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th width="80">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th width="240">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($users) && count($users) > 0): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="px-4">
                                                <div class="form-check">
                                                    <input class="form-check-input row-checkbox" type="checkbox"
                                                           value="<?= $user->id ?>">
                                                </div>
                                            </td>
                                            <td class="fw-bold">#<?= str_pad($user->id, 3, '0', STR_PAD_LEFT) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-<?= $user->name === 'admin' ? 'danger' : 'primary' ?> bg-opacity-10 rounded p-2 me-3">
                                                        <i class="fa-solid fa-user-tag text-<?= $user->name === 'admin' ? 'danger' : 'primary' ?>"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-semibold"><?= ucfirst($user->name) ?></span>
                                                        <?php if ($user->name === 'admin'): ?>
                                                            <span class="badge bg-danger ms-2">
                                                                <i class="fa-solid fa-shield  me-1"></i>Super Admin
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($user->name === 'editor'): ?>
                                                            <span class="badge bg-info ms-2">Content Editor</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <?= htmlspecialchars(substr($user->email ?? 'No description', 0, 50)) ?>
                                                    <?= strlen($user->email ?? '') > 50 ? '...' : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark px-3 py-2">
                                                    <i class="fa-solid fa-users me-1 text-primary"></i>
                                                    <?= $user->role->name ?? 'No role' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-semibold"><?= date('d M, Y', strtotime($user->created_at ?? 'now')) ?></span>
                                                    <br>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($user->created_at ?? 'now')) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $user->status ?? 'active';
                                                $statusClass = $status === 'active' ? 'success' : 'secondary';
                                                $statusIcon = $status === 'active' ? 'fa-circle-check' : 'fa-circle-pause';
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> px-3 py-2">
                                                    <i class="fa-regular <?= $statusIcon ?> text-<?= $statusClass ?> me-1"></i>
                                                    <span class="text-<?= $statusClass ?>">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= route('dashboard.users.show', ['id' => $user->id]) ?>"
                                                       class="btn btn-sm btn-info"
                                                       data-bs-toggle="tooltip"
                                                       title="View Details">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <a href="<?= route('dashboard.users.edit', ['id' => $user->id]) ?>"
                                                       class="btn btn-sm btn-warning"
                                                       data-bs-toggle="tooltip"
                                                       title="Edit User">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>

                                                    <?php if ($user->name !== 'admin'): ?>
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger delete-user-btn"
                                                                data-id="<?= $user->id ?>"
                                                                data-name="<?= htmlspecialchars($user->name) ?>"
                                                                data-url="<?= route('dashboard.users.destroy', ['id' => $user->id]) ?>"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete User">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                    type="button"
                                                                    data-bs-toggle="dropdown">
                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item" href="#">
                                                                        <i class="fa-regular fa-clone me-2"></i>Duplicate
                                                                        User
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#">
                                                                        <i class="fa-solid fa-shield me-2"></i>Manage
                                                                        Permissions
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#">
                                                                        <i class="fa-solid fa-users me-2"></i>View Users
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-warning" href="#">
                                                                        <i class="fa-solid fa-toggle-off me-2"></i>Change
                                                                        Status
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted px-3 py-2">
                                                            <i class="fa-solid fa-lock me-1"></i>Protected
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-5">
                                                <i class="fa-solid fa-user-tag fa-4x text-muted mb-3 opacity-50"></i>
                                                <h4 class="text-muted mb-2">No Users Found</h4>
                                                <p class="text-muted mb-4">Get started by creating your first user</p>
                                                <a href="<?= route('dashboard.users.create') ?>"
                                                   class="btn btn-primary btn-lg">
                                                    <i class="fa-solid fa-plus-circle me-2"></i>Create First User
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card Footer - Pagination and Info -->
                    <div class="card-footer bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <div class="text-muted small">
                                    <i class="fa-regular fa-file-lines me-1"></i>
                                    Showing <span class="fw-semibold"><?= min(count($users), 10) ?></span> of
                                    <span class="fw-semibold"><?= count($users) ?></span> entries
                                </div>
                            </div>

                            <div class="col-md-5 mb-2 mb-md-0">
                                <div class="d-flex align-items-center justify-content-md-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <label for="perPage" class="text-muted small me-2 mb-0">Show:</label>
                                        <select id="perPage" name="perPage" class="form-select form-select-sm w-auto"
                                                onchange="changePerPage(this.value)">
                                            <option value="10" <?= (isset($perPage) && $perPage == 10) ? 'selected' : '' ?>>
                                                10
                                            </option>
                                            <option value="25" <?= (isset($perPage) && $perPage == 25) ? 'selected' : '' ?>>
                                                25
                                            </option>
                                            <option value="50" <?= (isset($perPage) && $perPage == 50) ? 'selected' : '' ?>>
                                                50
                                            </option>
                                            <option value="100" <?= (isset($perPage) && $perPage == 100) ? 'selected' : '' ?>>
                                                100
                                            </option>
                                            <option value="all" <?= (isset($perPage) && $perPage == 'all') ? 'selected' : '' ?>>
                                                All
                                            </option>
                                        </select>
                                    </div>

                                    <span class="text-muted small">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    Last updated: <?= date('d M Y h:i A') ?>
                                </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <?php if (isset($totalPages) && $totalPages > 1): ?>
                                    <nav aria-label="Page navigation" class="d-flex justify-content-md-end">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?= (!isset($currentPage) || $currentPage == 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="#" aria-label="First">
                                                    <i class="fa-solid fa-angles-left"></i>
                                                </a>
                                            </li>
                                            <li class="page-item <?= (!isset($currentPage) || $currentPage == 1) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="#" aria-label="Previous">
                                                    <i class="fa-solid fa-chevron-left"></i>
                                                </a>
                                            </li>

                                            <?php
                                            $start = max(1, min($currentPage - 2, $totalPages - 4));
                                            $end = min($totalPages, max(5, $currentPage + 2));
                                            for ($i = $start; $i <= $end; $i++):
                                                ?>
                                                <li class="page-item <?= (isset($currentPage) && $currentPage == $i) ? 'active' : '' ?>">
                                                    <a class="page-link" href="#"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <li class="page-item <?= (isset($currentPage) && $currentPage == $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="#" aria-label="Next">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </a>
                                            </li>
                                            <li class="page-item <?= (isset($currentPage) && $currentPage == $totalPages) ? 'disabled' : '' ?>">
                                                <a class="page-link" href="#" aria-label="Last">
                                                    <i class="fa-solid fa-angles-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
<?php $this->endSection(); ?>
