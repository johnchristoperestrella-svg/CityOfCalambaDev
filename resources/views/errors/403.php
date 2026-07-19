<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Unauthorized</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 650px;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 8px rgba(59, 130, 246, 0.2));
        }
        
        .error-code {
            font-size: 90px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
            line-height: 1;
        }
        
        .error-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .error-subtitle {
            font-size: 18px;
            color: #3b82f6;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .reason-list {
            text-align: left;
            background: #f9f9f9;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            font-size: 15px;
        }
        
        .reason-list li {
            margin-bottom: 10px;
            color: #555;
            list-style-position: inside;
        }
        
        .reason-list li:last-child {
            margin-bottom: 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }
        
        .btn-secondary:hover {
            background: #fff5f7;
            transform: translateY(-3px);
        }
        
        .security-icon {
            width: 200px;
            height: 200px;
            margin: -40px auto 20px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(30, 64, 175, 0.1) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 100px;
        }
        
        @media (max-width: 600px) {
            .error-container {
                padding: 40px 25px;
            }
            
            .error-code {
                font-size: 70px;
            }
            
            .error-title {
                font-size: 28px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="security-icon">🔐</div>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>
        <div class="error-subtitle">Unauthorized Access</div>
        
        <p class="error-message">
            You don't have permission to access this resource. This area is restricted to authorized administrators only.
        </p>
        
        <div class="reason-list">
            <strong style="color: #333;">This could be because:</strong>
            <ul style="margin-top: 12px;">
                <li>Your user account doesn't have the required permissions</li>
                <li>You need administrator privileges to access this page</li>
                <li>Your session may have expired or been revoked</li>
                <li>The resource you're trying to access is restricted</li>
            </ul>
        </div>
        
        <p class="error-message" style="color: #888; font-size: 14px;">
            If you believe you should have access to this resource, please contact your system administrator.
        </p>
        
        <div class="action-buttons">
            <a href="<?php echo url('/dashboard'); ?>" class="btn btn-primary">Go to Dashboard</a>
            <a href="<?php echo url('/'); ?>" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</body>
</html>
