<?php $this->extend('Dashboard/layout/layout'); ?>

<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.roles.index') ?>">Roles</a></li>
                <li class="breadcrumb-item active">Role Details: <?= htmlspecialchars($role->name) ?></li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid px-0">
        <!-- Header with Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">Role Details</h1>
                        <p class="text-muted mb-0">View complete information about this role</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= route('dashboard.roles.edit', ['id' => $role->id]) ?>" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Edit Role
                        </a>
                        <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Role Details -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fa-solid fa-circle-info me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Role Name -->
                        <div class="mb-4">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-2">
                                <i class="fa-solid fa-tag me-1"></i>Role Name
                            </label>
                            <div class="bg-light p-3 rounded">
                                <span class="h5 mb-0"><?= htmlspecialchars($role->name) ?></span>
                                <?php if ($role->name === 'admin'): ?>
                                    <span class="badge bg-danger ms-2">Super Admin</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-2">
                                <i class="fa-solid fa-align-left me-1"></i>Description
                            </label>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0"><?= htmlspecialchars($role->description ?? 'No description provided for this role.') ?></p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-2">
                                <i class="fa-solid fa-circle me-1"></i>Status
                            </label>
                            <div class="bg-light p-3 rounded">
                                <?php
                                $statusClass = match($role->status ?? 'active') {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    default => 'warning'
                                };
                                ?>
                                <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> px-3 py-2">
                                <i class="fa-regular fa-circle-check me-1"></i>
                                <?= ucfirst($role->status ?? 'active') ?>
                            </span>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div>
                            <label class="fw-bold text-muted small text-uppercase d-block mb-2">
                                <i class="fa-solid fa-clock me-1"></i>Meta Information
                            </label>
                            <div class="bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Created At</small>
                                        <span class="fw-semibold"><?= date('d M, Y h:i A', strtotime($role->created_at ?? 'now')) ?></span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <span class="fw-semibold"><?= date('d M, Y h:i A', strtotime($role->updated_at ?? 'now')) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Permissions -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-info">
                            <i class="fa-solid fa-key me-2"></i>Role Permissions
                        </h5>
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                        <?= count($rolePermissions ?? []) ?> Permissions
                    </span>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($rolePermissions)): ?>
                            <!-- Permission Groups -->
                            <div class="row g-3">
                                <?php
                                $permissionGroups = [
                                        'users' => ['icon' => 'fa-users', 'color' => 'primary'],
                                        'roles' => ['icon' => 'fa-user-tag', 'color' => 'success'],
                                        'content' => ['icon' => 'fa-file-lines', 'color' => 'info'],
                                        'settings' => ['icon' => 'fa-gear', 'color' => 'warning']
                                ];

                                $groupedPermissions = [];
                                foreach ($rolePermissions as $permission) {
                                    $group = explode('_', $permission)[0] ?? 'other';
                                    $groupedPermissions[$group][] = $permission;
                                }
                                ?>

                                <?php foreach ($groupedPermissions as $group => $permissions): ?>
                                    <div class="col-md-6">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light py-2">
                                                <h6 class="mb-0 text-capitalize">
                                                    <i class="fa-solid <?= $permissionGroups[$group]['icon'] ?? 'fa-lock' ?> me-2 text-<?= $permissionGroups[$group]['color'] ?? 'secondary' ?>"></i>
                                                    <?= ucfirst($group) ?> Management
                                                </h6>
                                            </div>
                                            <div class="card-body p-3">
                                                <ul class="list-unstyled mb-0">
                                                    <?php foreach ($permissions as $permission): ?>
                                                        <li class="mb-2">
                                                        <span class="badge bg-light text-dark w-100 text-start p-2">
                                                            <i class="fa-regular fa-circle-check text-success me-2"></i>
                                                            <?= ucwords(str_replace('_', ' ', $permission)) ?>
                                                        </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- All Permissions List -->
                            <div class="mt-4">
                                <details>
                                    <summary class="text-primary fw-semibold">
                                        <i class="fa-solid fa-chevron-down me-1"></i> View All Permissions
                                    </summary>
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach ($rolePermissions as $permission): ?>
                                                <span class="badge bg-white text-dark border px-3 py-2">
                                                <i class="fa-solid fa-key me-1 text-muted"></i>
                                                <?= htmlspecialchars($permission) ?>
                                            </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </details>
                            </div>
                        <?php else: ?>
                            <!-- No Permissions -->
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fa-solid fa-key fa-4x text-muted opacity-50"></i>
                                </div>
                                <h6 class="text-muted">No Permissions Assigned</h6>
                                <p class="text-muted small mb-3">This role doesn't have any permissions yet.</p>
                                <a href="<?= route('dashboard.roles.edit', ['id' => $role->id]) ?>" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-plus me-1"></i>Add Permissions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Users with this Role -->
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-success">
                            <i class="fa-solid fa-users me-2"></i>Users with this Role
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($users) && count($users) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($users, 0, 5) as $user): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="fa-solid fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold"><?= htmlspecialchars($user->name) ?></span>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($user->email) ?></small>
                                            </div>
                                        </div>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                        Active
                                    </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($users) > 5): ?>
                                <div class="p-3 text-center">
                                    <a href="<?= route('dashboard.users.index', ['role' => $role->id]) ?>" class="btn btn-sm btn-link">
                                        View all <?= count($users) ?> users <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No users assigned to this role</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Role ID: #<?= $role->id ?></small>
                                <small class="text-muted">Created by Admin on <?= date('d M, Y', strtotime($role->created_at ?? 'now')) ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?= route('dashboard.roles.edit', ['id' => $role->id]) ?>" class="btn btn-warning">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Role
                                </a>
                                <?php if ($role->name !== 'admin'): ?>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="fa-solid fa-trash-can me-2"></i>Delete Role
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
<?php if ($role->name !== 'admin'): ?>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>Delete Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the role <strong>"<?= htmlspecialchars($role->name) ?>"</strong>?</p>
                    <?php if (!empty($users) && count($users) > 0): ?>
                        <div class="alert alert-warning">
                            <i class="fa-solid fa-exclamation-circle me-2"></i>
                            This role has <?= count($users) ?> assigned user(s). They may lose access.
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?= route('dashboard.roles.destroy', ['id' => $role->id]) ?>" method="POST" class="d-inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash-can me-2"></i>Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <!-- Custom Styles -->
    <!-- <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 0;
            margin-bottom: 0;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }
        .badge {
            font-weight: 500;
            padding: 0.5em 0.85em;
        }
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        details summary {
            cursor: pointer;
            list-style: none;
            user-select: none;
        }
        details summary::-webkit-details-marker {
            display: none;
        }
        details[open] summary i {
            transform: rotate(0deg);
        }
        summary i {
            transition: transform 0.2s ease;
        }
    </style> -->
<?php $this->endSection(); ?>