<?php
/**
 * Authentication: session, password hashing, login/logout helpers.
 */

declare(strict_types=1);

function user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function user(): ?array
{
    $id = user_id();
    if ($id === null) {
        return null;
    }
    static $cached = null;
    if ($cached !== null && (int) $cached['id'] === $id) {
        return $cached;
    }
    $cached = db_fetch_one(
        'SELECT id, email, first_name, last_name, created_at FROM users WHERE id = ?',
        [$id]
    );
    return $cached;
}

function is_logged_in(): bool
{
    return user_id() !== null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash_set('error', 'Please sign in to continue.');
        redirect('login.php');
    }
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id']    = (int) $user['id'];
    $_SESSION['first_name'] = $user['first_name'];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

function hash_password(string $plain): string
{
    return password_hash($plain, PASSWORD_BCRYPT);
}

function verify_password(string $plain, string $hash): bool
{
    return password_verify($plain, $hash);
}
