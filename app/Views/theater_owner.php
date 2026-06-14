<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Owner Dashboard - BookMyShow Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #080612;
            --bg-secondary: #0f0d1e;
            --bg-card: rgba(28, 25, 48, 0.7);
            --accent-glow: #e92e59;
            --accent-glow-rgb: 233, 46, 89;
            --text-main: #ffffff;
            --text-muted: #8e8ba8;
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
            font-size: 19px;
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 28px;
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
            font-size: 19px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-grid .full-width {
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

        /* List Layouts */
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

        .badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending { background: rgba(243, 156, 18, 0.15); color: #f39c12; }
        .badge-active { background: rgba(46, 204, 113, 0.15); color: #2ecc71; }

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
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="brand">🎟️ Theater Owner Panel</div>
        
        <ul class="menu-list">
            <li class="menu-item active" id="menu-overview">
                <button onclick="switchSection('overview')">📊 Overview</button>
            </li>
            <li class="menu-item" id="menu-theaters">
                <button onclick="switchSection('theaters')">🏛️ Manage Theaters</button>
            </li>
            <li class="menu-item" id="menu-screens">
                <button onclick="switchSection('screens')">🖥️ Screens & Layouts</button>
            </li>
            <li class="menu-item" id="menu-shows">
                <button onclick="switchSection('shows')">📅 Schedule Shows</button>
            </li>
        </ul>

        <div class="user-profile">
            <div class="user-avatar" id="avatar-letter">O</div>
            <div class="user-info">
                <span class="user-name" id="profile-name">Owner</span>
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
                    <span class="stat-label">My Theaters</span>
                    <span class="stat-value" id="stat-theaters-count">0</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Total Screens</span>
                    <span class="stat-value" id="stat-screens-count">0</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Active Shows</span>
                    <span class="stat-value" id="stat-shows-count">0</span>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">Live Schedule Status</div>
                <p style="color: var(--text-muted); font-size: 15px;">Welcome to your Theater Management System. Use the sidebar to register theaters, define screen layouts, generate seats, and schedule movie screenings.</p>
            </div>
        </div>

        <!-- Section: Manage Theaters -->
        <div id="section-theaters" class="hidden-section">
            <div class="section-card">
                <div class="section-header">Register New Theater</div>
                <form class="form-grid" onsubmit="handleRegisterTheater(event)">
                    <div class="input-group">
                        <label for="theater-name">Theater Name</label>
                        <input type="text" id="theater-name" required placeholder="PVR IMAX Multiplex">
                    </div>
                    <div class="input-group">
                        <label for="theater-email">Business Email</label>
                        <input type="email" id="theater-email" required placeholder="contact@theater.com">
                    </div>
                    <div class="input-group">
                        <label for="theater-mobile">Mobile Number</label>
                        <input type="tel" id="theater-mobile" required placeholder="9876543210">
                    </div>
                    <div class="input-group">
                        <label for="theater-postal">Postal/Zip Code</label>
                        <input type="text" id="theater-postal" required placeholder="700001">
                    </div>
                    <div class="input-group">
                        <label for="theater-city">City</label>
                        <input type="text" id="theater-city" required placeholder="Kolkata">
                    </div>
                    <div class="input-group">
                        <label for="theater-state">State</label>
                        <input type="text" id="theater-state" required placeholder="West Bengal">
                    </div>
                    <div class="input-group">
                        <label for="theater-country">Country</label>
                        <input type="text" id="theater-country" required value="India">
                    </div>
                    <div class="input-group">
                        <label for="theater-address">Address Line 1</label>
                        <input type="text" id="theater-address" required placeholder="12, Park Street">
                    </div>
                    <button type="submit" class="btn-submit">Register Theater</button>
                </form>
            </div>

            <div class="section-card">
                <div class="section-header">My Theaters Catalog</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Mobile</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="theaters-table-body">
                        <!-- Loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Manage Screens & Layouts -->
        <div id="section-screens" class="hidden-section">
            <div class="section-card">
                <div class="section-header">Add New Screen</div>
                <form class="form-grid" onsubmit="handleCreateScreen(event)">
                    <div class="input-group">
                        <label for="screen-theater-select">Select Theater</label>
                        <select id="screen-theater-select" required>
                            <option value="">-- Choose Theater --</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="screen-name">Screen Name</label>
                        <input type="text" id="screen-name" required placeholder="Audi 1">
                    </div>
                    <div class="input-group">
                        <label for="screen-type">Screen Type</label>
                        <select id="screen-type" required>
                            <option value="regular">Regular</option>
                            <option value="gold">Gold Class</option>
                            <option value="imax">IMAX</option>
                            <option value="4dx">4DX</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="screen-seats">Total Seats Estimate</label>
                        <input type="number" id="screen-seats" required placeholder="120">
                    </div>
                    <button type="submit" class="btn-submit">Create Screen</button>
                </form>
            </div>

            <div class="section-card">
                <div class="section-header">Generate Layout Seats</div>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">Use this form to generate rows (A-Z) and columns (1-20) of seat layout matrices for any newly created screen.</p>
                <form class="form-grid" onsubmit="handleGenerateSeats(event)">
                    <div class="input-group">
                        <label for="layout-screen-select">Select Screen</label>
                        <select id="layout-screen-select" required>
                            <option value="">-- Choose Screen --</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="layout-rows">Number of Rows</label>
                        <input type="number" id="layout-rows" min="1" max="26" required placeholder="10 (Rows A to J)">
                    </div>
                    <div class="input-group">
                        <label for="layout-cols">Seats Per Row</label>
                        <input type="number" id="layout-cols" min="1" max="25" required placeholder="12">
                    </div>
                    <button type="submit" class="btn-submit">Generate Layout</button>
                </form>
            </div>
        </div>

        <!-- Section: Schedule Shows -->
        <div id="section-shows" class="hidden-section">
            <div class="section-card">
                <div class="section-header">Schedule Movie Show</div>
                <form class="form-grid" onsubmit="handleCreateShow(event)">
                    <div class="input-group">
                        <label for="show-screen-select">Select Screen</label>
                        <select id="show-screen-select" required>
                            <option value="">-- Choose Screen --</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="show-movie-select">Select Movie</label>
                        <select id="show-movie-select" required>
                            <option value="">-- Choose Movie --</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="show-start">Start Time</label>
                        <input type="datetime-local" id="show-start" required>
                    </div>
                    <div class="input-group">
                        <label for="show-price">Seat Ticket Price (INR)</label>
                        <input type="number" id="show-price" required placeholder="250.00">
                    </div>
                    <div class="input-group">
                        <label for="show-format">Format</label>
                        <select id="show-format" required>
                            <option value="2D">2D</option>
                            <option value="3D">3D</option>
                            <option value="IMAX 3D">IMAX 3D</option>
                            <option value="4DX">4DX</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="show-lang">Show Language</label>
                        <input type="text" id="show-lang" placeholder="English (Leave blank to use movie default)">
                    </div>
                    <button type="submit" class="btn-submit">Schedule Show</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alert Toast -->
    <div id="toast" class="toast">Message here</div>

    <script>
        let currentUser = null;
        let myTheaters = [];
        let myScreens = [];

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

        async function verifyOwner() {
            try {
                const response = await apiCall('/api/profile');
                const result = await response.json();
                
                if (response.ok && result.success && result.data && result.data.role === 'theater_owner') {
                    currentUser = result.data;
                    document.getElementById('profile-name').innerText = currentUser.name;
                    document.getElementById('avatar-letter').innerText = currentUser.name.charAt(0).toUpperCase();
                    loadTheaters();
                    loadMoviesDropdown();
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
            const sections = ['overview', 'theaters', 'screens', 'shows'];
            sections.forEach(s => {
                document.getElementById(`section-${s}`).classList.add('hidden-section');
                document.getElementById(`menu-${s}`).classList.remove('active');
            });

            document.getElementById(`section-${sectionId}`).classList.remove('hidden-section');
            document.getElementById(`menu-${sectionId}`).classList.add('active');

            const titles = {
                'overview': 'Dashboard Overview',
                'theaters': 'Manage My Theaters',
                'screens': 'Configure Screens & Layouts',
                'shows': 'Schedule Movie Shows'
            };
            document.getElementById('section-title').innerText = titles[sectionId];
        }

        async function loadTheaters() {
            try {
                const response = await apiCall('/api/theaters');
                const result = await response.json();

                if (response.ok && result.success) {
                    myTheaters = result.data;
                    document.getElementById('stat-theaters-count').innerText = myTheaters.length;

                    const tableBody = document.getElementById('theaters-table-body');
                    const theaterSelect = document.getElementById('screen-theater-select');
                    tableBody.innerHTML = '';
                    theaterSelect.innerHTML = '<option value="">-- Choose Theater --</option>';

                    if (myTheaters.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No theaters registered yet.</td></tr>';
                        return;
                    }

                    myTheaters.forEach(theater => {
                        const row = `
                            <tr>
                                <td style="font-weight: 500;">${theater.name}</td>
                                <td>${theater.city}</td>
                                <td>${theater.mobile}</td>
                                <td><span class="badge badge-${theater.status}">${theater.status}</span></td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                        theaterSelect.insertAdjacentHTML('beforeend', `<option value="${theater.id}">${theater.name}</option>`);
                    });

                    // Trigger screen lookup for all owned theaters
                    loadAllScreens();
                }
            } catch (err) {
                showToast('Failed to load theaters list', 'error');
            }
        }

        async function loadAllScreens() {
            myScreens = [];
            let screenCount = 0;
            const layoutSelect = document.getElementById('layout-screen-select');
            const showScreenSelect = document.getElementById('show-screen-select');
            
            layoutSelect.innerHTML = '<option value="">-- Choose Screen --</option>';
            showScreenSelect.innerHTML = '<option value="">-- Choose Screen --</option>';

            for (const theater of myTheaters) {
                try {
                    const response = await apiCall(`/api/theaters/${theater.id}/screens`);
                    const result = await response.json();
                    if (response.ok && result.success) {
                        const screens = result.data;
                        screenCount += screens.length;
                        screens.forEach(screen => {
                            myScreens.push(screen);
                            const option = `<option value="${screen.id}">${theater.name} - ${screen.name}</option>`;
                            layoutSelect.insertAdjacentHTML('beforeend', option);
                            showScreenSelect.insertAdjacentHTML('beforeend', option);
                        });
                    }
                } catch (err) {}
            }
            document.getElementById('stat-screens-count').innerText = screenCount;
        }

        async function loadMoviesDropdown() {
            try {
                const response = await apiCall('/api/movies');
                const result = await response.json();
                if (response.ok && result.success) {
                    const movieSelect = document.getElementById('show-movie-select');
                    movieSelect.innerHTML = '<option value="">-- Choose Movie --</option>';
                    const movies = result.data.items || [];
                    movies.forEach(movie => {
                        movieSelect.insertAdjacentHTML('beforeend', `<option value="${movie.id}">${movie.title}</option>`);
                    });
                }
            } catch (err) {}
        }

        async function handleRegisterTheater(e) {
            e.preventDefault();
            const name = document.getElementById('theater-name').value;
            const email = document.getElementById('theater-email').value;
            const mobile = document.getElementById('theater-mobile').value;
            const postal_code = document.getElementById('theater-postal').value;
            const city = document.getElementById('theater-city').value;
            const state = document.getElementById('theater-state').value;
            const country = document.getElementById('theater-country').value;
            const address_line_1 = document.getElementById('theater-address').value;

            try {
                const response = await apiCall('/api/theaters/register', {
                    method: 'POST',
                    body: JSON.stringify({ name, email, mobile, postal_code, city, state, country, address_line_1 })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Theater registered successfully!', 'success');
                    e.target.reset();
                    loadTheaters();
                } else {
                    showToast(result.message || 'Registration failed', 'error');
                }
            } catch (err) {
                showToast('Error registering theater', 'error');
            }
        }

        async function handleCreateScreen(e) {
            e.preventDefault();
            const theaterId = document.getElementById('screen-theater-select').value;
            const name = document.getElementById('screen-name').value;
            const type = document.getElementById('screen-type').value;
            const total_seats = document.getElementById('screen-seats').value;

            try {
                const response = await apiCall(`/api/theaters/${theaterId}/screens`, {
                    method: 'POST',
                    body: JSON.stringify({ name, type, total_seats })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Screen created successfully!', 'success');
                    e.target.reset();
                    loadTheaters();
                } else {
                    showToast(result.message || 'Failed to create screen', 'error');
                }
            } catch (err) {
                showToast('Error creating screen', 'error');
            }
        }

        async function handleGenerateSeats(e) {
            e.preventDefault();
            const screenId = document.getElementById('layout-screen-select').value;
            const rows = document.getElementById('layout-rows').value;
            const columns = document.getElementById('layout-cols').value;

            try {
                const response = await apiCall(`/api/screens/${screenId}/seats`, {
                    method: 'POST',
                    body: JSON.stringify({ rows, columns })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(`Layout generated! Created ${result.data.total_seats} seats.`, 'success');
                    e.target.reset();
                } else {
                    showToast(result.message || 'Generation failed', 'error');
                }
            } catch (err) {
                showToast('Error generating screen seats', 'error');
            }
        }

        async function handleCreateShow(e) {
            e.preventDefault();
            const screenId = document.getElementById('show-screen-select').value;
            const movie_id = document.getElementById('show-movie-select').value;
            const start_time = document.getElementById('show-start').value;
            const price = document.getElementById('show-price').value;
            const format = document.getElementById('show-format').value;
            const language = document.getElementById('show-lang').value;

            const body = { movie_id, start_time: start_time.replace('T', ' ') + ':00', price, format };
            if (language) body.language = language;

            try {
                const response = await apiCall(`/api/screens/${screenId}/shows`, {
                    method: 'POST',
                    body: JSON.stringify(body)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast('Show scheduled successfully!', 'success');
                    e.target.reset();
                    // update shows counter
                    let showsCountElem = document.getElementById('stat-shows-count');
                    showsCountElem.innerText = parseInt(showsCountElem.innerText) + 1;
                } else {
                    showToast(result.message || 'Scheduling failed', 'error');
                }
            } catch (err) {
                showToast('Error scheduling movie show', 'error');
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

        verifyOwner();
    </script>
</body>
</html>
