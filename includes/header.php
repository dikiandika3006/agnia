<?php
/** @var string $page_title  @var string $page_desc  @var string $page_key */
require_once __DIR__ . '/config.php';
$page_title = $page_title ?? 'AGNIA — IT Consulting & AI Automation';
$page_desc  = $page_desc ?? 'AGNIA adalah konsultan IT yang membangun aplikasi, automation berbasis AI, dan sistem laporan keuangan di atas sistem yang sudah Anda punya.';
$page_key   = $page_key ?? '';
$page_path  = $page_path ?? '';
$base_href  = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/') . '/';
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href="<?= e($base_href) ?>">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($page_desc) ?>">
<meta name="theme-color" content="#0B1A30">
<?php if (SITE_URL !== ''): ?>
<link rel="canonical" href="<?= e(rtrim(SITE_URL, '/') . '/' . $page_path) ?>">
<meta property="og:url" content="<?= e(rtrim(SITE_URL, '/') . '/' . $page_path) ?>">
<?php endif; ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="AGNIA">
<meta property="og:locale" content="id_ID">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($page_desc) ?>">
<meta property="og:image" content="<?= e((SITE_URL !== '' ? rtrim(SITE_URL, '/') . '/' : '') . 'assets/favicon/og-image.png') ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="assets/favicon/favicon.ico" sizes="any">
<link rel="icon" href="assets/favicon/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="assets/favicon/apple-touch-icon.png">
<link rel="manifest" href="assets/favicon/site.webmanifest">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="css/agnia-tokens.css">
<link rel="stylesheet" href="css/style.css?v=1">
<script src="js/main.js?v=1" defer></script>
<?php if (GA_ID !== ''): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_ID ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= GA_ID ?>');</script>
<?php endif; ?>
</head>
<body class="page-<?= e($page_key ?: 'home') ?>">
<a class="skip" href="<?= e($page_path) ?>#main">Lewati ke konten</a>

<header class="site-header" id="top">
  <div class="wrap nav">
    <a class="brand" href="./" aria-label="AGNIA — beranda">
      <img src="assets/logo/logo-horizontal-warna.svg" alt="AGNIA" width="150" height="40">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Buka menu">
      <span></span><span></span><span></span>
    </button>
    <nav class="links" id="site-nav" aria-label="Navigasi utama">
      <?php foreach (NAV as [$url, $label, $key]): ?>
        <a href="<?= e($url) ?>"<?= $key === $page_key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <a class="btn p" href="kontak#form">Jadwalkan Workshop</a>
    </nav>
  </div>
</header>

<main id="main">
