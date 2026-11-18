<?php
$page_title = 'Kebijakan Privasi — AGNIA';
$page_desc  = 'Bagaimana AGNIA mengumpulkan, memakai, dan melindungi data pribadi Anda.';
$page_key   = 'privasi';
$page_path  = 'kebijakan-privasi';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';
?>

<?php page_hero('Legal', 'Kebijakan <em>Privasi</em>', 'Terakhir diperbarui: 18 November 2026. Kebijakan ini menjelaskan bagaimana AGNIA mengumpulkan, memakai, dan melindungi data pribadi Anda.'); ?>

<section class="legal">
  <div class="wrap narrow prose">
    <h2>1. Data yang kami kumpulkan</h2>
    <p>Saat Anda mengisi formulir kontak atau menghubungi kami, kami dapat menerima nama, nama organisasi, alamat email, nomor telepon atau WhatsApp, dan isi pesan yang Anda kirim. Kami juga dapat mencatat data teknis dasar seperti alamat IP dan jenis browser untuk menjaga keamanan situs.</p>

    <h2>2. Tujuan penggunaan</h2>
    <ul>
      <li>Menanggapi pertanyaan dan menjadwalkan Discovery Workshop.</li>
      <li>Menyiapkan penawaran atau proposal yang Anda minta.</li>
      <li>Menjaga keamanan dan meningkatkan kualitas situs.</li>
    </ul>
    <p>Kami tidak menjual data pribadi Anda kepada pihak mana pun.</p>

    <h2>3. Dasar pemrosesan</h2>
    <p>Kami memproses data pribadi berdasarkan persetujuan yang Anda berikan saat mengirim formulir, serta untuk memenuhi permintaan Anda sebelum terjadinya perjanjian, sesuai Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi.</p>

    <h2>4. Penyimpanan dan keamanan</h2>
    <p>Data disimpan selama diperlukan untuk tujuan di atas, atau selama diwajibkan oleh peraturan. Kami menerapkan langkah teknis dan organisasi yang wajar, termasuk pembatasan akses per peran, untuk melindungi data dari akses yang tidak sah.</p>

    <h2>5. Data klien dalam proyek</h2>
    <p>Data yang Anda serahkan dalam proyek tetap milik Anda. Data tersebut hanya dipakai untuk menjalankan proyek sesuai perjanjian kerja, dan tidak dipakai untuk melatih model AI pihak ketiga tanpa persetujuan tertulis.</p>

    <h2>6. Pihak ketiga</h2>
    <p>Situs ini memuat font dari Google Fonts. Jika Anda menghubungi kami lewat WhatsApp atau email, kebijakan privasi penyedia layanan tersebut juga berlaku.</p>

    <h2>7. Hak Anda</h2>
    <p>Anda berhak meminta akses, perbaikan, atau penghapusan data pribadi Anda, serta menarik persetujuan kapan saja. Ajukan permintaan melalui <a href="kontak#form">halaman kontak</a><?= CONTACT_EMAIL !== '' ? ' atau email <a href="mailto:' . e(CONTACT_EMAIL) . '">' . e(CONTACT_EMAIL) . '</a>' : '' ?>.</p>

    <h2>8. Perubahan kebijakan</h2>
    <p>Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu tersedia di halaman ini.</p>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php';
