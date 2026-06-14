<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Dashboard Overview<?= $this->endSection() ?>

<?= $this->section('page-title') ?>Dashboard Overview<?= $this->endSection() ?>

<?= $this->section('extra-css') ?>
<style>
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

    .movie-meta {
        font-size: 13px;
        color: var(--text-muted);
        display: flex;
        justify-content: space-between;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
<?= $this->endSection() ?>

<?= $this->section('extra-js') ?>
<script>
    function onAdminVerified() {
        loadMovies();
    }

    async function loadMovies() {
        try {
            const response = await apiCall('/api/movies');
            const result = await response.json();

            if (response.ok && result.success) {
                const movies = result.data.items;
                document.getElementById('stat-movies-count').innerText = movies.length;
                
                const overviewGrid = document.getElementById('overview-movies-grid');
                overviewGrid.innerHTML = '';

                if (movies.length === 0) {
                    overviewGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: span 4;">No movies registered yet.</p>';
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
                });
            }
        } catch (err) {
            showToast('Failed to load movies catalog', 'error');
        }
    }
</script>
<?= $this->endSection() ?>
