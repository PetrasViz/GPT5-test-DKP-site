<?php
$title = 'Login';
$currentPage = 'login';
ob_start();
?>
<h1>Login</h1>
<?php if (!empty($errors['general'])): ?>
<p style="color:red"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>
<form method="post" action="/login" id="loginForm" novalidate>
    <?= \App\Helpers\Csrf::inputField() ?>
    <label for="login-guild">Guild:</label>
    <input type="text" id="login-guild" name="guild" value="<?= htmlspecialchars($values['guild'] ?? '') ?>" required>
    <span class="error" data-error="guild" style="color:red"><?= htmlspecialchars($errors['guild'] ?? '') ?></span><br>

    <label for="login-email">Email:</label>
    <input type="email" id="login-email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" required>
    <span class="error" data-error="email" style="color:red"><?= htmlspecialchars($errors['email'] ?? '') ?></span><br>

    <label for="login-password">Password:</label>
    <input type="password" id="login-password" name="password" required>
    <span class="error" data-error="password" style="color:red"><?= htmlspecialchars($errors['password'] ?? '') ?></span><br>

    <button type="submit">Login</button>
</form>
<p><a href="/register">Register</a> | <a href="/forgot">Forgot password?</a></p>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const fields = ['guild', 'email', 'password'];

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
