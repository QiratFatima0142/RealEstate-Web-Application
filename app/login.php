<?php
require_once __DIR__ . '/includes/config.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$page_title = 'Sign in';
$active     = '';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="auth-card">
        <h1>Welcome back</h1>
        <p class="muted">Sign in to continue to your dashboard.</p>

        <form class="form" method="post" action="actions/login.php" novalidate>
            <?= csrf_field() ?>
            <div class="form__group">
                <label class="form__label" for="email">Email</label>
                <input class="form__input" type="email" id="email" name="email" required autofocus
                       value="<?= e($_GET['email'] ?? '') ?>">
            </div>
            <div class="form__group">
                <label class="form__label" for="password">Password</label>
                <input class="form__input" type="password" id="password" name="password" required minlength="6">
            </div>
            <button type="submit" class="btn btn--primary">Sign In</button>
        </form>

        <div class="auth-card__footer">
            <a href="forgot_password.php">Forgot your password?</a>
            &middot;
            <a href="signup.php">Create an account</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
