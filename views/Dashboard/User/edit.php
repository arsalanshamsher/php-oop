<?php $this->extend('Dashboard/layout/layout'); ?>
<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.users.index') ?>">Users</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Edit User: <?= htmlspecialchars($user->name) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= route('dashboard.users.update', ['id' => $user->id]) ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="<?= route('dashboard.users.index') ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>
