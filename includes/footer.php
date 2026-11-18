</main>

<footer class="ft">
  <div class="wrap">
    <div class="fgrid">
      <div class="fbrand">
        <img class="lg" src="assets/logo/logo-deskriptor-gelap.svg" alt="AGNIA" width="200" height="52" loading="lazy">
        <p>Konsultan IT yang membawa automation dan AI ke setiap proyek. Mengubah operasi digital menjadi dampak bisnis yang terukur.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="layanan#konsultasi">Konsultasi &amp; Strategi IT</a></li>
          <li><a href="layanan#automation">Business Automation &amp; AI</a></li>
          <li><a href="layanan#aplikasi">Pengembangan Aplikasi</a></li>
          <li><a href="layanan#laporan">Laporan Keuangan &amp; Dashboard</a></li>
        </ul>
      </div>
      <div>
        <h4>Perusahaan</h4>
        <ul>
          <li><a href="tentang">Tentang AGNIA</a></li>
          <li><a href="./#cara-kerja">Cara kerja</a></li>
          <li><a href="solusi#sfi">Sales Force Intelligence</a></li>
          <li><a href="kebijakan-privasi">Kebijakan privasi</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li><a href="<?= e(wa_link()) ?>"<?= wa_attrs() ?>><?= icon('msg', 16) ?>WhatsApp</a></li>
          <?php if (CONTACT_EMAIL !== ''): ?>
          <li><a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= icon('send', 16) ?><?= e(CONTACT_EMAIL) ?></a></li>
          <?php else: ?>
          <li><a href="kontak#form"><?= icon('send', 16) ?>Email</a></li>
          <?php endif; ?>
          <?php if (CONTACT_LINKEDIN !== ''): ?>
          <li><a href="<?= e(CONTACT_LINKEDIN) ?>" target="_blank" rel="noopener"><?= icon('globe', 16) ?>LinkedIn</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="fb">
      <span>© <?= date('Y') ?> AGNIA. <?= e(SITE_TAGLINE) ?>.</span>
      <span><?= e(CONTACT_LOCATION) ?></span>
    </div>
  </div>
</footer>
</body>
</html>
