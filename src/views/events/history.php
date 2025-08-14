<?php
$title = 'Event History';
$currentPage = 'event-history';
ob_start();
?>
<h1>Event History</h1>
<p>Past events will be listed here.</p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
