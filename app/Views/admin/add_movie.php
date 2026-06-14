<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Register New Movie<?= $this->endSection() ?>

<?= $this->section('page-title') ?>Register Movie<?= $this->endSection() ?>

<?= $this->section('extra-css') ?>
<style>
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
                <label for="movie-genre">Genre</label>
                <input type="text" id="movie-genre" required placeholder="Action, Sci-Fi, Thriller">
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
<?= $this->endSection() ?>

<?= $this->section('extra-js') ?>
<script>
    function onAdminVerified() {
        // Nothing specific to preload, validation completed
    }

    async function handleCreateMovie(e) {
        e.preventDefault();
        const title = document.getElementById('movie-title').value;
        const duration_minutes = document.getElementById('movie-duration').value;
        const language = document.getElementById('movie-lang').value;
        const genre = document.getElementById('movie-genre').value;
        const poster_url = document.getElementById('movie-poster').value;
        const description = document.getElementById('movie-desc').value;

        try {
            const response = await apiCall('/api/movies', {
                method: 'POST',
                body: JSON.stringify({ title, duration_minutes, language, genre, poster_url, description })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast('Movie registered successfully!', 'success');
                e.target.reset();
                setTimeout(() => {
                    window.location.href = '/admin/movies';
                }, 1000);
            } else {
                showToast(result.message || 'Failed to register movie', 'error');
            }
        } catch (err) {
            showToast('Error registering movie', 'error');
        }
    }
</script>
<?= $this->endSection() ?>
