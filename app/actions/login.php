<?php
require_once __DIR__ . '/../includes/config.php';
require_method('POST');
csrf_verify();

$email    = strtolower(post('email'));
$password = post('password');

if ($email === '' || $password === '') {
    flash_set('error', 'Email and password are required.');
    redirect('../login.php');
}

if (!is_valid_email($email)) {
    flash_set('error', 'Please provide a valid email address.');
    redirect('../login.php?email=' . urlencode($email));
}

$user = db_fetch_one(
    'SELECT id, email, password_hash, first_name FROM users WHERE email = ?',
    [$email]
);

if (!$user || !verify_password($password, (string) $user['password_hash'])) {
    flash_set('error', 'Invalid credentials. Please try again.');
    redirect('../login.php?email=' . urlencode($email));
}

login_user($user);
flash_set('success', 'Welcome back, ' . e($user['first_name']) . '!');
redirect('../dashboard.php');
