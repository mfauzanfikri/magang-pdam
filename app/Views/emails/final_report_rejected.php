<p>Halo <?= esc($name) ?>,</p>

<p>Mohon maaf, laporan akhir untuk proposal <strong><?= esc($proposal) ?></strong> telah <strong>ditolak</strong>.</p>

<p>Judul laporan: <strong><?= esc($finalReport) ?></strong></p>

<?php if ($notes): ?>
    <p><strong>Catatan dari reviewer:</strong><br><?= nl2br(esc($notes)) ?></p>
<?php endif; ?>

<p>Silakan revisi dan ajukan kembali jika diperlukan.</p>

<p>Hormat kami,<br>Tim Magang PDAM</p>
