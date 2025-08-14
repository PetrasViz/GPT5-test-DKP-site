<?php
$title = 'Auctions';
$currentPage = 'auctions';
ob_start();
?>
<h1>Auctions</h1>
<p>List of active auctions will appear here.</p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
