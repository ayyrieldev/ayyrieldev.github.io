<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$profile = $profile ?? get_profile();
$pageTitle = $pageTitle ?? 'Portofolio';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle); ?> - <?= h($profile['nama']); ?></title>
    <meta name="description" content="<?= h($profile['deskripsi']); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= h(asset_url('css/style.css')); ?>">
</head>
<body>
