<?php
/**
 * Simple Test Page for Train Button
 * No routing, no database calls - just pure HTML with buttons
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Train Model - Button Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        
        .button-row { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
            margin-bottom: 40px;
        }
        
        .button-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .button-card h2 { color: #667eea; margin-bottom: 20px; }
        .button-card button {
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .button-card button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .red-btn { background: #ff0000; color: white; }
        .red-btn:hover { background: #cc0000; }
        
        .green-btn { background: #00cc00; color: white; }
        .green-btn:hover { background: #00aa00; }
        
        .blue-btn { background: #0066ff; color: white; }
        .blue-btn:hover { background: #0044cc; }
        
        .orange-btn { background: #ff6b35; color: white; border: 3px solid yellow; }
        .orange-btn:hover { background: #ff5c3f; }
        
        .status { 
            background: #ffff00; 
            color: #000; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 30px;
            border: 3px solid #ff0000;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Train ML Model - Button Test Page</h1>
        
        <div class="status">
            ✓ PAGE LOADED - ALL BUTTONS SHOULD BE VISIBLE BELOW
        </div>
        
        <div class="button-row">
            <div class="button-card">
                <h2>Button 1</h2>
                <p>Red Button</p>
                <button class="red-btn" onclick="handleClick(1)">
                    🚀 TRAIN MODEL
                </button>
            </div>
            
            <div class="button-card">
                <h2>Button 2</h2>
                <p>Green Button</p>
                <button class="green-btn" onclick="handleClick(2)">
                    ✓ START TRAINING
                </button>
            </div>
            
            <div class="button-card">
                <h2>Button 3</h2>
                <p>Blue Button</p>
                <button class="blue-btn" onclick="handleClick(3)">
                    ⚙️ EXECUTE MODEL
                </button>
            </div>
            
            <div class="button-card">
                <h2>Button 4</h2>
                <p>Orange Button</p>
                <button class="orange-btn" onclick="handleClick(4)">
                    💡 TRAIN NOW
                </button>
            </div>
        </div>
    </div>
    
    <script>
        console.log('✓ Test page loaded successfully');
        console.log('✓ All buttons are ready to click');
        
        function handleClick(buttonNum) {
            alert('✓ BUTTON ' + buttonNum + ' CLICKED!\n\nThis proves buttons ARE working on your system!\n\nThe issue must be with the ML Analytics page routing.');
            console.log('Button ' + buttonNum + ' clicked!');
        }
    </script>
</body>
</html>
