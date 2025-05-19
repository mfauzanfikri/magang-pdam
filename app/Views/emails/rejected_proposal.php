<p>Halo <?= esc($name) ?>,</p>

<p>Mohon maaf, proposal magang Anda dengan judul <strong><?= esc($proposal) ?></strong> telah <strong>ditolak</strong>.</p>

<?php if ($notes): ?>
    <p><strong>Catatan dari reviewer:</strong><br><?= nl2br(esc($notes)) ?></p>
<?php endif; ?>

<p>Silakan lakukan revisi dan ajukan kembali jika diperlukan.</p>

<p>Terima kasih,<br>Admin Magang PDAM</p>
