<?php
/**
 * Shared site header. Variables expected:
 *   $page_title (string)   - browser tab title
 *   $active     (string)   - nav key for highlighting (home|properties|about|contact|dashboard)
 */

$page_title = $page_title ?? APP_NAME;
$active     = $active     ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> - <?= e(APP_NAME) ?></title>
    <meta name="description" content="<?= e(APP_TAGLINE) ?>">
    <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nova+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<header class="navbar">
    <div class="container navbar__inner">
        <a class="brand" href="index.php">
            <img src="assets/images/logo.svg" alt="<?= e(APP_NAME) ?> logo" class="brand__logo">
            <span class="brand__name"><?= e(APP_NAME) ?></span>
        </a>
        <button class="nav__toggle" aria-label="Open menu" aria-expanded="false" data-nav-toggle>&#9776;</button>
        <nav class="nav__links" id="primary-nav">
            <a href="index.php"      class="<?= $active === 'home'       ? 'is-active' : '' ?>">Home</a>
            <a href="properties.php" class="<?= $active === 'properties' ? 'is-active' : '' ?>">Properties</a>
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php"  class="<?= $active === 'dashboard'  ? 'is-active' : '' ?>">Dashboard</a>
            <?php endif; ?>
            <a href="about.php"      class="<?= $active === 'about'      ? 'is-active' : '' ?>">About</a>
            <a href="contact.php"    class="<?= $active === 'contact'    ? 'is-active' : '' ?>">Contact</a>
            <?php if (is_logged_in()): ?>
                <span class="muted" style="color:#9ca3af;font-size:0.9rem;">Hi, <?= e($_SESSION['first_name'] ?? 'there') ?></span>
                <a href="actions/logout.php" class="nav__cta">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn--ghost btn--sm nav__cta">Sign In</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
    <div class="container" style="padding-top:1.25rem;">
        <?php foreach (flash_all() as $flash): ?>
            <div class="flash flash--<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endforeach; ?>
    </div>
