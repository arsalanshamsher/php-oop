<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.donors.index') ?>">Donors</a></li>
                <li class="breadcrumb-item active">Donor Details</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Donor: <?= htmlspecialchars($donor->donor_name) ?></h5>
                    <a href="<?= route('dashboard.donors.edit', ['id' => $donor->id]) ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit Donor
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold d-block">Donor Name</label>
                            <span><?= htmlspecialchars($donor->donor_name) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold d-block">Email</label>
                            <span><?= htmlspecialchars($donor->email) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold d-block">Phone Number</label>
                            <span><?= htmlspecialchars($donor->phone_number) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold d-block">Status</label>
                            <span class="badge bg-<?= $donor->status == 1 ? 'success' : 'danger' ?>">
                                <?= $donor->status == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold d-block">Address</label>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($donor->address)) ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?= route('dashboard.donors.index') ?>" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
