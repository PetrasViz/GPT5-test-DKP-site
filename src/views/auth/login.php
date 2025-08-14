<?php
$title = 'Login';
$currentPage = 'login';
ob_start();
?>
<h1>Login</h1>
<?php if (!empty($error)): ?>
<p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="/login">
    <?= \App\Helpers\Csrf::inputField() ?>
    <label>Guild: <input type="text" name="guild" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
<p><a href="/register">Register</a> | <a href="/forgot">Forgot password?</a></p>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
