<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - <?php echo env('APP_NAME'); ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #1e3a8a 100%);
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
        }

        .auth-wrapper {
            position: absolute;
            width: 100%;
            height: 100%;
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
            margin-bottom: 24px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .auth-form input {
            width: 100%;
            padding: 14px;
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
            align-items: stretch;
        }

        .password-field-wrapper input {
            flex: 1;
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
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
            z-index: 10;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .auth-form .btn {
            width: 100%;
            padding: 14px;
            margin-top: 15px;
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
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
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
            .auth-wrapper {
                position: relative;
                padding: 20px 0;
                min-height: 100vh;
            }

            .auth-container {
                padding: 40px 30px;
                margin: 20px;
                border-radius: 12px;
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
        }

        @media (max-width: 480px) {
            body {
                height: auto;
                min-height: 100vh;
                padding: 10px 0;
            }

            .auth-wrapper {
                position: relative;
                height: auto;
                min-height: 100vh;
                padding: 15px 0;
            }

            .auth-container {
                padding: 30px 20px;
                margin: 0 auto;
                width: calc(100% - 20px);
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
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

            .btn {
                padding: 11px;
                margin-top: 10px;
                font-size: 13px;
                border-radius: 6px;
            }

            .auth-form .auth-link {
                margin-top: 20px;
                padding-top: 20px;
            }

            .auth-form .auth-link p {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .auth-form .auth-link a {
                font-size: 12px;
                margin-left: 4px;
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

            .auth-form input {
                padding: 10px;
                font-size: 14px;
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
                <p>Sign In to Your Account</p>
            </div>

            <form class="auth-form" id="login-form" method="POST" action="<?php echo url('/api/login'); ?>">
                <div id="message"></div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="admin@calamba.gov.ph" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-field-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" id="password-toggle" onclick="togglePassword('password')"></button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">→</button>
                </div>

                <div class="auth-link">
                    <p>Don't have an account? <a href="<?php echo url('/register'); ?>">Create one</a></p>
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

        // Initialize toggle icon
        document.getElementById('password-toggle').textContent = '👁️‍🗨';

        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = document.getElementById('login-form');
            const button = form.querySelector('button');
            const messageDiv = document.getElementById('message');
            
            button.disabled = true;

            const formData = new FormData(form);

            try {
                const response = await fetch('<?php echo url('/api/login'); ?>', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    messageDiv.innerHTML = '<div class="alert alert-success">Sign in successful! Redirecting...</div>';
                    setTimeout(() => {
                        window.location.href = data.redirect || '<?php echo url('/dashboard'); ?>';
                    }, 1500);
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${data.error || 'Sign in failed'}</div>`;
                    button.disabled = false;
                    button.textContent = '→';
                }
            } catch (error) {
                messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                button.disabled = false;
                button.textContent = '→';
                console.error('Login error:', error);
            }
        });
    </script>
</body>
</html>

