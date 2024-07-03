<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Contact';
$active     = 'contact';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container" style="max-width:820px;">
        <div class="section-heading section-heading--left">
            <h1>Contact us</h1>
            <p>Questions, feedback, or partnership ideas? Drop us a line.</p>
        </div>

        <div class="grid grid--2" style="align-items:start;">
            <form class="form" method="post" action="actions/contact.php" novalidate>
                <?= csrf_field() ?>
                <div class="form__group">
                    <label class="form__label" for="name">Your name</label>
                    <input class="form__input" type="text" id="name" name="name" required maxlength="100">
                </div>
                <div class="form__group">
                    <label class="form__label" for="email">Email</label>
                    <input class="form__input" type="email" id="email" name="email" required>
                </div>
                <div class="form__group">
                    <label class="form__label" for="message">Message</label>
                    <textarea class="form__textarea" id="message" name="message" required minlength="10" maxlength="2000"></textarea>
                </div>
                <button class="btn btn--primary" type="submit">Send message</button>
            </form>

            <aside style="background:var(--color-bg-subtle);padding:1.5rem;border-radius:var(--radius-lg);border:1px solid var(--color-border);">
                <h3>Reach us directly</h3>
                <p class="muted">We respond within one working day.</p>
                <p><strong>Email:</strong> hello@estateease.test</p>
                <p><strong>Phone:</strong> +92 300 1234567</p>
                <p><strong>Office:</strong> University of Central Punjab, Lahore, Pakistan</p>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
