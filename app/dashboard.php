<?php
require_once __DIR__ . '/includes/config.php';
require_login();

$uid = (int) user_id();

$summary = db_fetch_one(
    "SELECT
        (SELECT COUNT(*)                FROM purchase       WHERE user_id = :u1) AS total_purchases,
        (SELECT COALESCE(SUM(total_amount), 0) FROM purchase WHERE user_id = :u2) AS portfolio_value,
        (SELECT COUNT(*)                FROM soldproperty sp
           JOIN purchase p ON p.id = sp.purchase_id
          WHERE p.user_id = :u3)        AS total_sold,
        (SELECT COALESCE(SUM(received_amount), 0) FROM soldproperty sp
           JOIN purchase p ON p.id = sp.purchase_id
          WHERE p.user_id = :u4)        AS revenue_received",
    ['u1' => $uid, 'u2' => $uid, 'u3' => $uid, 'u4' => $uid]
) ?? [
    'total_purchases'  => 0,
    'portfolio_value'  => 0,
    'total_sold'       => 0,
    'revenue_received' => 0,
];

$page_title = 'Dashboard';
$active     = 'dashboard';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-heading section-heading--left">
            <h1>Welcome, <?= e($_SESSION['first_name'] ?? 'there') ?> &#128075;</h1>
            <p>Your personal property workspace.</p>
        </div>

        <div class="dashboard-grid mb-2">
            <div class="tile">
                <div class="tile__icon">&#127970;</div>
                <div class="tile__title"><?= (int) $summary['total_purchases'] ?></div>
                <div class="tile__desc">Properties purchased</div>
            </div>
            <div class="tile">
                <div class="tile__icon">&#128181;</div>
                <div class="tile__title"><?= e(format_money((float) $summary['portfolio_value'])) ?></div>
                <div class="tile__desc">Portfolio value</div>
            </div>
            <div class="tile">
                <div class="tile__icon">&#128200;</div>
                <div class="tile__title"><?= (int) $summary['total_sold'] ?></div>
                <div class="tile__desc">Properties sold</div>
            </div>
            <div class="tile">
                <div class="tile__icon">&#128176;</div>
                <div class="tile__title"><?= e(format_money((float) $summary['revenue_received'])) ?></div>
                <div class="tile__desc">Revenue received</div>
            </div>
        </div>

        <div class="section-heading section-heading--left mt-4">
            <h2>Quick actions</h2>
        </div>

        <div class="dashboard-grid">
            <a class="tile" href="add_purchase.php">
                <div class="tile__icon">&#10133;</div>
                <div class="tile__title">Add a purchase</div>
                <div class="tile__desc">Record a property you just bought.</div>
            </a>
            <a class="tile" href="properties.php?scope=mine">
                <div class="tile__icon">&#128221;</div>
                <div class="tile__title">My properties</div>
                <div class="tile__desc">Review everything you own.</div>
            </a>
            <a class="tile" href="add_sold.php">
                <div class="tile__icon">&#129534;</div>
                <div class="tile__title">Sell a property</div>
                <div class="tile__desc">Close a deal and record the amount received.</div>
            </a>
            <a class="tile" href="sold.php">
                <div class="tile__icon">&#128203;</div>
                <div class="tile__title">Sold &amp; installments</div>
                <div class="tile__desc">See what's sold and what's still due.</div>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
