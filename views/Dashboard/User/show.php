<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.users.index') ?>">Users</a></li>
                <li class="breadcrumb-item active">User Details</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User: <?= htmlspecialchars($user->name) ?></h5>
                    <a href="<?= route('dashboard.users.edit', ['id' => $user->id]) ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit User
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Name:</label>
                        <span><?= htmlspecialchars($user->name) ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <span><?= htmlspecialchars($user->email) ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Joined:</label>
                        <span><?= date('d M, Y', strtotime($user->created_at ?? 'now')) ?></span>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?= route('dashboard.users.index') ?>" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
