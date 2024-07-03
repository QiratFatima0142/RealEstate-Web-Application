<?php
require_once __DIR__ . '/../includes/config.php';
require_login();
require_method('POST');
csrf_verify();

$name         = post('name');
$purchaseDate = post('purchase_date');
$total        = (float) post('total_amount');
$area         = (int)   post('area_sqm');

$errors = [];
if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Property name is required.';
if (!strtotime($purchaseDate))               $errors[] = 'Please provide a valid purchase date.';
if ($total <= 0)                             $errors[] = 'Amount must be greater than zero.';
if ($area  <= 0)                             $errors[] = 'Area must be greater than zero.';

$photoName = null;
if (!empty($_FILES['photo']['name'])) {
    $photo = $_FILES['photo'];
    if ($photo['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Photo upload failed.';
    } elseif ($photo['size'] > 4 * 1024 * 1024) {
        $errors[] = 'Photo must be under 4 MB.';
    } else {
        $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed, true)) {
            $errors[] = 'Photo must be JPG, PNG, or WebP.';
        } else {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0775, true);
            }
            $photoName = sprintf('%s-%s.%s', slugify($name), bin2hex(random_bytes(4)), $ext);
            if (!move_uploaded_file($photo['tmp_name'], UPLOAD_DIR . '/' . $photoName)) {
                $errors[] = 'Could not save uploaded photo.';
                $photoName = null;
            }
        }
    }
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    redirect('../add_purchase.php');
}

db_execute(
    'INSERT INTO purchase (user_id, name, total_amount, area_sqm, purchase_date, photo, created_at)
          VALUES (?, ?, ?, ?, ?, ?, NOW())',
    [user_id(), $name, $total, $area, $purchaseDate, $photoName]
);

flash_set('success', 'Property added to your portfolio.');
redirect('../properties.php?scope=mine');
