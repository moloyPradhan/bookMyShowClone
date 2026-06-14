<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BookMyShow Clone</title>
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
        }

        .menu-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item button {
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
            transition: all 0.3s;
        }

        .menu-item button:hover, .menu-item.active button {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
        }

        .menu-item.active button {
            border-left: 3px solid var(--accent-glow);
            border-radius: 0 12px 12px 0;
            background: rgba(var(--accent-glow-rgb), 0.08);
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Form & List Sections */
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

        .movie-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .movie-form .full-width {
            grid-column: span 2;
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
        }

        .input-group input, .input-group textarea, .input-group select {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text-main);
            font-size: 15px;
            outline: none;
            width: 100%;
        }

        .input-group input:focus, .input-group textarea:focus, .input-group select:focus {
            border-color: var(--accent-glow);
            box-shadow: 0 0 8px rgba(var(--accent-glow-rgb), 0.15);
        }

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

        /* Movie List Grid */
        .movies-list-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .movie-card {
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .movie-poster {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #222;
        }

        .movie-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .movie-title {
            font-size: 16px;
            font-weight: 600;
        }

        }

        /* Table Layouts */
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

        .hidden-section {
            display: none;
        }

        .cleanup-box {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            background: rgba(233, 46, 89, 0.05);
            border: 1px dashed rgba(233, 46, 89, 0.2);
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="brand">🎟️ BMS Admin</div>
        
        <ul class="menu-list">
            <li class="menu-item active" id="menu-overview">
                <button onclick="switchSection('overview')">📊 Overview</button>
            </li>
            <li class="menu-item" id="menu-movies">
                <button onclick="switchSection('movies')">🎬 Manage Movies</button>
            </li>
            <li class="menu-item" id="menu-system">
                <button onclick="switchSection('system')">⚙️ System Cleanup</button>
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
            <h1 class="page-title" id="section-title">Dashboard Overview</h1>
        </header>

        <!-- Section: Overview -->
        <div id="section-overview">
            <div class="dashboard-grid">
                <div class="stat-card">
                    <span class="stat-label">Total Movies</span>
                    <span class="stat-value" id="stat-movies-count">0</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">System Status</span>
                    <span class="stat-value" style="color: #2ecc71;">Active</span>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">Live Movies</div>
                <div class="movies-list-grid" id="overview-movies-grid">
                    <!-- Loaded dynamically -->
                </div>
            </div>
        </div>

        <!-- Section: Manage Movies -->
        <div id="section-movies" class="hidden-section">
            <div class="section-card">
                <div class="section-header">Register New Movie</div>
                <form class="movie-form" onsubmit="handleCreateMovie(event)">
                    <div class="input-group">
                        <label for="movie-title">Movie Title</label>
                        <input type="text" id="movie-title" required placeholder="Inception">
                    </div>

                    <div class="input-group">
                        <label for="movie-duration">Duration (Minutes)</label>
                        <input type="number" id="movie-duration" required placeholder="148">
                    </div>

                    <div class="input-group">
                        <label for="movie-lang">Language</label>
                        <input type="text" id="movie-lang" required placeholder="English">
                    </div>

                    <div class="input-group">
                        <label for="movie-format">Default Format</label>
                        <select id="movie-format" required>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="IMAX 3D">IMAX 3D</option>
                            <option value="4DX">4DX</option>
                        </select>
                    </div>

                    <div class="input-group full-width">
                        <label for="movie-poster">Poster Image URL</label>
                        <input type="url" id="movie-poster" required placeholder="https://images.cloudinary.com/...">
                    </div>

                    <div class="input-group full-width">
                        <label for="movie-desc">Description</label>
                        <textarea id="movie-desc" rows="4" required placeholder="A thief who steals corporate secrets through the use of dream-sharing technology..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Add Movie</button>
                </form>
            </div>

            <div class="section-card">
                <div class="section-header">Registered Movies Catalog</div>
                <div style="overflow-x: auto;">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Title</th>
                                <th>Language</th>
                                <th>Format</th>
                                <th>Duration</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="catalog-movies-table-body">
                            <!-- Loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section: System Cleanup -->
        <div id="section-system" class="hidden-section">
            <div class="section-card">
                <div class="section-header">Database Optimizations</div>
                <div class="cleanup-box">
                    <h3>Clean Expired Seat Locks</h3>
                    <p style="font-size: 14px; color: var(--text-muted);">Releases show seats that were locked by users but not booked within the 5-minute checkout window.</p>
                    <button class="btn-submit" onclick="triggerSeatCleanup()" style="margin-top: 10px;">Run Cleanup Process</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Toast -->
    <div id="toast" class="toast">Message here</div>

    <script>
        let currentUser = null;

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
                const response = await fetch('/api/profile');
                const result = await response.json();
                
                if (response.ok && result.success && result.data && result.data.role === 'admin') {
                    currentUser = result.data;
                    document.getElementById('profile-name').innerText = currentUser.name;
                    document.getElementById('avatar-letter').innerText = currentUser.name.charAt(0).toUpperCase();
                    loadMovies();
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

        function switchSection(sectionId) {
            const sections = ['overview', 'movies', 'system'];
            sections.forEach(s => {
                document.getElementById(`section-${s}`).classList.add('hidden-section');
                document.getElementById(`menu-${s}`).classList.remove('active');
            });

            document.getElementById(`section-${sectionId}`).classList.remove('hidden-section');
            document.getElementById(`menu-${sectionId}`).classList.add('active');

            const titles = {
                'overview': 'Dashboard Overview',
                'movies': 'Manage Movies Catalog',
                'system': 'System Administration'
            };
            document.getElementById('section-title').innerText = titles[sectionId];
        }

        async function loadMovies() {
            try {
                const response = await fetch('/api/movies');
                const result = await response.json();

                if (response.ok && result.success) {
                    const movies = result.data;
                    document.getElementById('stat-movies-count').innerText = movies.length;
                    
                    const overviewGrid = document.getElementById('overview-movies-grid');
                    const catalogTableBody = document.getElementById('catalog-movies-table-body');
                    
                    overviewGrid.innerHTML = '';
                    catalogTableBody.innerHTML = '';

                    if (movies.length === 0) {
                        overviewGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: span 4;">No movies registered yet.</p>';
                        catalogTableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">No movies registered yet.</td></tr>';
                        return;
                    }

                    movies.forEach(movie => {
                        const movieHtml = `
                            <div class="movie-card">
                                <img src="${movie.poster_url}" class="movie-poster" alt="${movie.title}">
                                <div class="movie-info">
                                    <div class="movie-title">${movie.title}</div>
                                    <div class="movie-meta">
                                        <span>⏱️ ${movie.duration_minutes}m</span>
                                        <span>🌐 ${movie.language}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        overviewGrid.insertAdjacentHTML('beforeend', movieHtml);

                        const tableRow = `
                            <tr>
                                <td><img src="${movie.poster_url}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);" alt="${movie.title}"></td>
                                <td style="font-weight: 600;">${movie.title}</td>
                                <td>${movie.language}</td>
                                <td><span style="background: rgba(233, 46, 89, 0.15); color: #e92e59; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase;">${movie.format}</span></td>
                                <td>${movie.duration_minutes}m</td>
                                <td style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-muted);" title="${movie.description}">${movie.description}</td>
                            </tr>
                        `;
                        catalogTableBody.insertAdjacentHTML('beforeend', tableRow);
                    });
                }
            } catch (err) {
                showToast('Failed to load movies catalog', 'error');
            }
        }

        async function handleCreateMovie(e) {
            e.preventDefault();
            const title = document.getElementById('movie-title').value;
            const duration_minutes = document.getElementById('movie-duration').value;
            const language = document.getElementById('movie-lang').value;
            const format = document.getElementById('movie-format').value;
            const poster_url = document.getElementById('movie-poster').value;
            const description = document.getElementById('movie-desc').value;

            try {
                const response = await fetch('/api/movies', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ title, duration_minutes, language, format, poster_url, description })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Movie registered successfully!', 'success');
                    e.target.reset();
                    loadMovies();
                    switchSection('overview');
                } else {
                    showToast(result.message || 'Failed to register movie', 'error');
                }
            } catch (err) {
                showToast('Error registering movie', 'error');
            }
        }

        async function triggerSeatCleanup() {
            try {
                const response = await fetch('/api/shows/cleanup-locks', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(`Cleanup successful! Released ${result.data.cleaned} expired seat locks.`, 'success');
                } else {
                    showToast(result.message || 'Cleanup failed', 'error');
                }
            } catch (err) {
                showToast('Error running seat locks cleanup', 'error');
            }
        }

        async function handleLogout() {
            try {
                await fetch('/api/logout', { method: 'POST' });
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
</body>
</html>
