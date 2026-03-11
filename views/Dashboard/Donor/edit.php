<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.donors.index') ?>">Donors</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Edit Donor: <?= htmlspecialchars($donor->donor_name) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= route('dashboard.donors.update', ['id' => $donor->id]) ?>" method="POST">
                        <div class="mb-3">
                            <label for="donor_name" class="form-label">Donor Name</label>
                            <input type="text" class="form-control" id="donor_name" name="donor_name" value="<?= htmlspecialchars($donor->donor_name) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($donor->email) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($donor->phone_number) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($donor->address) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" <?= $donor->status == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $donor->status == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Donor</button>
                        <a href="<?= route('dashboard.donors.index') ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
