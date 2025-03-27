<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/bootstrap-5.0.2-dist/css/bootstrap.min.css'); ?>">
    <title>Admin - Donor Create</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Admin - Donor Create</h1>
        <div class="row">
            <div class="col-lg-12">
                


                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="card-title">Add New Donor</h5>
                       


                        <form action="<?php echo route('admin-dashboard-store'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="donor_name" class="form-label">Donor Name</label>
                                <input type="text" value="<?= old('donor_name') ?>" class="form-control" id="donor_name" name="donor_name">
                                <?php if (has_error('donor_name')): ?>
                                    <div class="error-message">
                                        <span class="text-danger"><?= error_message('donor_name') ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="donor_profile" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="donor_profile" name="donor_profile">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Create Donor</button>
                            <a href="<?php echo route('admin-dashboard'); ?>" class="btn btn-secondary">Cancel</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo asset('/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>