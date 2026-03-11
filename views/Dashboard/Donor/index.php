<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.donors.index') ?>">Donors</a></li>
                <li class="breadcrumb-item active">List</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2 mb-0">Donor Management</h1>
                    <a href="<?= route('dashboard.donors.create') ?>" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create New Donor
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <span class="text-muted">Total Donors: <?= count($donors) ?></span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th class="px-4" width="80">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th width="180">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($donors)): ?>
                                    <?php foreach ($donors as $donor): ?>
                                        <tr>
                                            <td class="px-4 fw-bold">#<?= $donor->id ?></td>
                                            <td><?= htmlspecialchars($donor->donor_name) ?></td>
                                            <td><?= htmlspecialchars($donor->email) ?></td>
                                            <td><?= htmlspecialchars($donor->phone_number) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $donor->status == 1 ? 'success' : 'danger' ?>">
                                                    <?= $donor->status == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= route('dashboard.donors.edit', ['id' => $donor->id]) ?>"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                                    </a>
                                                    <form action="<?= route('dashboard.donors.destroy', ['id' => $donor->id]) ?>"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this donor?');">
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
                                        <td colspan="6" class="text-center py-5">No donors found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
