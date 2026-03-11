<?php $this->extend('Dashboard/layout/layout'); ?>

<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>"><i class="fa-solid fa-house me-1"></i>Dashboard</a>
                </li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.roles.index') ?>"><i
                                class="fa-solid fa-user-tag me-1"></i>Roles</a></li>
                <li class="breadcrumb-item active">All Roles</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid px-0">
        <!-- Header with Title and Create Button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">Role Management</h1>
                        <p class="text-muted mb-0">Manage and organize user roles and permissions</p>
                    </div>
                    <a href="<?= route('dashboard.roles.create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus-circle me-2"></i>Create New Role
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <!-- Total Roles Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Roles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(count($roles)) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Roles -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Roles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(count($roles)) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-check fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users Assigned -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Users Assigned
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(rand(50, 200)) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-user-check fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Protected Roles -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Protected Roles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(rand(1, 5)) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-lock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
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

        <!-- Roles Table Card -->
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
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Users Count</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th width="240">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($roles) && count($roles) > 0): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <tr>
                                            <td class="px-4">
                                                <div class="form-check">
                                                    <input class="form-check-input row-checkbox" type="checkbox"
                                                           value="<?= $role->id ?>">
                                                </div>
                                            </td>
                                            <td class="fw-bold">#<?= str_pad($role->id, 3, '0', STR_PAD_LEFT) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-<?= $role->name === 'admin' ? 'danger' : 'primary' ?> bg-opacity-10 rounded p-2 me-3">
                                                        <i class="fa-solid fa-user-tag text-white"></i>
                                                    </div>
                                                    <div>
                                                        <span class="fw-semibold"><?= ucfirst($role->name) ?></span>
                                                        <?php if ($role->name === 'admin'): ?>
                                                            <span class="badge bg-danger ms-2">
                                                                <i class="fa-solid fa-shield me-1"></i>Super Admin
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($role->name === 'editor'): ?>
                                                            <span class="badge bg-info ms-2">Content Editor</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted small">
                                                    <?= htmlspecialchars(substr($role->description ?? 'No description', 0, 50)) ?>
                                                    <?= strlen($role->description ?? '') > 50 ? '...' : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark px-3 py-2">
                                                    <i class="fa-solid fa-users me-1 text-primary"></i>
                                                    <?= $role->users_count ?? rand(0, 15) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-semibold"><?= date('d M, Y', strtotime($role->created_at ?? 'now')) ?></span>
                                                    <br>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($role->created_at ?? 'now')) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $role->status ?? 'active';
                                                $statusClass = $status === 'active' ? 'success' : 'secondary';
                                                $statusIcon = $status === 'active' ? 'fa-circle-check' : 'fa-circle-pause';
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> px-3 py-2">
                                                    <i class="fa-regular <?= $statusIcon ?> text-white me-1"></i>
                                                    <span class="text-white">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= route('dashboard.roles.show', ['id' => $role->id]) ?>"
                                                       class="btn btn-sm btn-info"
                                                       data-bs-toggle="tooltip"
                                                       title="View Details">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <a href="<?= route('dashboard.roles.edit', ['id' => $role->id]) ?>"
                                                       class="btn btn-sm btn-warning"
                                                       data-bs-toggle="tooltip"
                                                       title="Edit Role">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>

                                                    <?php if ($role->name !== 'admin'): ?>
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger delete-role-btn"
                                                                data-id="<?= $role->id ?>"
                                                                data-name="<?= htmlspecialchars($role->name) ?>"
                                                                data-url="<?= route('dashboard.roles.destroy', ['id' => $role->id]) ?>"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Role">
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
                                                                        Role
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
                                                <h4 class="text-muted mb-2">No Roles Found</h4>
                                                <p class="text-muted mb-4">Get started by creating your first role</p>
                                                <a href="<?= route('dashboard.roles.create') ?>"
                                                   class="btn btn-primary btn-lg">
                                                    <i class="fa-solid fa-plus-circle me-2"></i>Create First Role
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
                                    Showing <span class="fw-semibold"><?= min(count($roles), 10) ?></span> of
                                    <span class="fw-semibold"><?= count($roles) ?></span> entries
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

        <!-- Bulk Actions Bar (Visible when items selected) -->
        <div class="bulk-actions-bar card shadow position-fixed bottom-0 start-50 translate-middle-x mb-3 d-none"
             id="bulkActionsBar">
            <div class="card-body py-2 px-4">
                <div class="d-flex align-items-center gap-4">
                    <span class="fw-semibold" id="selectedCount">0 items selected</span>
                    <div class="vr"></div>
                    <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                        <i class="fa-solid fa-trash-can me-1"></i>Delete Selected
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="bulkStatus('active')">
                        <i class="fa-regular fa-circle-check me-1"></i>Set Active
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="bulkStatus('inactive')">
                        <i class="fa-regular fa-circle-pause me-1"></i>Set Inactive
                    </button>
                    <button class="btn btn-sm btn-outline-secondary ms-auto" onclick="deselectAll()">
                        <i class="fa-solid fa-times me-1"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                    <p>Are you sure you want to delete the role <strong id="deleteRoleName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-exclamation-circle me-2"></i>
                        This action cannot be undone. Users with this role may lose access.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="" method="POST" id="deleteForm" class="d-inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash-can me-2"></i>Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Delete role buttons
            document.querySelectorAll('.delete-role-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    document.getElementById('deleteRoleName').textContent = name;
                    document.getElementById('deleteForm').action = this.dataset.url;
                    new bootstrap.Modal(document.getElementById('deleteModal')).show();
                });
            });
           

            // Select all checkbox
            document.getElementById('selectAll')?.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActionsBar();
            });

            // Individual checkboxes
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.addEventListener('change', updateBulkActionsBar);
            });

            // Apply filters button
            document.getElementById('applyFilters')?.addEventListener('click', function () {
                const search = document.getElementById('searchInput').value;
                const status = document.getElementById('statusFilter').value;
                const sort = document.getElementById('sortBy').value;

                let url = new URL(window.location.href);
                if (search) url.searchParams.set('search', search);
                if (status) url.searchParams.set('status', status);
                if (sort) url.searchParams.set('sort', sort);

                window.location.href = url.toString();
            });

            // Search on enter key
            document.getElementById('searchInput')?.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    document.getElementById('applyFilters').click();
                }
            });
        });

        function updateBulkActionsBar() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkboxes.length;
            const bar = document.getElementById('bulkActionsBar');

            if (count > 0) {
                document.getElementById('selectedCount').textContent = count + ' item' + (count > 1 ? 's' : '') + ' selected';
                bar.classList.remove('d-none');
            } else {
                bar.classList.add('d-none');
            }
        }

        function deselectAll() {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            document.getElementById('bulkActionsBar').classList.add('d-none');
        }

        function changePerPage(value) {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        function exportTable(format) {
            // Implement export functionality
            alert('Exporting as ' + format + '...');
        }

        function bulkDelete() {
            const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            if (selected.includes('1')) {
                alert('Cannot delete admin role');
                return;
            }
            // Implement bulk delete
            alert('Deleting roles: ' + selected.join(', '));
        }

        function bulkStatus(status) {
            const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            // Implement bulk status change
            alert('Setting roles to ' + status + ': ' + selected.join(', '));
        }
    </script>

    <style>
        

        

       

      

        

        .bulk-actions-bar {
            z-index: 1000;
            border-radius: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15) !important;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%) translateX(-50%);
                opacity: 0;
            }
            to {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
        }
    </style>
<?php $this->endSection(); ?>