<?= include_file('admin/layout/header.php') ?>
<div class="container">
    <h1 class="text-center">Admin</h1>
    <div class="row">
        <div class="col-12">
            <a href="<?php echo route('admin-donor-list') ?>" class="btn btn-primary">Donors</a>
            
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
                   

                </div>
            </div>
        </div>
    </div>
</div>
<?= include_file('admin/layout/footer.php') ?>