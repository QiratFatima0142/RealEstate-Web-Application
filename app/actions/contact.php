<?php
require_once __DIR__ . '/../includes/config.php';
require_method('POST');
csrf_verify();

$name    = post('name');
$email   = strtolower(post('email'));
$message = post('message');

$errors = [];
if ($name === '' || mb_strlen($name) > 100) $errors[] = 'Your name is required.';
if (!is_valid_email($email))                $errors[] = 'A valid email is required.';
if (mb_strlen($message) < 10)               $errors[] = 'Message must be at least 10 characters.';
if (mb_strlen($message) > 2000)             $errors[] = 'Message must be under 2000 characters.';

if ($errors) {
    flash_set('error', implode(' ', $errors));
    redirect('../contact.php');
}

try {
    db_execute(
        'INSERT INTO contact_message (name, email, message, created_at)
              VALUES (?, ?, ?, NOW())',
        [$name, $email, $message]
    );
} catch (Throwable $e) {
    error_log('contact insert failed: ' . $e->getMessage());
}

flash_set('success', 'Thanks! Your message has been received.');
redirect('../contact.php');
