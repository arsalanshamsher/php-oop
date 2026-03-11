<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.roles.index') ?>">Role</a></li>
                <li class="breadcrumb-item active">Edit Role</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>
<?php $this->section('content'); ?>
    <div class="container-fluid">


        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h2 mb-1">Edit Role</h1>
                        <p class="text-muted mb-0">Update role information and permissions</p>
                    </div>
                    <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Roles
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Role Form Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fa-solid fa-user-tag me-2"></i>Edit Role: <span class="text-dark"><?= $role->name ?? 'Administrator' ?></span>
                        </h5>
                        <span class="badge bg-opacity-10 text-primary px-3 py-2">
                        <i class="fa-regular fa-circle-check me-1"></i>Role ID: #<?= $role->id ?? '001' ?>
                    </span>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?= route('dashboard.roles.update', ['id' => $role->id ?? 1]) ?>" method="POST">
                            <input type="hidden" name="_method" value="PUT">

                            <!-- Role Name Field -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-semibold">
                                            Role Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fa-solid fa-tag"></i>
                                        </span>
                                            <input type="text"
                                                   class="form-control"
                                                   id="name"
                                                   name="name"
                                                   value="<?= $role->name ?? 'Administrator' ?>"
                                                   placeholder="e.g., Admin, Editor, Viewer"
                                                    <?= ($role->name ?? '') == 'admin' ? 'disabled' : '' ?>
                                                   required>
                                        </div>
                                        <?php if (($role->name ?? '') == 'admin'): ?>
                                            <div class="form-text text-warning">
                                                <i class="fa-solid fa-shield me-1"></i>The admin role name cannot be changed
                                            </div>
                                        <?php else: ?>
                                            <div class="form-text text-muted">
                                                Use a descriptive name like "Content Manager" or "Support Staff"
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Created Info -->
                                <div class="col-lg-6">
                                    <div class="bg-light p-3 rounded">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Created Date</small>
                                                <span class="fw-semibold"><?= date('d M, Y', strtotime($role->created_at ?? '2024-01-15')) ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Last Updated</small>
                                                <span class="fw-semibold"><?= date('d M, Y', strtotime($role->updated_at ?? 'now')) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-semibold mb-0">
                                            <i class="fa-solid fa-shield-hart me-2 text-primary"></i>
                                            Role Permissions
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-link" id="selectAllBtn">
                                                <i class="fa-regular fa-square-check me-1"></i>Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-link text-secondary" id="deselectAllBtn">
                                                <i class="fa-regular fa-square me-1"></i>Deselect All
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Modify the permissions assigned to this role
                                    </p>
                                </div>
                            </div>

                            <!-- Permission Groups -->
                            <div class="row g-4">
                                <?php foreach ($permissions as $groupKey => $group): ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="card h-100 border">
                                            <div class="card-header bg-light py-2">
                                                <div class="form-check">
                                                    <input class="form-check-input group-select-all" type="checkbox" id="<?= $groupKey ?>"
                                                            <?= $role->name === 'admin' ? 'checked disabled' : '' ?>>
                                                    <label class="form-check-label fw-semibold" for="<?= $groupKey ?>">
                                                        <i class="fa-solid <?= $group['icon'] ?> me-1"></i><?= $group['name'] ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <?php foreach ($group['permissions'] as $key => $label): ?>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input permission-checkbox"
                                                               type="checkbox"
                                                               name="permissions[]"
                                                               value="<?= $key ?>"
                                                               id="<?= $key ?>"
                                                                <?= in_array($key, $rolePermissions ?? []) ? 'checked' : '' ?>
                                                                <?= $role->name === 'admin' ? 'disabled' : '' ?>>
                                                        <label class="form-check-label small" for="<?= $key ?>"><?= $label ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Description Field -->
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="description" class="form-label fw-semibold">
                                            Description <span class="text-muted">(Optional)</span>
                                        </label>
                                        <textarea class="form-control"
                                                  id="description"
                                                  name="description"
                                                  rows="3"
                                                  placeholder="Enter role description..."
                                              <?= ($role->name ?? '') == 'admin' ? 'disabled' : '' ?>><?= $role->description ?? 'Administrator role with full access to all system features' ?></textarea>
                                        <?php if (($role->name ?? '') == 'admin'): ?>
                                            <div class="form-text text-warning">
                                                <i class="fa-solid fa-shield me-1"></i>Admin role description is protected
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">Status</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="active"
                                                        <?= (($role->status ?? 'active') == 'active') ? 'checked' : '' ?>
                                                        <?= ($role->name ?? '') == 'admin' ? 'disabled' : '' ?>>
                                                <label class="form-check-label" for="statusActive">
                                                <span class="badge  bg-opacity-10 text-success px-3 py-2">
                                                    <i class="fa-regular fa-circle-check me-1"></i>Active
                                                </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive"
                                                        <?= (($role->status ?? '') == 'inactive') ? 'checked' : '' ?>
                                                        <?= ($role->name ?? '') == 'admin' ? 'disabled' : '' ?>>
                                                <label class="form-check-label" for="statusInactive">
                                                <span class="badge  bg-opacity-10 text-secondary px-3 py-2">
                                                    <i class="fa-regular fa-circle-pause me-1"></i>Inactive
                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php if (($role->name ?? '') == 'admin'): ?>
                                            <div class="form-text text-warning">
                                                <i class="fa-solid fa-shield me-1"></i>Admin role must remain active
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-2 justify-content-between">
                                        <div>
                                            <?php if (($role->name ?? '') != 'admin'): ?>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="fa-solid fa-trash-can me-2"></i>Delete Role
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-light px-4">
                                                Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary px-5">
                                                <i class="fa-solid fa-save me-2"></i>Update Role
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
<?php if (($role->name ?? '') != 'admin'): ?>
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
                    <p>Are you sure you want to delete the role <strong>"<?= $role->name ?? 'Administrator' ?>"</strong>?</p>
                    <p class="text-muted small mb-0">This action cannot be undone. All users with this role may lose access.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?= route('dashboard.roles.destroy', ['id' => $role->id ?? 1]) ?>" method="POST" class="d-inline">
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

    <!-- JavaScript for Permission Management -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Group Select All functionality
            const groupHeaders = document.querySelectorAll('.card-header .form-check-input');

            groupHeaders.forEach(headerCheckbox => {
                headerCheckbox.addEventListener('change', function() {
                    const cardBody = this.closest('.card').querySelector('.card-body');
                    const checkboxes = cardBody.querySelectorAll('.permission-checkbox');

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            });

            // Update header checkbox state based on individual checkboxes
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const cardBody = this.closest('.card-body');
                    const headerCheckbox = cardBody.closest('.card').querySelector('.card-header .form-check-input');
                    const allCheckboxes = cardBody.querySelectorAll('.permission-checkbox');
                    const checkedCount = cardBody.querySelectorAll('.permission-checkbox:checked').length;

                    if (checkedCount === allCheckboxes.length) {
                        headerCheckbox.checked = true;
                        headerCheckbox.indeterminate = false;
                    } else if (checkedCount === 0) {
                        headerCheckbox.checked = false;
                        headerCheckbox.indeterminate = false;
                    } else {
                        headerCheckbox.checked = false;
                        headerCheckbox.indeterminate = true;
                    }
                });
            });

            // Select All button
            document.getElementById('selectAllBtn')?.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                groupHeaders.forEach(header => {
                    header.checked = true;
                    header.indeterminate = false;
                });
            });

            // Deselect All button
            document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                groupHeaders.forEach(header => {
                    header.checked = false;
                    header.indeterminate = false;
                });
            });

            // Initialize indeterminate states
            permissionCheckboxes.forEach(checkbox => {
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 0;
            margin-bottom: 0;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
        }
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.05);
        }
        .form-control, .input-group-text {
            border-radius: 0.5rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .card-header .form-check {
            margin-bottom: 0;
        }
        .card.h-100 {
            transition: all 0.2s ease;
        }
        .card.h-100:hover {
            border-color: #0d6efd !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0,123,255,0.075);
        }
        .form-control:disabled,
        .form-check-input:disabled,
        textarea:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
    </style>
<?php $this->endSection(); ?>