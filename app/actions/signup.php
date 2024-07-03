<?php
require_once __DIR__ . '/../includes/config.php';
require_method('POST');
csrf_verify();

$first = post('first_name');
$last  = post('last_name');
$email = strtolower(post('email'));
$pass  = post('password');

$errors = [];
if ($first === '' || mb_strlen($first) > 60) $errors[] = 'First name is required.';
if ($last  === '' || mb_strlen($last)  > 60) $errors[] = 'Last name is required.';
if (!is_valid_email($email))                  $errors[] = 'A valid email is required.';
if (strlen($pass) < 6)                        $errors[] = 'Password must be at least 6 characters.';

if ($errors) {
    flash_set('error', implode(' ', $errors));
    redirect('../signup.php');
}

$existing = db_fetch_one('SELECT id FROM users WHERE email = ?', [$email]);
if ($existing) {
    flash_set('error', 'An account with that email already exists.');
    redirect('../login.php?email=' . urlencode($email));
}

db_execute(
    'INSERT INTO users (first_name, last_name, email, password_hash, created_at)
          VALUES (?, ?, ?, ?, NOW())',
    [$first, $last, $email, hash_password($pass)]
);

$user = db_fetch_one('SELECT id, email, first_name FROM users WHERE email = ?', [$email]);
if ($user) {
    login_user($user);
    flash_set('success', 'Your account is ready. Welcome, ' . e($first) . '!');
    redirect('../dashboard.php');
}

flash_set('success', 'Account created. Please sign in.');
redirect('../login.php?email=' . urlencode($email));
