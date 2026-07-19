<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - <?php echo env('APP_NAME'); ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #1e3a8a 100%);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 0 40px rgba(37, 99, 235, 0.15);
            animation: slideIn 0.5s ease;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-header h1 {
            font-size: 36px;
            margin: 0 0 12px 0;
            color: #2563eb;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            color: #6b7280;
            margin: 0;
            font-size: 15px;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            border: 3px solid #2563eb;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .auth-form .form-group {
            margin-bottom: 20px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .auth-form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-sizing: border-box;
        }

        .auth-form input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .auth-form input:hover {
            border-color: #9ca3af;
        }

        .password-field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-field-wrapper input {
            flex: 1;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6b7280;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .password-requirements {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.5;
        }

        .auth-form .btn {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .auth-form .btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .auth-form .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .auth-form .auth-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 28px;
            border-top: 1px solid #e5e7eb;
        }

        .auth-form .auth-link p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .auth-form .auth-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
            margin-left: 6px;
            transition: color 0.3s;
        }

        .auth-form .auth-link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .alert {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 5px solid;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-left-color: #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border-left-color: #2563eb;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .auth-container {
                padding: 40px 30px;
            }

            .auth-header h1 {
                font-size: 32px;
            }

            .auth-header p {
                font-size: 14px;
            }

            .auth-form input {
                padding: 12px;
                font-size: 16px;
            }

            .auth-form label {
                font-size: 13px;
            }

            .btn {
                padding: 12px;
                font-size: 14px;
            }

            .password-requirements {
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .auth-container {
                padding: 30px 20px;
                border-radius: 12px;
            }

            .auth-header {
                margin-bottom: 30px;
            }

            .auth-header h1 {
                font-size: 26px;
                margin-bottom: 10px;
            }

            .auth-header p {
                font-size: 13px;
            }

            .auth-form .form-group {
                margin-bottom: 16px;
            }

            .auth-form label {
                margin-bottom: 6px;
                font-size: 12px;
            }

            .auth-form input {
                padding: 11px;
                font-size: 16px;
                border-radius: 6px;
            }

            .auth-form input:focus {
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            }

            .password-requirements {
                font-size: 11px;
                margin-top: 4px;
                line-height: 1.4;
            }

            .password-requirements li {
                margin-bottom: 3px;
            }

            .btn {
                padding: 11px;
                margin-top: 15px;
                font-size: 13px;
                border-radius: 6px;
            }

            .auth-form .auth-link {
                margin-top: 20px;
                padding-top: 20px;
            }

            .auth-form .auth-link p {
                font-size: 12px;
            }

            .alert {
                padding: 10px;
                font-size: 12px;
                border-radius: 6px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 360px) {
            .auth-container {
                padding: 25px 15px;
            }

            .auth-header h1 {
                font-size: 22px;
            }

            .auth-header p {
                font-size: 12px;
            }

            .btn {
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-header">
                <img src="https://calambacity.gov.ph/maincss/assets/img/logocity.webp" alt="Calamba City Logo" class="auth-logo">
                <h1> PopDev</h1>
                <p>Create Your Account</p>
            </div>

            <form class="auth-form" id="register-form" method="POST" action="<?php echo url('/api/register'); ?>">
                <div id="message"></div>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="yourname@calamba.gov.ph" required>
                    <div class="password-requirements" style="color: #6b7280; margin-top: 6px;">
                        Must be a @calamba.gov.ph email address
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
                        <button type="button" class="password-toggle" id="password-toggle" onclick="togglePassword('password')"></button>
                    </div>
                    <div class="password-requirements">
                        Minimum 8 characters<br>
                        Use letters, numbers, and symbols
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                        <button type="button" class="password-toggle" id="confirm-password-toggle" onclick="togglePassword('confirm_password')"></button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Create Account</button>
                </div>

                <div class="auth-link">
                    <p>Already have an account? <a href="<?php echo url('/'); ?>">Sign in</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = document.getElementById(fieldId + '-toggle');
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.textContent = '👁'; // Eye icon (visible)
                toggle.title = 'Hide password';
            } else {
                field.type = 'password';
                toggle.textContent = '👁️‍🗨'; // Eye with slash (hidden)
                toggle.title = 'Show password';
            }
        }

        // Initialize toggle icons
        document.getElementById('password-toggle').textContent = '👁️‍🗨';
        document.getElementById('confirm-password-toggle').textContent = '👁️‍🗨';

        const registerForm = document.getElementById('register-form');
        const emailInput = document.getElementById('email');
        const submitButton = registerForm.querySelector('button');

        // Do a quick email existence check on blur
        emailInput.addEventListener('blur', async () => {
            const messageDiv = document.getElementById('message');
            const email = emailInput.value.trim();
            if (!email) return;
            try {
                const resp = await fetch('<?php echo url('/api/check-email'); ?>?email=' + encodeURIComponent(email));
                const data = await resp.json();
                if (resp.ok && data.exists) {
                    messageDiv.innerHTML = '<div class="alert alert-danger">âœ— Email already registered</div>';
                    submitButton.disabled = true;
                } else {
                    // Clear message if email not exists
                    if (messageDiv && messageDiv.innerHTML.indexOf('Email already registered') !== -1) {
                        messageDiv.innerHTML = '';
                    }
                    submitButton.disabled = false;
                }
            } catch (err) {
                // ignore network errors
            }
        });

        document.getElementById('register-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = registerForm;
            const button = submitButton;
            const messageDiv = document.getElementById('message');
            
            // Validate email is from @calamba.gov.ph domain
            const email = document.getElementById('email').value.toLowerCase();
            if (!email.endsWith('@calamba.gov.ph')) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Email must be from the @calamba.gov.ph domain</div>';
                return;
            }
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                messageDiv.innerHTML = '<div class="alert alert-danger">âœ— Passwords do not match</div>';
                return;
            }

            if (password.length < 8) {
                messageDiv.innerHTML = '<div class="alert alert-danger">âœ— Password must be at least 8 characters</div>';
                return;
            }

            button.disabled = true;
            button.textContent = 'Creating Account...';

            const formData = new FormData(form);

            try {
                const response = await fetch('<?php echo url('/api/register'); ?>', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    messageDiv.innerHTML = '<div class="alert alert-success">âœ“ Account created successfully! Redirecting to login...</div>';
                    setTimeout(() => {
                        window.location.href = '<?php echo url('/'); ?>';
                    }, 2000);
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${data.error || 'Registration failed'}</div>`;
                    button.disabled = false;
                    button.textContent = 'Create Account';
                }
            } catch (error) {
                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                button.disabled = false;
                button.textContent = 'Create Account';
                console.error('Registration error:', error);
            }
        });
    </script>
</body>
</html>



