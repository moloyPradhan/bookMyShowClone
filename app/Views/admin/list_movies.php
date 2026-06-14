<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Movies Catalog<?= $this->endSection() ?>

<?= $this->section('page-title') ?>Registered Movies Catalog<?= $this->endSection() ?>

<?= $this->section('extra-css') ?>
<style>
    /* Styling overrides specific to lists */
    .items-table td img {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="section-card">
        <div class="section-header">
            <span>Registered Movies</span>
            <a href="/admin/movies/add" class="btn-submit" style="text-decoration: none; font-size: 13px; padding: 10px 18px; display: inline-flex; align-items: center; gap: 8px;">➕ Add Movie</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Genre</th>
                        <th>Duration</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="catalog-movies-table-body">
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">Loading movies...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('extra-js') ?>
<script>
    function onAdminVerified() {
        loadMovies();
    }

    async function loadMovies() {
        console.log("Load movies...")

        try {
            const response = await apiCall('/api/movies');
            const result = await response.json();

            if (response.ok && result.success) {
                const movies = result.data.items;
                const catalogTableBody = document.getElementById('catalog-movies-table-body');
                catalogTableBody.innerHTML = '';

                if (movies.length === 0) {
                    catalogTableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">No movies registered yet.</td></tr>';
                    return;
                }

                movies.forEach(movie => {
                    const tableRow = `
                        <tr>
                            <td><img src="${movie.poster_url}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);" alt="${movie.title}"></td>
                            <td style="font-weight: 600;">${movie.title}</td>
                            <td>${movie.language}</td>
                            <td><span style="background: rgba(233, 46, 89, 0.15); color: #e92e59; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">${movie.genre}</span></td>
                            <td>${movie.duration_minutes}m</td>
                            <td style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-muted);" title="${movie.description}">${movie.description}</td>
                        </tr>
                    `;
                    catalogTableBody.insertAdjacentHTML('beforeend', tableRow);
                });
            } else {
                showToast(result.message || 'Failed to load movies', 'error');
            }
        } catch (err) {
            showToast('Failed to load movies catalog', 'error');
        }
    }
</script>
<?= $this->endSection() ?>
