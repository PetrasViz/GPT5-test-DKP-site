<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
<h1>Forgot Password</h1>
<?php if (!empty($message)): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="post" action="/forgot">
    <?= \App\Helpers\Csrf::inputField() ?>
    <label>Email: <input type="email" name="email" required></label><br>
    <button type="submit">Send reset link</button>
</form>
<p><a href="/login">Back to login</a></p>
</body>
</html>
