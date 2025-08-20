<?php
$title = 'Error';
ob_start();
?>
<div class="alert alert-danger" role="alert">
    <?= htmlspecialchars($message ?? 'An error occurred.') ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

