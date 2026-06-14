<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>System Administration<?= $this->endSection() ?>

<?= $this->section('page-title') ?>System Administration<?= $this->endSection() ?>

<?= $this->section('extra-css') ?>
<style>
    .cleanup-box {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 25px;
        background: rgba(233, 46, 89, 0.03);
        border: 1px dashed rgba(233, 46, 89, 0.15);
        border-radius: 16px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="section-card">
        <div class="section-header">Database Optimizations</div>
        <div class="cleanup-box">
            <h3 style="font-weight: 600;">Clean Expired Seat Locks</h3>
            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.5;">Releases show seats that were locked by users during ticket checkouts but did not result in a completed booking within the 5-minute checkout window.</p>
            <button class="btn-submit" onclick="triggerSeatCleanup()" style="margin-top: 10px;">Run Cleanup Process</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('extra-js') ?>
<script>
    function onAdminVerified() {
        // Ready
    }

    async function triggerSeatCleanup() {
        try {
            const response = await apiCall('/api/shows/cleanup-locks', {
                method: 'POST'
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
</script>
<?= $this->endSection() ?>
