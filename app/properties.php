<?php
require_once __DIR__ . '/includes/config.php';

$scope     = $_GET['scope'] ?? 'all';
$search    = trim((string) ($_GET['q'] ?? ''));
$minArea   = (int) ($_GET['min_area'] ?? 0);
$sort      = $_GET['sort'] ?? 'recent';

$where   = [];
$params  = [];

if ($scope === 'mine' && is_logged_in()) {
    $where[]          = 'user_id = :uid';
    $params[':uid']   = (int) user_id();
}
if ($search !== '') {
    $where[]          = '(name LIKE :q)';
    $params[':q']     = '%' . $search . '%';
}
if ($minArea > 0) {
    $where[]          = 'area_sqm >= :min_area';
    $params[':min_area'] = $minArea;
}

$sortSql = match ($sort) {
    'price_asc'  => 'total_amount ASC',
    'price_desc' => 'total_amount DESC',
    'area_desc'  => 'area_sqm DESC',
    default       => 'id DESC',
};

$sql = 'SELECT id, name, area_sqm, total_amount, photo, created_at FROM purchase';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY ' . $sortSql . ' LIMIT 60';

try {
    $rows = db_fetch_all($sql, $params);
} catch (Throwable $e) {
    $rows = [];
    flash_set('error', 'Could not load properties. Did you run database/schema.sql?');
}

$page_title = 'Properties';
$active     = 'properties';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-heading section-heading--left">
            <h1>Properties</h1>
            <p><?= $scope === 'mine' ? 'Your personal portfolio.' : 'All listings across the portal.' ?></p>
        </div>

        <form class="filter-bar" method="get" action="properties.php">
            <?php if ($scope === 'mine'): ?>
                <input type="hidden" name="scope" value="mine">
            <?php endif; ?>
            <input class="form__input" type="search" name="q" placeholder="Search by property name..."
                   value="<?= e($search) ?>">
            <input class="form__input" type="number" name="min_area" placeholder="Min area (m&#178;)"
                   value="<?= $minArea > 0 ? (int) $minArea : '' ?>">
            <select class="form__select" name="sort">
                <option value="recent"     <?= $sort === 'recent'     ? 'selected' : '' ?>>Newest first</option>
                <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected' : '' ?>>Price: low to high</option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: high to low</option>
                <option value="area_desc"  <?= $sort === 'area_desc'  ? 'selected' : '' ?>>Largest area</option>
            </select>
            <button class="btn btn--primary" type="submit">Filter</button>
        </form>

        <?php if (!$rows): ?>
            <div class="empty-state">
                <p>No properties matched your filters.</p>
                <?php if (is_logged_in()): ?>
                    <a class="btn btn--primary mt-2" href="add_purchase.php">Add your first property</a>
                <?php else: ?>
                    <a class="btn btn--primary mt-2" href="signup.php">Create an account to add listings</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid--cards">
                <?php foreach ($rows as $row): ?>
                    <article class="card">
                        <div class="card__media">
                            <?php $photo = $row['photo'] ? UPLOAD_URL . '/' . e($row['photo']) : 'assets/images/placeholder.svg'; ?>
                            <img src="<?= $photo ?>" alt="<?= e($row['name']) ?>" loading="lazy">
                        </div>
                        <div class="card__body">
                            <h3 class="card__title"><?= e($row['name']) ?></h3>
                            <div class="card__meta">
                                <span>&#128205; <?= (int) $row['area_sqm'] ?> m&#178;</span>
                                <span>&#128197; <?= e(format_date($row['created_at'])) ?></span>
                            </div>
                            <div class="card__price"><?= e(format_money((float) $row['total_amount'])) ?></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
