<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMyShow Clone Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0715;
            --accent-glow: #e92e59;
            --accent-glow-rgb: 233, 46, 89;
            --text-main: #ffffff;
            --text-muted: #8c89a8;
            --border-color: rgba(255, 255, 255, 0.05);
            --gradient-btn: linear-gradient(135deg, #e92e59 0%, #ff6b6b 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }

        body {
            background-color: var(--bg-primary);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(var(--accent-glow-rgb), 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(81, 45, 168, 0.15) 0%, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .portal-card {
            width: 100%;
            max-width: 700px;
            background: rgba(26, 23, 44, 0.55);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 50px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
            text-align: center;
        }

        .logo {
            font-size: 38px;
            font-weight: 800;
            background: var(--gradient-btn);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 16px;
            color: var(--text-muted);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .routes-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .route-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }

        .route-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--accent-glow);
            box-shadow: 0 10px 25px rgba(var(--accent-glow-rgb), 0.15);
        }

        .route-icon {
            font-size: 36px;
            background: rgba(var(--accent-glow-rgb), 0.1);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(var(--accent-glow-rgb), 0.2);
        }

        .route-title {
            font-size: 18px;
            font-weight: 600;
        }

        .route-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--gradient-btn);
            color: var(--text-main);
            text-decoration: none;
            border-radius: 12px;
            padding: 16px 36px;
            font-size: 16px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(var(--accent-glow-rgb), 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(var(--accent-glow-rgb), 0.45);
        }

        footer {
            margin-top: 30px;
            font-size: 13px;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="portal-card">
        <div class="logo">🎟️ BookMyShow Portal</div>
        <div class="subtitle">Welcome to the BookMyShow clone web interface. Authenticate to explore developer panels, theater schedules, screen management, and API integrations.</div>

        <div class="routes-grid">
            <a href="/admin" class="route-card">
                <div class="route-icon">⚡</div>
                <div class="route-title">Admin Dashboard</div>
                <div class="route-desc">Register new movies, review the system catalogs, and run seat-lock cleanups.</div>
            </a>
            
            <a href="/theater-owner" class="route-card">
                <div class="route-icon">🎬</div>
                <div class="route-title">Theater Owner Panel</div>
                <div class="route-desc">Register theaters, create screens, generate seat layouts, and schedule shows.</div>
            </a>
        </div>

        <a href="/login" class="btn-primary">Sign In / Register Account</a>

        <footer>
            Built with CodeIgniter 4 PHP RESTful API & Node.js WebSockets
        </footer>
    </div>

</body>
</html>
