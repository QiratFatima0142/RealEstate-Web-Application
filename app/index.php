<?php
require_once __DIR__ . '/includes/config.php';

$page_title = 'Home';
$active     = 'home';

$featured = [];
try {
    $featured = db_fetch_all(
        "SELECT id, name, area_sqm, total_amount, photo, created_at
           FROM purchase
       ORDER BY id DESC
          LIMIT 6"
    );
} catch (Throwable $e) {
    $featured = [];
}

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero__content">
        <span class="badge">Real Estate Management Portal</span>
        <h1 style="margin-top:1rem;">Find, buy, and manage properties with confidence.</h1>
        <p>EstateEase helps agents and owners track property purchases, manage sales, and follow installment schedules - all in one secure portal.</p>
        <div class="hero__actions">
            <a href="properties.php" class="btn btn--primary">Browse Properties</a>
            <?php if (!is_logged_in()): ?>
                <a href="signup.php" class="btn btn--ghost">Create Account</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn--ghost">Go to Dashboard</a>
            <?php endif; ?>
        </div>

        <div class="stats">
            <div class="stat"><div class="stat__value">500+</div><div class="stat__label">Listings</div></div>
            <div class="stat"><div class="stat__value">120+</div><div class="stat__label">Closed Deals</div></div>
            <div class="stat"><div class="stat__value">12</div><div class="stat__label">Cities Covered</div></div>
            <div class="stat"><div class="stat__value">98%</div><div class="stat__label">Client Satisfaction</div></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <h2>Why EstateEase</h2>
            <p>A focused toolkit that replaces spreadsheets with a real database, secure authentication, and a clean dashboard.</p>
        </div>
        <div class="grid grid--cards">
            <article class="tile">
                <div class="tile__icon">&#9873;</div>
                <div class="tile__title">Track Purchases</div>
                <p class="tile__desc">Record every property you acquire with name, area, price, and photographic proof - all backed by a real MySQL database.</p>
            </article>
            <article class="tile">
                <div class="tile__icon">&#128176;</div>
                <div class="tile__title">Manage Sales</div>
                <p class="tile__desc">Follow the full lifecycle: mark a property as sold, log the received amount, and schedule the next installment date.</p>
            </article>
            <article class="tile">
                <div class="tile__icon">&#128202;</div>
                <div class="tile__title">Dashboard Analytics</div>
                <p class="tile__desc">See the value of your portfolio, open installments, and recent activity at a glance - no more hunting through files.</p>
            </article>
            <article class="tile">
                <div class="tile__icon">&#128274;</div>
                <div class="tile__title">Secure by Default</div>
                <p class="tile__desc">Password hashing with bcrypt, CSRF-protected forms, session regeneration on login, and prepared SQL statements everywhere.</p>
            </article>
        </div>
    </div>
</section>

<?php if (!empty($featured)): ?>
<section class="section section--subtle">
    <div class="container">
        <div class="section-heading">
            <h2>Recently listed</h2>
            <p>The latest properties added to the portal.</p>
        </div>
        <div class="grid grid--cards">
            <?php foreach ($featured as $row): ?>
                <article class="card">
                    <div class="card__media">
                        <?php $photo = $row['photo'] ? UPLOAD_URL . '/' . e($row['photo']) : 'assets/images/placeholder.svg'; ?>
                        <img src="<?= $photo ?>" alt="<?= e($row['name']) ?>" loading="lazy">
                    </div>
                    <div class="card__body">
                        <h3 class="card__title"><?= e($row['name']) ?></h3>
                        <div class="card__meta">
                            <span>&#128205; <?= e((string) $row['area_sqm']) ?> m&#178;</span>
                            <span>&#128197; <?= e(format_date($row['created_at'])) ?></span>
                        </div>
                        <div class="card__price"><?= e(format_money((float) $row['total_amount'])) ?></div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
