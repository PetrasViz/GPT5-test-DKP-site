<?php
$title = 'Forgot Password';
$currentPage = 'forgot';
ob_start();
?>
<h1>Forgot Password</h1>
<?php if (!empty($message)): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="post" action="/forgot">
    <?= \App\Helpers\Csrf::inputField() ?>
    <label for="forgot-email">Email:</label>
    <input type="email" id="forgot-email" name="email" required><br>
    <button type="submit">Send reset link</button>
</form>
<p><a href="/login">Back to login</a></p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
