<?php
$title = 'Register Guild';
$currentPage = 'guild_register';
ob_start();
?>
<h1>Register Guild</h1>
<form method="post" action="/guild/register" id="guildRegisterForm" novalidate>
    <?= \App\Helpers\Csrf::inputField() ?>
    <label for="guild-name">Guild Name:</label>
    <input type="text" id="guild-name" name="name" value="<?= htmlspecialchars($values['name'] ?? '') ?>" required>
    <span class="error" data-error="name" style="color:red"><?= htmlspecialchars($errors['name'] ?? '') ?></span><br>
    <button type="submit">Create Guild</button>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
