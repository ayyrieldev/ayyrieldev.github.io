<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

ensure_session();

if (!empty($_SESSION['admin_id'])) {
    redirect('admin/dashboard.php');
}

redirect('admin/login.php');
