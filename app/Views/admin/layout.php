<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - BMS Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0914;
            --bg-secondary: #121020;
            --bg-card: rgba(26, 23, 44, 0.7);
            --accent-glow: #e92e59;
            --accent-glow-rgb: 233, 46, 89;
            --text-main: #ffffff;
            --text-muted: #8c89a8;
            --border-color: rgba(255, 255, 255, 0.05);
            --gradient-btn: linear-gradient(135deg, #e92e59 0%, #ff6b6b 100%);
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            transition: background-color 0.3s, border-color 0.3s;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            background: var(--gradient-btn);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 40px;
            padding-left: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .menu-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item a {
            width: 100%;
            background: none;
            border: none;
            color: var(--text-muted);
            padding: 14px 16px;
            border-radius: 12px;
            cursor: pointer;
            text-align: left;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item a:hover, .menu-item.active a {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
        }

        .menu-item.active a {
            border-left: 3px solid var(--accent-glow);
            border-radius: 0 12px 12px 0;
            background: rgba(var(--accent-glow-rgb), 0.08);
            color: var(--text-main);
        }

        .user-profile {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 10px;
            border-top: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-btn);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .btn-logout {
            background: none;
            border: none;
            color: var(--accent-glow);
            font-size: 12px;
            cursor: pointer;
            text-align: left;
            margin-top: 2px;
            font-weight: 500;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
        }

        /* Form & Card Sections */
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Button & Form Elements */
        .btn-submit {
            background: var(--gradient-btn);
            color: var(--text-main);
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(var(--accent-glow-rgb), 0.25);
            align-self: flex-start;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(var(--accent-glow-rgb), 0.4);
        }

        /* Table layouts */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th, .items-table td {
            text-align: left;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            font-size: 15px;
        }

        .items-table th {
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(18, 16, 32, 0.95);
            border-left: 4px solid var(--accent-glow);
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 100;
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
            transition: all 0.4s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <?= $this->renderSection('extra-css') ?>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <a href="/admin" class="brand">🎟️ BMS Admin</a>
        
        <ul class="menu-list">
            <li class="menu-item <?= $activePage === 'overview' ? 'active' : '' ?>">
                <a href="/admin">📊 Overview</a>
            </li>
            <li class="menu-item <?= $activePage === 'add-movie' ? 'active' : '' ?>">
                <a href="/admin/movies/add">🎬 Register Movie</a>
            </li>
            <li class="menu-item <?= $activePage === 'list-movies' ? 'active' : '' ?>">
                <a href="/admin/movies">🍿 Movies Catalog</a>
            </li>
            <li class="menu-item <?= $activePage === 'system' ? 'active' : '' ?>">
                <a href="/admin/cleanup">⚙️ System Cleanup</a>
            </li>
        </ul>

        <div class="user-profile">
            <div class="user-avatar" id="avatar-letter">A</div>
            <div class="user-info">
                <span class="user-name" id="profile-name">Admin</span>
                <button class="btn-logout" onclick="handleLogout()">Sign Out</button>
            </div>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="main-content">
        <header>
            <h1 class="page-title"><?= $this->renderSection('page-title') ?></h1>
        </header>

        <?= $this->renderSection('content') ?>
    </div>

    <!-- Alert Toast -->
    <div id="toast" class="toast">Message here</div>

    <script>
        let currentUser = null;

        async function apiCall(url, options = {}) {
            options.credentials = 'include';
            if (!options.headers) {
                options.headers = {};
            }
            if (!(options.body instanceof FormData) && !options.headers['Content-Type'] && !options.headers['content-type']) {
                options.headers['Content-Type'] = 'application/json';
            }

            try {
                let response = await fetch(url, options);

                // If response is 401 Unauthorized, try refreshing the token
                if (response.status === 401 && !url.includes('/api/refresh') && !url.includes('/api/login')) {
                    console.log('Access token expired or unauthorized. Attempting token refresh...');
                    
                    const refreshResponse = await fetch('/api/refresh', {
                        method: 'POST',
                        credentials: 'include',
                        headers: { 'Content-Type': 'application/json' }
                    });

                    if (refreshResponse.ok) {
                        console.log('Token refreshed successfully. Retrying original request...');
                        response = await fetch(url, options);
                    } else {
                        console.warn('Token refresh failed. Redirecting to login...');
                        window.location.href = '/login';
                        throw new Error('Unauthorized');
                    }
                }

                return response;
            } catch (error) {
                console.error('API Call failed:', error);
                throw error;
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            toast.innerText = message;
            if (type === 'success') {
                toast.style.borderLeftColor = '#2ecc71';
            } else if (type === 'error') {
                toast.style.borderLeftColor = '#e74c3c';
            } else {
                toast.style.borderLeftColor = '#e92e59';
            }
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        async function verifyAdmin() {
            try {
                const response = await apiCall('/api/profile');
                const result = await response.json();
                
                if (response.ok && result.success && result.data && result.data.role === 'admin') {
                    currentUser = result.data;
                    document.getElementById('profile-name').innerText = currentUser.name;
                    document.getElementById('avatar-letter').innerText = currentUser.name.charAt(0).toUpperCase();
                    if (typeof onAdminVerified === 'function') {
                        onAdminVerified();
                    }
                } else {
                    showToast('Access Denied. Redirecting to login...', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                }
            } catch (err) {
                showToast('Authentication failed', 'error');
                window.location.href = '/login';
            }
        }

        async function handleLogout() {
            try {
                await apiCall('/api/logout', { method: 'POST' });
                showToast('Signed out successfully', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1000);
            } catch (err) {
                window.location.href = '/login';
            }
        }

        verifyAdmin();
    </script>
    <?= $this->renderSection('extra-js') ?>
</body>
</html>
