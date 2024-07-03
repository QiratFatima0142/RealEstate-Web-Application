<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'About';
$active     = 'about';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container" style="max-width:820px;">
        <div class="section-heading section-heading--left">
            <h1>About EstateEase</h1>
            <p>A university Web Application Development project rebuilt to portfolio quality.</p>
        </div>

        <p>EstateEase was created as the capstone for a Web Application Development course in July 2024.
           The goal was to ship a realistic, multi-page web application that uses a real database,
           handles authentication, and supports file uploads - all with a polished, responsive UI.</p>

        <h3>What it does</h3>
        <ul>
            <li>Lets agents and owners track properties they have purchased (name, area, price, date, photo).</li>
            <li>Records sales with partial-payment support (received amount, next installment date).</li>
            <li>Summarises the portfolio value, revenue, and pipeline on a personal dashboard.</li>
            <li>Exposes a public-facing listings page for browsing.</li>
        </ul>

        <h3>How it is built</h3>
        <ul>
            <li><strong>Frontend:</strong> semantic HTML5, responsive CSS with custom properties, vanilla JavaScript.</li>
            <li><strong>Backend:</strong> PHP 8 using PDO, prepared statements, bcrypt password hashing, and CSRF protection.</li>
            <li><strong>Database:</strong> MySQL 8 with foreign keys, indexes, and cascade rules.</li>
            <li><strong>CI/CD:</strong> GitHub Actions validates HTML, CSS, PHP syntax, and MySQL schema on every push.</li>
            <li><strong>Deployment:</strong> the static preview is published to GitHub Pages; the full PHP app runs on XAMPP, Docker, or any PHP-capable host.</li>
        </ul>

        <h3>The team</h3>
        <p>Developed by <strong>Qirat Fatima</strong> as coursework at the University of Central Punjab (UCP), Lahore.</p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
