<?php
$title = 'Register';
$currentPage = 'register';
ob_start();
?>
<h1>Register</h1>
<form method="post" action="/register" id="registerForm" novalidate>
    <?= \App\Helpers\Csrf::inputField() ?>
    <label for="register-email">Email:</label>
    <input type="email" id="register-email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" required>
    <span class="error" data-error="email" style="color:red"><?= htmlspecialchars($errors['email'] ?? '') ?></span><br>

    <label for="register-password">Password:</label>
    <input type="password" id="register-password" name="password" required>
    <span class="error" data-error="password" style="color:red"><?= htmlspecialchars($errors['password'] ?? '') ?></span><br>

    <label for="register-display-name">Display Name:</label>
    <input type="text" id="register-display-name" name="display_name" value="<?= htmlspecialchars($values['display_name'] ?? '') ?>" required>
    <span class="error" data-error="display_name" style="color:red"><?= htmlspecialchars($errors['display_name'] ?? '') ?></span><br>

    <label for="register-game-role">In-game Role:</label>
    <select id="register-game-role" name="game_role">
        <option value="tank" <?= ($values['game_role'] ?? '') === 'tank' ? 'selected' : '' ?>>Tank</option>
        <option value="dps" <?= ($values['game_role'] ?? '') === 'dps' ? 'selected' : '' ?>>DPS</option>
        <option value="ranged dps" <?= ($values['game_role'] ?? '') === 'ranged dps' ? 'selected' : '' ?>>Ranged DPS</option>
        <option value="healer" <?= ($values['game_role'] ?? '') === 'healer' ? 'selected' : '' ?>>Healer</option>
    </select>
    <span class="error" data-error="game_role" style="color:red"><?= htmlspecialchars($errors['game_role'] ?? '') ?></span><br>

    <button type="submit">Register</button>
</form>
<p><a href="/login">Back to login</a></p>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    const fields = ['email', 'password', 'display_name', 'game_role'];

    const validateField = (field) => {
        const input = form[field];
        const errorElem = form.querySelector('[data-error="' + field + '"]');
        if (!input.value.trim()) {
            errorElem.textContent = 'This field is required';
            return false;
        }
        errorElem.textContent = '';
        return true;
    };

    fields.forEach(field => {
        form[field].addEventListener('input', () => validateField(field));
    });

    form.addEventListener('submit', function (e) {
        let valid = true;
        fields.forEach(field => {
            if (!validateField(field)) {
                valid = false;
            }
        });
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
