<?php
$title = 'Management';
$currentPage = 'management';
ob_start();
?>
<h1>Management</h1>
<p>Placeholder for auctions, events, and user controls.</p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
