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
    <?= \App\Helpers\Csrf::inputField() ?>
    <label>Guild: <input type="text" name="guild" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Display Name: <input type="text" name="display_name" required></label><br>
    <label>Role:
        <select name="role">
            <option value="guild_member">Guild Member</option>
            <option value="guild_advisor">Guild Advisor</option>
            <option value="guild_leader">Guild Leader</option>
            <option value="admin">Admin</option>
        </select>
    </label><br>
    <label>In-game Role:
        <select name="game_role">
            <option value="tank">Tank</option>
            <option value="dps">DPS</option>
            <option value="ranged dps">Ranged DPS</option>
            <option value="healer">Healer</option>
        </select>
    </label><br>
    <button type="submit">Register</button>
</form>
<p><a href="/login">Back to login</a></p>
</body>
</html>
