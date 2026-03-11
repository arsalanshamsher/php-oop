<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.roles.index') ?>">Role</a></li>
                <li class="breadcrumb-item active">Create</li>
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
                    <h1 class="h2 mb-0">Create New Role</h1>
                    <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Create Role Form Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fa-solid fa-user-tag me-2"></i>Role Information
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?= route('dashboard.roles.store') ?>" method="POST">
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
                                                   placeholder="e.g., Admin, Editor, Viewer"
                                                   required>
                                        </div>
                                        <div class="form-text text-muted">
                                            Use a descriptive name like "Content Manager" or "Support Staff"
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fa-solid fa-shield-heart me-2 text-primary"></i>
                                        Role Permissions
                                    </h6>
                                    <p class="text-muted small mb-3">
                                        Select the permissions you want to assign to this role
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
                                                    <input class="form-check-input group-select-all" type="checkbox" id="<?= $groupKey ?>">
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
                                                               id="<?= $key ?>">
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
                                                  placeholder="Enter role description..."></textarea>
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
                                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" checked>
                                                <label class="form-check-label" for="statusActive">
                                                <span class="badge  text-success px-3 py-2">
                                                    <i class="fa-regular fa-circle-check me-1"></i>Active
                                                </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive">
                                                <label class="form-check-label" for="statusInactive">
                                                <span class="badge text-secondary px-3 py-2">
                                                    <i class="fa-regular fa-circle-pause me-1"></i>Inactive
                                                </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="<?= route('dashboard.roles.index') ?>" class="btn btn-light px-4">
                                            Cancel
                                        </a>
                                        <button type="reset" class="btn btn-outline-secondary px-4">
                                            <i class="fa-solid fa-rotate me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="fa-solid fa-save me-2"></i>Save Role
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select All Scripts -->
    <script src="<?= asset('js/select-all-groups-permission.js') ?>"></script>
<?php $this->endSection(); ?>