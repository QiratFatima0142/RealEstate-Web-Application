<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
require_method('POST');
csrf_verify();

$purchaseId = (int)   post('purchase_id');
$soldDate   = post('sold_date');
$nextDate   = post('next_date') ?: null;
$total      = (float) post('total_amount');
$received   = (float) post('received_amount');

$errors = [];
if ($purchaseId <= 0)           $errors[] = 'Please choose a property to sell.';
if (!strtotime($soldDate))      $errors[] = 'Please provide a valid sale date.';
if ($nextDate && !strtotime($nextDate)) $errors[] = 'Next installment date is invalid.';
if ($total <= 0)                $errors[] = 'Sale total must be greater than zero.';
if ($received < 0)              $errors[] = 'Received amount cannot be negative.';
if ($received > $total)         $errors[] = 'Received amount cannot exceed the total sale.';

if (!$errors) {
    $owned = db_fetch_one(
        'SELECT id FROM purchase WHERE id = ? AND user_id = ?',
        [$purchaseId, user_id()]
    );
    if (!$owned) $errors[] = 'That property does not belong to you.';

    $alreadySold = db_fetch_one(
        'SELECT id FROM soldproperty WHERE purchase_id = ?',
        [$purchaseId]
    );
    if ($alreadySold) $errors[] = 'This property is already marked as sold.';
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    redirect('../add_sold.php');
}

db_execute(
    'INSERT INTO soldproperty (purchase_id, sold_date, total_amount, received_amount, next_date, created_at)
          VALUES (?, ?, ?, ?, ?, NOW())',
    [$purchaseId, $soldDate, $total, $received, $nextDate]
);

flash_set('success', 'Sale recorded successfully.');
redirect('../sold.php');
