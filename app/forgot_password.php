<?php
require_once __DIR__ . '/includes/config.php';

$page_title = 'Forgot password';
$active     = '';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="auth-card">
        <h1>Forgot your password?</h1>
        <p class="muted">Enter the email address linked to your account. We'll send you a reset link.</p>

        <form class="form" method="post" action="#" novalidate>
            <?= csrf_field() ?>
            <div class="form__group">
                <label class="form__label" for="email">Email</label>
                <input class="form__input" type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn--primary">Send reset link</button>
            <p class="form__help">Demo build: the reset email pipeline is not wired up in this project. In production you would send a signed token over email.</p>
        </form>

        <div class="auth-card__footer">
            <a href="login.php">Back to sign in</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
