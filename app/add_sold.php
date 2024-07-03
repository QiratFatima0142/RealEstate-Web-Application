<?php
require_once __DIR__ . '/includes/config.php';
require_login();

$uid = (int) user_id();
$owned = db_fetch_all(
    "SELECT p.id, p.name, p.total_amount, p.area_sqm,
            (SELECT COUNT(*) FROM soldproperty sp WHERE sp.purchase_id = p.id) AS is_sold
       FROM purchase p
      WHERE p.user_id = ?
   ORDER BY p.id DESC",
    [$uid]
);

$page_title = 'Sell a property';
$active     = 'dashboard';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-heading section-heading--left">
            <h1>Sell a property</h1>
            <p>Record a sale, the amount received so far, and when the next installment is due.</p>
        </div>

        <?php if (!$owned): ?>
            <div class="empty-state">
                <p>You do not have any properties yet. Add one before recording a sale.</p>
                <a class="btn btn--primary mt-2" href="add_purchase.php">Add a purchase</a>
            </div>
        <?php else: ?>
            <form class="form form--wide" method="post" action="actions/sold_add.php" novalidate>
                <?= csrf_field() ?>

                <div class="form__group">
                    <label class="form__label" for="purchase_id">Select property</label>
                    <select class="form__select" id="purchase_id" name="purchase_id" required>
                        <option value="">Choose one...</option>
                        <?php foreach ($owned as $p): ?>
                            <option value="<?= (int) $p['id'] ?>" <?= $p['is_sold'] ? 'disabled' : '' ?>>
                                <?= e($p['name']) ?>
                                - <?= e(format_money((float) $p['total_amount'])) ?>
                                (<?= (int) $p['area_sqm'] ?> m&#178;)
                                <?= $p['is_sold'] ? ' - already sold' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid--2">
                    <div class="form__group">
                        <label class="form__label" for="sold_date">Date of sale</label>
                        <input class="form__input" type="date" id="sold_date" name="sold_date" required
                               value="<?= e(date('Y-m-d')) ?>" max="<?= e(date('Y-m-d')) ?>">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="next_date">Next installment date</label>
                        <input class="form__input" type="date" id="next_date" name="next_date">
                        <span class="form__help">Leave blank for full up-front sales.</span>
                    </div>
                </div>

                <div class="grid grid--2">
                    <div class="form__group">
                        <label class="form__label" for="total_amount">Sale total (PKR)</label>
                        <input class="form__input" type="number" id="total_amount" name="total_amount" required min="1" step="1">
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="received_amount">Received so far (PKR)</label>
                        <input class="form__input" type="number" id="received_amount" name="received_amount" required min="0" step="1">
                    </div>
                </div>

                <div style="display:flex;gap:0.75rem;">
                    <button class="btn btn--primary" type="submit">Record sale</button>
                    <a href="dashboard.php" class="btn btn--outline">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
