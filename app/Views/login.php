<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - BookMyShow Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f0c1b;
            --bg-card: rgba(30, 27, 51, 0.65);
            --accent-glow: #e92e59;
            --accent-glow-rgb: 233, 46, 89;
            --text-main: #ffffff;
            --text-muted: #a5a2c2;
            --border-color: rgba(255, 255, 255, 0.08);
            --gradient-btn: linear-gradient(135deg, #e92e59 0%, #ff6b6b 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--bg-primary);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(var(--accent-glow-rgb), 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(81, 45, 168, 0.2) 0%, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(var(--accent-glow-rgb), 0.1) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            background: var(--gradient-btn);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        .tabs {
            display: flex;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            position: relative;
            z-index: 1;
        }

        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            color: var(--text-muted);
            padding: 12px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 10px;
        }

        .tab-btn.active {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group input, .input-group select {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--text-main);
            font-size: 15px;
            outline: none;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: var(--accent-glow);
            box-shadow: 0 0 10px rgba(var(--accent-glow-rgb), 0.2);
        }

        .btn-submit {
            background: var(--gradient-btn);
            color: var(--text-main);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(var(--accent-glow-rgb), 0.3);
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(var(--accent-glow-rgb), 0.45);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(30, 27, 51, 0.9);
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

        .hidden-form {
            display: none;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">🎟️ BookMyShow Clone</div>
            <div class="subtitle" id="auth-subtitle">Welcome back! Please login to continue.</div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('login')">Sign In</button>
            <button class="tab-btn" onclick="switchTab('register')">Register</button>
        </div>

        <!-- Login Form -->
        <form id="login-form" onsubmit="handleLogin(event)">
            <div class="input-group">
                <label for="login-email">Email Address</label>
                <input type="email" id="login-email" required placeholder="name@domain.com">
            </div>

            <div class="input-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="hidden-form" onsubmit="handleRegister(event)">
            <div class="input-group">
                <label for="reg-name">Full Name</label>
                <input type="text" id="reg-name" required placeholder="John Doe">
            </div>

            <div class="input-group">
                <label for="reg-email">Email Address</label>
                <input type="email" id="reg-email" required placeholder="john@domain.com">
            </div>

            <div class="input-group">
                <label for="reg-password">Password</label>
                <input type="password" id="reg-password" required placeholder="••••••••">
            </div>

            <div class="input-group">
                <label for="reg-role">Account Type</label>
                <select id="reg-role" required>
                    <option value="customer">Customer</option>
                    <option value="theater_owner">Theater Owner</option>
                    <option value="admin">Platform Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>
    </div>

    <!-- Alert Toast -->
    <div id="toast" class="toast">Message here</div>

    <script>
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

        function switchTab(mode) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const subtitle = document.getElementById('auth-subtitle');
            const tabs = document.querySelectorAll('.tab-btn');

            if (mode === 'login') {
                loginForm.classList.remove('hidden-form');
                registerForm.classList.add('hidden-form');
                subtitle.innerText = 'Welcome back! Please login to continue.';
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
            } else {
                loginForm.classList.add('hidden-form');
                registerForm.classList.remove('hidden-form');
                subtitle.innerText = 'Create a new account to unlock features.';
                tabs[0].classList.remove('active');
                tabs[1].classList.add('active');
            }
        }

        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        const user = result.data.user;
                        if (user.role === 'admin') {
                            window.location.href = '/admin';
                        } else if (user.role === 'theater_owner') {
                            window.location.href = '/theater-owner';
                        } else {
                            showToast('Logged in as customer. Standard views not configured.', 'info');
                        }
                    }, 1000);
                } else {
                    showToast(result.message || 'Login failed', 'error');
                }
            } catch (err) {
                showToast('Network error occurred', 'error');
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;
            const role = document.getElementById('reg-role').value;

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password, role })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Registration successful! Please sign in.', 'success');
                    switchTab('login');
                } else {
                    showToast(result.message || 'Registration failed', 'error');
                }
            } catch (err) {
                showToast('Network error occurred', 'error');
            }
        }

        // Auto redirect if already logged in
        async function checkSession() {
            try {
                const response = await fetch('/api/profile');
                if (response.ok) {
                    const result = await response.json();
                    if (result.success && result.data) {
                        const role = result.data.role;
                        if (role === 'admin') {
                            window.location.href = '/admin';
                        } else if (role === 'theater_owner') {
                            window.location.href = '/theater-owner';
                        }
                    }
                }
            } catch (err) {}
        }
        checkSession();
    </script>
</body>
</html>
