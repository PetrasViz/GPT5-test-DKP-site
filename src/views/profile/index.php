<?php
$title = 'Profile';
$currentPage = 'profile';
ob_start();
?>
<h1>Profile</h1>
<form method="post" action="/profile">
    <label for="profile-email">Email:</label>
    <input type="email" id="profile-email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    <p>Guild: <?= htmlspecialchars($user['guild']) ?></p>
    <p>Role: <?= htmlspecialchars($user['role']) ?></p>
    <label for="profile-display-name">Display Name:</label>
    <input type="text" id="profile-display-name" name="display_name" value="<?= htmlspecialchars($user['display_name']) ?>" required><br>
    <label for="profile-game-role">In-game Role:</label>
    <select id="profile-game-role" name="game_role">
        <?php $roles = ['tank'=>'Tank','dps'=>'DPS','ranged dps'=>'Ranged DPS','healer'=>'Healer']; foreach($roles as $value => $label): ?>
        <option value="<?= $value ?>"<?= $user['game_role']===$value?' selected':'' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Save</button>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
