<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Admin Panel</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            max-width: 550px;
            width: 100%;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            padding: 40px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo i {
            font-size: 50px;
            color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-logo h2 {
            font-weight: 700;
            margin-top: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding-left: 45px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0a0a0;
            z-index: 10;
            font-size: 18px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0a0a0;
            cursor: pointer;
            z-index: 10;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            height: 50px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }

        .terms {
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .terms a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 15px;
        }

        .login-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 3px;
            background: #e0e0e0;
            position: relative;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s;
            border-radius: 3px;
        }

        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .weak { background: #ff4d4f; width: 33.33%; }
        .medium { background: #faad14; width: 66.66%; }
        .strong { background: #52c41a; width: 100%; }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fff2f0;
            color: #ff4d4f;
            border-left: 4px solid #ff4d4f;
        }

        .alert-success {
            background: #f6ffed;
            color: #52c41a;
            border-left: 4px solid #52c41a;
        }

        .validation-feedback {
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .is-valid {
            border-color: #52c41a !important;
        }

        .is-invalid {
            border-color: #ff4d4f !important;
        }

        .valid-feedback {
            color: #52c41a;
        }

        .invalid-feedback {
            color: #ff4d4f;
        }
    </style>
</head>
<body>
<div class="register-container">
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= session()->get('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php session()->remove('error'); ?>
    <?php endif; ?>

    <div class="register-card">
        <div class="brand-logo">
            <i class="fas fa-shield-alt"></i>
            <h2>Create Account</h2>
            <p class="text-muted">Sign up to get started with Admin Panel</p>
        </div>

        <form action="<?= route('register.store') ?>" method="POST" id="registerForm">
            <!-- Full Name -->
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text"
                       class="form-control"
                       name="name"
                       id="name"
                       placeholder="Full Name"
                       value="<?= old('name') ?>"
                       required>
            </div>

            <!-- Email -->
            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email"
                       class="form-control"
                       name="email"
                       id="email"
                       placeholder="Email Address"
                       value="<?= old('email') ?>"
                       required>
                <div class="validation-feedback" id="emailFeedback"></div>
            </div>

            <!-- Password -->
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password"
                       class="form-control"
                       name="password"
                       id="password"
                       placeholder="Password"
                       required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
            </div>

            <!-- Password Strength -->
            <div class="password-strength">
                <div class="password-strength-bar" id="strengthBar"></div>
            </div>
            <span class="strength-text" id="strengthText">Enter password</span>

            <!-- Confirm Password -->
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password"
                       class="form-control"
                       name="password_confirmation"
                       id="confirmPassword"
                       placeholder="Confirm Password"
                       required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirmPassword')"></i>
            </div>
            <div class="validation-feedback" id="confirmFeedback"></div>

            <!-- Terms & Conditions -->
            <div class="terms">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-register w-100" id="registerBtn">
                <i class="fas fa-user-plus me-2"></i>Sign Up
            </button>

            <!-- Login Link -->
            <div class="login-link">
                Already have an account? <a href="<?= route('login') ?>">Sign in</a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle Password Visibility
    function togglePassword(inputId) {
        const password = document.getElementById(inputId);
        const toggleIcon = password.nextElementSibling;

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

        // Check password strength
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;

        // Update UI
        strengthBar.className = 'password-strength-bar';

        switch(strength) {
            case 0:
            case 1:
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#ff4d4f';
                break;
            case 2:
            case 3:
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Medium password';
                strengthText.style.color = '#faad14';
                break;
            case 4:
            case 5:
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#52c41a';
                break;
        }

        if (password.length === 0) {
            strengthBar.style.width = '0';
            strengthText.textContent = 'Enter password';
            strengthText.style.color = '#666';
        }
    });

    // Email Validation
    document.getElementById('email')?.addEventListener('input', function() {
        const email = this.value;
        const feedback = document.getElementById('emailFeedback');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.length === 0) {
            this.classList.remove('is-valid', 'is-invalid');
            feedback.textContent = '';
        } else if (emailRegex.test(email)) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
            feedback.textContent = 'Valid email address';
            feedback.className = 'validation-feedback valid-feedback';
        } else {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            feedback.textContent = 'Please enter a valid email address';
            feedback.className = 'validation-feedback invalid-feedback';
        }
    });

    // Confirm Password Validation
    document.getElementById('confirmPassword')?.addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirm = this.value;
        const feedback = document.getElementById('confirmFeedback');

        if (confirm.length === 0) {
            this.classList.remove('is-valid', 'is-invalid');
            feedback.textContent = '';
        } else if (password === confirm) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
            feedback.textContent = 'Passwords match';
            feedback.className = 'validation-feedback valid-feedback';
        } else {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            feedback.textContent = 'Passwords do not match';
            feedback.className = 'validation-feedback invalid-feedback';
        }
    });

    // Form Validation
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirmPassword').value;
        const terms = document.getElementById('terms').checked;

        if (!name || !email || !password || !confirm) {
            e.preventDefault();
            alert('Please fill in all fields');
            return;
        }

        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match');
            return;
        }

        if (!terms) {
            e.preventDefault();
            alert('Please agree to the Terms and Conditions');
            return;
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
</body>
</html>