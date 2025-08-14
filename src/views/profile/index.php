<?php
$title = 'Profile';
$currentPage = 'profile';
ob_start();
?>
<h1>Profile</h1>
<form method="post" action="/profile">
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label><br>
    <p>Guild: <?= htmlspecialchars($user['guild']) ?></p>
    <p>Role: <?= htmlspecialchars($user['role']) ?></p>
    <label>Display Name: <input type="text" name="display_name" value="<?= htmlspecialchars($user['display_name']) ?>" required></label><br>
    <label>In-game Role:
        <select name="game_role">
            <?php $roles = ['tank'=>'Tank','dps'=>'DPS','ranged dps'=>'Ranged DPS','healer'=>'Healer']; foreach($roles as $value=> $label): ?>
            <option value="<?= $value ?>"<?= $user['game_role']===$value?' selected':'' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Save</button>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
