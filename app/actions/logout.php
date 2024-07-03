<?php
require_once __DIR__ . '/../includes/config.php';
logout_user();
session_start();
flash_set('info', 'You have been signed out.');
redirect('../index.php');
