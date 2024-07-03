<?php
require_once __DIR__ . '/includes/config.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$page_title = 'Create an account';
$active     = '';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="auth-card">
        <h1>Create your account</h1>
        <p class="muted">It only takes a minute to get started.</p>

        <form class="form" method="post" action="actions/signup.php" novalidate>
            <?= csrf_field() ?>
            <div class="grid grid--2">
                <div class="form__group">
                    <label class="form__label" for="first_name">First name</label>
                    <input class="form__input" type="text" id="first_name" name="first_name" required maxlength="60">
                </div>
                <div class="form__group">
                    <label class="form__label" for="last_name">Last name</label>
                    <input class="form__input" type="text" id="last_name" name="last_name" required maxlength="60">
                </div>
            </div>
            <div class="form__group">
                <label class="form__label" for="email">Email</label>
                <input class="form__input" type="email" id="email" name="email" required>
            </div>
            <div class="form__group">
                <label class="form__label" for="password">Password</label>
                <input class="form__input" type="password" id="password" name="password" required minlength="6">
                <span class="form__help">Minimum 6 characters. Stored as a bcrypt hash.</span>
            </div>
            <button type="submit" class="btn btn--primary">Create Account</button>
        </form>

        <div class="auth-card__footer">
            Already have an account? <a href="login.php">Sign in instead</a>.
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
