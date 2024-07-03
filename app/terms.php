<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Terms of Use';
$active     = '';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container" style="max-width:820px;">
        <div class="section-heading section-heading--left">
            <h1>Terms of use</h1>
            <p class="muted">Last updated: 3 July 2024</p>
        </div>

        <p>By using EstateEase you agree to the following terms. This is a university coursework project and is provided on an "as is" basis without warranty of any kind.</p>

        <h3>Acceptable use</h3>
        <ul>
            <li>Only record properties and sales that you are authorised to manage.</li>
            <li>Do not upload images containing personal data of third parties without consent.</li>
            <li>Do not attempt to bypass authentication, brute-force accounts, or attack the database.</li>
        </ul>

        <h3>Account responsibility</h3>
        <p>You are responsible for keeping your password secure. Notify the administrator immediately if you suspect your account has been compromised.</p>

        <h3>Liability</h3>
        <p>As a student project, EstateEase is not liable for any financial loss resulting from mistakes in entered data. Always verify amounts and dates against original documents.</p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
