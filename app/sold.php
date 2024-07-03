<?php
require_once __DIR__ . '/includes/config.php';
require_login();

$uid = (int) user_id();
$rows = db_fetch_all(
    "SELECT sp.id, sp.sold_date, sp.total_amount, sp.received_amount, sp.next_date,
            p.id AS purchase_id, p.name AS property_name, p.area_sqm,
            (sp.total_amount - sp.received_amount) AS outstanding
       FROM soldproperty sp
       JOIN purchase p ON p.id = sp.purchase_id
      WHERE p.user_id = ?
   ORDER BY sp.sold_date DESC",
    [$uid]
);

$page_title = 'Sold properties';
$active     = 'dashboard';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-heading section-heading--left">
            <h1>Sold properties</h1>
            <p>Every sale and its payment status.</p>
        </div>

        <?php if (!$rows): ?>
            <div class="empty-state">
                <p>No sales recorded yet. Close your first deal from the dashboard.</p>
                <a class="btn btn--primary mt-2" href="add_sold.php">Record a sale</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Area</th>
                            <th>Sold on</th>
                            <th>Total</th>
                            <th>Received</th>
                            <th>Outstanding</th>
                            <th>Next installment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <?php
                                $outstanding = (float) $r['outstanding'];
                                $status      = $outstanding <= 0 ? 'paid' : ($r['next_date'] ? 'pending' : 'partial');
                                $badgeClass  = [
                                    'paid'    => 'badge badge--success',
                                    'pending' => 'badge badge--warning',
                                    'partial' => 'badge badge--danger',
                                ][$status];
                                $statusLabel = [
                                    'paid'    => 'Paid in full',
                                    'pending' => 'Installment due',
                                    'partial' => 'Outstanding',
                                ][$status];
                            ?>
                            <tr>
                                <td><?= e($r['property_name']) ?></td>
                                <td><?= (int) $r['area_sqm'] ?> m&#178;</td>
                                <td><?= e(format_date($r['sold_date'])) ?></td>
                                <td><?= e(format_money((float) $r['total_amount'])) ?></td>
                                <td><?= e(format_money((float) $r['received_amount'])) ?></td>
                                <td><?= e(format_money($outstanding)) ?></td>
                                <td><?= $r['next_date'] ? e(format_date($r['next_date'])) : '-' ?></td>
                                <td><span class="<?= $badgeClass ?>"><?= e($statusLabel) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
