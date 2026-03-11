<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>

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

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .form-check-label {
            color: #666;
            font-size: 14px;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .signup-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 15px;
        }

        .signup-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-login p {
            color: #999;
            font-size: 14px;
            position: relative;
            margin-bottom: 20px;
        }

        .social-login p::before,
        .social-login p::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e0e0e0;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-icon:hover {
            transform: translateY(-3px);
        }

        .social-icon.google {
            background: #DB4437;
        }

        .social-icon.facebook {
            background: #4267B2;
        }

        .social-icon.twitter {
            background: #1DA1F2;
        }

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
    </style>
</head>
<body>
<div class="login-container">
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= session()->get('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php session()->forget('error'); ?>
    <?php endif; ?>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->get('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php session()->forget('success'); ?>
    <?php endif; ?>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-shield-alt"></i>
            <h2>Admin Panel</h2>
            <p class="text-muted">Welcome back! Please login to your account.</p>
        </div>

        <form action="<?= route('login.authenticate') ?>" method="POST" id="loginForm">
            <!-- Email Field -->
            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email"
                       class="form-control"
                       name="email"
                       placeholder="Email Address"
                       value="<?= old('email') ?>"
                       required>
            </div>

            <!-- Password Field -->
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password"
                       class="form-control"
                       name="password"
                       id="password"
                       placeholder="Password"
                       required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="remember-forgot">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                <a href="<?= route('forgot-password') ?>" class="forgot-link">
                    Forgot Password?
                </a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-login w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>

            <!-- Sign Up Link -->
            <div class="signup-link">
                Don't have an account? <a href="<?= route('register') ?>">Sign up now</a>
            </div>
        </form>

        <!-- Social Login -->
        <div class="social-login">
            <p>Or continue with</p>
            <div class="social-icons">
                <a href="#" class="social-icon google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-icon facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle Password Visibility
    function togglePassword() {
        const password = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');

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

    // Form Validation
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const email = document.querySelector('input[name="email"]').value;
        const password = document.querySelector('input[name="password"]').value;

        if (!email || !password) {
            e.preventDefault();
            alert('Please fill in all fields');
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