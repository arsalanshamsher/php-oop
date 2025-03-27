<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/bootstrap-5.0.2-dist/css/bootstrap.min.css'); ?>">
    <title>Admin</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Admin</h1>
        <div class="row">
            <div class="col-12">
                <a href="<?php echo route('admin-dashboard-create') ?>" class="btn btn-primary">Add</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Donor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-12">
                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="card-title">Donor List</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Donor Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($donors as $kwy => $value) : ?>
                                    <tr>
                                        <th scope="row"><?php echo $value['donor_id']; ?></th>
                                        <td><?php echo $value['donor_name']; ?></td>
                                        <td><?php echo $value['email']; ?></td>
                                        <td><?php echo $value['phone_number']; ?></td>
                                        <td><?php echo $value['address']; ?></td>
                                        <td><img src="<?php echo $value['donor_profile']; ?>" alt="Profile Image" width="50"></td>
                                        <td><?php echo $value['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                                        <td><?php echo $value['created_at']; ?></td>
                                        <td><?php echo $value['updated_at']; ?></td>
                                        <td>
                                            <a href="<?php echo route('admin-dashboard-show',['id'=>$value['donor_id']]) ?>">Show</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
</body>

</html>