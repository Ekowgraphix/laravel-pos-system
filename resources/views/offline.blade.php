<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Winniema's Enterprise</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            text-align: center;
            padding: 40px 20px;
            max-width: 600px;
        }

        .icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        p {
            font-size: 20px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .retry-btn {
            background: #fff;
            color: #667eea;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .features {
            margin-top: 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            text-align: left;
        }

        .feature {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .feature h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .feature p {
            font-size: 14px;
            opacity: 0.8;
            margin: 0;
        }

        .status {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            font-size: 14px;
            margin-top: 20px;
        }

        .online {
            background: rgba(40, 167, 69, 0.8);
        }

        .offline {
            background: rgba(220, 53, 69, 0.8);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📡</div>
        <h1>You're Offline</h1>
        <p>
            It looks like you've lost your internet connection.<br>
            Don't worry, you can still access cached content!
        </p>
        
        <div class="status" id="status">
            <span id="statusText">Offline</span>
        </div>

        <br><br>
        <button class="retry-btn" onclick="location.reload()">
            Try Again
        </button>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">💾</div>
                <h3>Cached Data</h3>
                <p>View previously loaded pages</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🔄</div>
                <h3>Auto-Sync</h3>
                <p>Changes sync when back online</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📱</div>
                <h3>PWA Ready</h3>
                <p>Install as mobile app</p>
            </div>
        </div>
    </div>

    <script>
        // Monitor connection status
        const statusEl = document.getElementById('status');
        const statusText = document.getElementById('statusText');

        function updateStatus() {
            if (navigator.onLine) {
                statusEl.className = 'status online';
                statusText.textContent = 'Back Online!';
                setTimeout(() => location.reload(), 1000);
            } else {
                statusEl.className = 'status offline';
                statusText.textContent = 'Offline';
            }
        }

        window.addEventListener('online', updateStatus);
        window.addEventListener('offline', updateStatus);

        // Auto-retry every 30 seconds
        setInterval(() => {
            if (navigator.onLine) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
