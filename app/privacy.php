<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Privacy Policy';
$active     = '';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container" style="max-width:820px;">
        <div class="section-heading section-heading--left">
            <h1>Privacy policy</h1>
            <p class="muted">Last updated: 3 July 2024</p>
        </div>

        <p>EstateEase is a coursework project. We collect the minimum information required to let you create an account, record properties, and log sales. Your data lives in a MySQL database on the machine where the application is deployed.</p>

        <h3>What we store</h3>
        <ul>
            <li>Account: first name, last name, email, bcrypt hash of your password.</li>
            <li>Properties you add: name, area, amount, purchase date, optional photo.</li>
            <li>Sales you record: sale date, total amount, received amount, next installment date.</li>
        </ul>

        <h3>What we do not do</h3>
        <ul>
            <li>We never sell or share your data with third parties.</li>
            <li>We never store passwords in plain text.</li>
            <li>We do not use third-party analytics or advertising trackers.</li>
        </ul>

        <h3>Your rights</h3>
        <p>You can request deletion of your account at any time by emailing <a href="mailto:hello@estateease.test">hello@estateease.test</a>. Cascade delete rules ensure all associated records are removed.</p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
