<?php
require_once __DIR__ . '/includes/config.php';
require_login();

$page_title = 'Add a purchase';
$active     = 'dashboard';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-heading section-heading--left">
            <h1>Add a purchase</h1>
            <p>Record a newly acquired property. Photos are stored in <code>app/uploads/</code>.</p>
        </div>

        <form class="form form--wide" method="post" action="actions/purchase_add.php" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <div class="grid grid--2">
                <div class="form__group">
                    <label class="form__label" for="name">Property name</label>
                    <input class="form__input" type="text" id="name" name="name" required maxlength="120"
                           placeholder="e.g. DHA Phase 6 - Plot 42">
                </div>
                <div class="form__group">
                    <label class="form__label" for="purchase_date">Purchase date</label>
                    <input class="form__input" type="date" id="purchase_date" name="purchase_date" required
                           max="<?= e(date('Y-m-d')) ?>">
                </div>
            </div>

            <div class="grid grid--2">
                <div class="form__group">
                    <label class="form__label" for="total_amount">Total amount (PKR)</label>
                    <input class="form__input" type="number" id="total_amount" name="total_amount" required min="1" step="1"
                           placeholder="e.g. 8500000">
                </div>
                <div class="form__group">
                    <label class="form__label" for="area_sqm">Area (m&#178;)</label>
                    <input class="form__input" type="number" id="area_sqm" name="area_sqm" required min="1" step="1"
                           placeholder="e.g. 400">
                </div>
            </div>

            <div class="form__group">
                <label class="form__label" for="photo">Photo</label>
                <input class="form__input" type="file" id="photo" name="photo" accept="image/png,image/jpeg,image/webp">
                <span class="form__help">Optional. PNG, JPEG, or WebP up to 4 MB.</span>
            </div>

            <div style="display:flex;gap:0.75rem;">
                <button class="btn btn--primary" type="submit">Save purchase</button>
                <a href="dashboard.php" class="btn btn--outline">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
