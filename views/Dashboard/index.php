<?php $this->extend('Dashboard/layout/layout'); ?>

<?php $this->section('breadcrumb'); ?>
    <div class="col-12">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Home</li>
        </ul>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Dashboard</h2>
            </div>
        </div>

        <div class="row">
            <!-- Total Users Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_users']) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Roles</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_roles']) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Sessions Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Donors</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_donors']) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-signal fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Tasks</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">7</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 300px; background: #f8f9fc;">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <span class="text-muted">Chart will be displayed here</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-circle text-primary me-2"></i>
                                    New user registered
                                </div>
                                <small class="text-muted">5 min ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-tag text-success me-2"></i>
                                    Role updated
                                </div>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file text-info me-2"></i>
                                    New report generated
                                </div>
                                <small class="text-muted">3 hours ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-cog text-warning me-2"></i>
                                    System updated
                                </div>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($recentUsers)): ?>
                                    <?php foreach ($recentUsers as $user): ?>
                                        <tr>
                                            <td><?= $user->id ?></td>
                                            <td><?= htmlspecialchars($user->name) ?></td>
                                            <td><?= htmlspecialchars($user->email) ?></td>
                                            <td><span class="badge bg-primary">User</span></td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td><?= date('Y-m-d', strtotime($user->created_at ?? 'now')) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No recent users found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing 1 to 3 of 3 results
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label for="perPage" class="mb-0 text-muted">Per Page:</label>
                            <select id="perPage" name="perPage" class="form-select form-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>