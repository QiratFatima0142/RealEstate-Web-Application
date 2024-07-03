<?php
/**
 * Application configuration and bootstrap.
 * Loaded at the top of every PHP entry point.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

date_default_timezone_set('Asia/Karachi');

error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_ENV') === 'production' ? '0' : '1');
ini_set('log_errors', '1');

define('APP_NAME', 'EstateEase');
define('APP_TAGLINE', 'The smarter way to manage real estate');
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('UPLOAD_URL', 'uploads');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'realstate');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
