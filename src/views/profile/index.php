<?php
$title = 'Profile';
$currentPage = 'profile';
ob_start();
?>
<h1>Profile</h1>
<form method="post" action="/profile">
    <label for="profile-email">Email:</label>
    <input type="email" id="profile-email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    <p>Role: <?= htmlspecialchars($user['role']) ?></p>
    <label for="profile-display-name">Display Name:</label>
    <input type="text" id="profile-display-name" name="display_name" value="<?= htmlspecialchars($user['display_name']) ?>" required><br>
    <label for="profile-game-role">In-game Role:</label>
    <select id="profile-game-role" name="game_role">
        <?php $roles = ['TANK'=>'Tank','MELEE_DPS'=>'Melee DPS','RANGED_DPS'=>'Ranged DPS','HEALER'=>'Healer']; foreach($roles as $value => $label): ?>
        <option value="<?= $value ?>"<?= $user['game_role'] === $value ? ' selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Save</button>
</form>

<h2>Guilds</h2>
<?php if ($currentGuild): ?>
    <p>Current Guild: <?= htmlspecialchars($currentGuild['name']) ?></p>
    <form method="post" action="/profile/leave" style="display:inline">
        <button type="submit">Leave Guild</button>
    </form>
<?php else: ?>
    <p>Not currently in a guild.</p>
<?php endif; ?>

<ul>
<?php foreach ($guilds as $g): ?>
    <li>
        <?= htmlspecialchars($g['name']) ?>
        <?php if (!$currentGuild || $currentGuild['id'] !== $g['id']): ?>
            <form method="post" action="/profile/join" style="display:inline">
                <input type="hidden" name="guild_id" value="<?= $g['id'] ?>">
                <button type="submit">Join</button>
            </form>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
