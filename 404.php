<?php
http_response_code(404);
$page_title = 'Halaman tidak ditemukan — AGNIA';
$page_key   = '404';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';
page_hero('Error 404', 'Halaman <em>tidak ditemukan</em>', 'Halaman yang Anda cari mungkin sudah dipindahkan atau alamatnya salah ketik.',
    '<div class="ctas"><a class="btn p" href="./">Kembali ke beranda ' . arrow() . '</a><a class="btn s" href="kontak">Hubungi kami</a></div>');
require __DIR__ . '/includes/footer.php';
