<?php
/**
 * Shared helpers: escaping, validation, CSRF, flash messages.
 */

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function post(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_all(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        die('CSRF token mismatch. Please refresh and try again.');
    }
}

function require_method(string $method): void
{
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        http_response_code(405);
        die('Method not allowed.');
    }
}

function is_valid_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function format_money(int|float $amount, string $currency = 'PKR'): string
{
    return $currency . ' ' . number_format((float) $amount, 0);
}

function format_date(string $dateString): string
{
    $time = strtotime($dateString);
    return $time ? date('d M Y', $time) : $dateString;
}

function slugify(string $value): string
{
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    return strtolower(trim((string) $value, '-'));
}
