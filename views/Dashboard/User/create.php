<?php $this->extend('Dashboard/layout/layout'); ?>

<?php $this->section('breadcrumb'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= route('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('dashboard.users.index') ?>">Users</a></li>
                <li class="breadcrumb-item active">Create New User</li>
            </ul>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
    <div class="container-fluid px-0">
        <!-- Header with Back Button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">Create New User</h1>
                        <p class="text-muted mb-0">Add a new user to the system</p>
                    </div>
                    <a href="<?= route('dashboard.users.index') ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Create User Form Card -->
        <div class="row">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>User Information
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="<?= route('dashboard.users.store') ?>" method="POST" id="createUserForm">
                            <!-- Full Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           placeholder="Enter full name"
                                           value="<?= old('name') ?>"
                                           required>
                                </div>
                                <div class="form-text text-muted">
                                    Enter user's first and last name
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email"
                                           placeholder="user@example.com"
                                           value="<?= old('email') ?>"
                                           required>
                                </div>
                                <div class="form-text text-muted">
                                    Will be used for login and notifications
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           placeholder="Enter password"
                                           required>
                                    <button class="btn btn-light" type="button" onclick="togglePassword()">
                                        <i class="fa-regular fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" id="strengthBar" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    <small class="text-muted" id="strengthText">Enter password</small>
                                </div>
                                <div class="form-text text-muted">
                                    Minimum 8 characters with letters and numbers
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Re-enter password"
                                           required>
                                </div>
                                <div class="validation-feedback" id="confirmFeedback"></div>
                            </div>

                            <!-- Role Selection -->
                            <div class="mb-4">
                                <label for="role_id" class="form-label fw-semibold">
                                    User Role <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-user-tag"></i>
                                </span>
                                    <select class="form-select" id="role_id" name="role_id" required>
                                        <option value="" selected disabled>Select a role</option>
                                        <?php if (isset($roles) && !empty($roles)): ?>
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?= $role->id ?>" <?= old('role_id') == $role->id ? 'selected' : '' ?>>
                                                    <?= ucfirst($role->name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1">Admin</option>
                                            <option value="2">Editor</option>
                                            <option value="3">Viewer</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-text text-muted">
                                    Select the role and permissions for this user
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    Account Status
                                </label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" checked>
                                        <label class="form-check-label" for="statusActive">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                            <i class="fa-regular fa-circle-check me-1"></i>Active
                                        </span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive">
                                        <label class="form-check-label" for="statusInactive">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            <i class="fa-regular fa-circle-pause me-1"></i>Inactive
                                        </span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="statusPending" value="pending">
                                        <label class="form-check-label" for="statusPending">
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                            <i class="fa-regular fa-clock me-1"></i>Pending
                                        </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information (Optional) -->
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold">
                                    Phone Number <span class="text-muted">(Optional)</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                    <input type="tel"
                                           class="form-control"
                                           id="phone"
                                           name="phone"
                                           placeholder="+1 234 567 8900"
                                           value="<?= old('phone') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="department" class="form-label fw-semibold">
                                    Department <span class="text-muted">(Optional)</span>
                                </label>
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-building"></i>
                                </span>
                                    <input type="text"
                                           class="form-control"
                                           id="department"
                                           name="department"
                                           placeholder="e.g., IT, HR, Marketing"
                                           value="<?= old('department') ?>">
                                </div>
                            </div>

                            <!-- Send Welcome Email -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_welcome_email" id="sendWelcomeEmail" checked>
                                    <label class="form-check-label" for="sendWelcomeEmail">
                                        <i class="fa-regular fa-envelope me-1 text-primary"></i>
                                        Send welcome email with login credentials
                                    </label>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <hr class="my-4">

                            <div class="d-flex gap-3 justify-content-end">
                                <a href="<?= route('dashboard.users.index') ?>" class="btn btn-light px-4">
                                    Cancel
                                </a>
                                <button type="reset" class="btn btn-outline-secondary px-4">
                                    <i class="fa-solid fa-rotate me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fa-solid fa-save me-2"></i>Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Info / Help Sidebar -->
            <div class="col-md-4 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-info">
                            <i class="fa-solid fa-circle-info me-2"></i>Quick Tips
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-key text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Password Requirements</h6>
                                <p class="text-muted small mb-0">Minimum 8 characters, at least one uppercase letter, one number, and one special character.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-shield text-success"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Role-Based Access</h6>
                                <p class="text-muted small mb-0">Users will have permissions based on their assigned role. Choose carefully.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-envelope text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Welcome Email</h6>
                                <p class="text-muted small mb-0">A welcome email with login credentials will be sent automatically if enabled.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fa-solid fa-clock text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Account Status</h6>
                                <p class="text-muted small mb-0">Inactive users cannot log in. Pending accounts require email verification.</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-info bg-light border-0 d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-lightbulb fa-xl me-3 text-warning"></i>
                            <div class="small">
                                <strong>Pro Tip:</strong> You can create multiple users at once using the <a href="#" class="alert-link">bulk import feature</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password Strength Checker
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!%*?]+/)) strength++;

            // Update progress bar
            const width = (strength / 5) * 100;
            strengthBar.style.width = width + '%';

            // Update colors and text
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'progress-bar';
                strengthText.textContent = 'Enter password';
                strengthText.className = 'text-muted';
            } else if (strength <= 2) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak password';
                strengthText.className = 'text-danger';
            } else if (strength <= 3) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Medium password';
                strengthText.className = 'text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong password';
                strengthText.className = 'text-success';
            }
        });

        // Confirm Password Validation
        document.getElementById('password_confirmation')?.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const feedback = document.getElementById('confirmFeedback');

            if (confirm.length === 0) {
                this.classList.remove('is-valid', 'is-invalid');
                feedback.textContent = '';
            } else if (password === confirm) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
                feedback.innerHTML = '<i class="fa-regular fa-circle-check me-1"></i> Passwords match';
                feedback.className = 'validation-feedback text-success mt-1';
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                feedback.innerHTML = '<i class="fa-regular fa-circle-exclamation me-1"></i> Passwords do not match';
                feedback.className = 'validation-feedback text-danger mt-1';
            }
        });

        // Email Format Validation
        document.getElementById('email')?.addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.length > 0 && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (email.length > 0) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Form Submission Validation
        document.getElementById('createUserForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match. Please check again.');
            }
        });
    </script>

    <style>
        .breadcrumb {
            /*background-color: transparent;*/
            padding: 0.75rem 0;
            margin-bottom: 0;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }
        .form-control, .input-group-text, .form-select {
            border-radius: 0.5rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
        .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.2);
        }
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .progress {
            border-radius: 1rem;
            background-color: #e9ecef;
        }
        .progress-bar {
            border-radius: 1rem;
            transition: width 0.3s ease;
        }
        .is-valid {
            border-color: #198754 !important;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .alert {
            border-radius: 0.5rem;
        }
    </style>
<?php $this->endSection(); ?>