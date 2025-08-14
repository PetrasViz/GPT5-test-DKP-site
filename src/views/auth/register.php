<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<?php if (!empty($error)): ?>
<p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="/register">
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Register</button>
</form>
<p><a href="/login">Back to login</a></p>
</body>
</html>
