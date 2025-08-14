<?php
$title = 'Auction History';
$currentPage = 'auction-history';
ob_start();
?>
<h1>Auction History</h1>
<p>Past auctions will be listed here.</p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
