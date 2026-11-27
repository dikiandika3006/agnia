<?php
/* Section yang dipakai ulang di beberapa halaman.
   Panggil fungsi section_*() di dalam halaman. */

require_once __DIR__ . '/config.php';

const SERVICES = [
    ['konsultasi', 'compass', 'Konsultasi &amp; Strategi IT',
        'Memetakan proses, data, dan KPI untuk menemukan use case dengan nilai tertinggi.',
        ['Discovery workshop', 'Roadmap IT &amp; AI', 'Baseline &amp; KPI']],
    ['automation', 'flow', 'Business Automation &amp; AI',
        'Alur kerja otomatis dengan AI yang menganalisis, merekomendasikan, dan mengirim insight.',
        ['AI reasoning', 'Notifikasi WhatsApp', 'Laporan otomatis']],
    ['aplikasi', 'code', 'Pengembangan Aplikasi &amp; Integrasi',
        'Aplikasi web, mobile, dan sistem internal yang dibangun sesuai cara kerja tim Anda.',
        ['Web &amp; mobile app', 'Integrasi CRM &amp; database', 'API &amp; service layer']],
    ['laporan', 'chart', 'Laporan Keuangan &amp; Dashboard',
        'Laporan keuangan dan operasional otomatis, cockpit per peran, dengan data yang terjaga.',
        ['Laporan keuangan otomatis', 'Analisis biaya &amp; efisiensi', 'Executive cockpit']],
];

const APP_TYPES = [
    ['monitor', 'Aplikasi web &amp; dashboard', 'Portal kerja, dashboard, dan sistem berbasis browser.'],
    ['phone', 'Aplikasi mobile', 'Android dan iOS untuk tim lapangan atau pelanggan.'],
    ['store', 'Sistem operasional', 'Penjualan, stok, keuangan, dan laporan dalam satu sistem.'],
    ['plug', 'Integrasi &amp; API', 'Menghubungkan aplikasi yang sudah Anda pakai.'],
    ['msg', 'Chatbot &amp; WhatsApp', 'Notifikasi, pengingat, dan layanan otomatis.'],
    ['portal', 'Portal pelanggan &amp; mitra', 'Akses mandiri untuk pelanggan, donatur, atau mitra.'],
];

function eyebrow_title(string $eyebrow, string $title, string $tag = 'h2'): string
{
    return '<div class="eyebrow">' . $eyebrow . '</div><' . $tag . ' class="title">' . $title . '</' . $tag . '>';
}

function page_hero(string $eyebrow, string $title, string $lead, string $extra = ''): void
{ ?>
<section class="page-hero">
  <div class="wrap">
    <div class="eyebrow"><?= $eyebrow ?></div>
    <div class="rule"></div>
    <h1><?= $title ?></h1>
    <p class="lead"><?= $lead ?></p>
    <?= $extra ?>
  </div>
  <img class="page-hero-art" src="assets/images/simbol-cta.svg" alt="" aria-hidden="true">
</section>
<?php }

function section_services(bool $detailed = false): void
{ ?>
<section class="svc" id="layanan-ringkas">
  <div class="wrap">
    <div class="hd2">
      <div><?= eyebrow_title('Layanan', 'Satu mitra, dari strategi<br class="d">sampai <em>sistem berjalan</em>') ?></div>
      <p class="lead">Satu tim untuk strategi, automation, dan pengembangan aplikasi. Kami membantu Anda memilih proses yang tepat, membangunnya, lalu memastikan dampaknya terukur.</p>
    </div>
    <div class="sgrid4">
      <?php foreach (SERVICES as [$id, $ic, $title, $desc, $items]): ?>
      <a class="card svc-card" href="layanan#<?= $id ?>">
        <div class="itile"><?= icon($ic, 24) ?></div>
        <h3><?= $title ?></h3>
        <p><?= $desc ?></p>
        <ul>
          <?php foreach ($items as $it): ?><li><?= check() ?><?= $it ?></li><?php endforeach; ?>
        </ul>
        <span class="more">Selengkapnya <?= arrow(16) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php }

function section_apps(): void
{ ?>
<section class="apps" id="aplikasi">
  <div class="wrap">
    <div class="top">
      <div class="l">
        <?= eyebrow_title('Pengembangan aplikasi', 'Bukan hanya saran.<br>Kami juga <em>membangun aplikasinya</em>.') ?>
        <p class="lead">Tim AGNIA merancang, membangun, dan merawat aplikasi web, mobile, dan sistem internal. Setiap aplikasi dibuat sesuai proses bisnis Anda, dan siap dihubungkan dengan automation dan AI kapan pun dibutuhkan.</p>
        <ol class="proc" aria-label="Tahapan pengembangan">
          <?php foreach (['Rancang', 'Bangun', 'Uji', 'Rawat'] as $i => $s): ?>
            <?php if ($i): ?><li class="sep" aria-hidden="true"><?= arrow(16) ?></li><?php endif; ?>
            <li><span class="num"><?= $i + 1 ?></span><?= $s ?></li>
          <?php endforeach; ?>
        </ol>
        <div class="klabel">Kemampuan teknis</div>
        <div class="chips">
          <?php foreach (['Web app', 'Android &amp; iOS', 'API &amp; integrasi', 'Database', 'Cloud', 'WhatsApp Business API', 'AI &amp; LLM', 'Dashboard'] as $c): ?>
          <span><?= $c ?></span>
          <?php endforeach; ?>
        </div>
        <div class="mt-36"><a class="btn p" href="kontak?topik=aplikasi#form">Diskusikan aplikasi Anda <?= arrow() ?></a></div>
      </div>
      <div class="types">
        <?php foreach (APP_TYPES as [$ic, $t, $d]): ?>
        <div class="card type">
          <div class="ti"><?= icon($ic, 24) ?></div>
          <div><b><?= $t ?></b><span><?= $d ?></span></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php section_showcase(); ?>
  </div>
</section>
<?php }

function section_showcase(string $id = ''): void
{
    $shot = showcase_image();
    ?>
<div class="show"<?= $id ? ' id="' . $id . '"' : '' ?>>
  <div class="show-txt">
    <span class="tag">KARYA KAMI</span>
    <h3>Aplikasi yang <em>sudah kami bangun</em></h3>
    <p>Dibangun dan dirawat oleh tim AGNIA, dipakai langsung oleh klien di berbagai bidang.</p>
    <ol class="works">
      <?php foreach (['Sistem Manajemen Toko', 'Sistem Koperasi KSM', 'Klinik Dental Care', 'Program Keuangan Android &amp; Web', 'Program Administrasi Instansi'] as $i => $w): ?>
      <li><span class="num"><?= $i + 1 ?></span><?= $w ?></li>
      <?php endforeach; ?>
    </ol>
    <a class="btn w" href="kontak?topik=aplikasi#form">Diskusikan aplikasi Anda <?= arrow() ?></a>
  </div>
  <?php if ($shot): ?>
  <img class="slot-img" src="<?= e($shot) ?>" alt="Tampilan aplikasi buatan AGNIA" width="1040" height="660" loading="lazy">
  <?php else: ?>
  <div class="slot" aria-hidden="true">
    <div class="mock">
      <div class="mock-bar"><i></i><i></i><i></i></div>
      <div class="mock-body">
        <div class="mock-side"><span></span><span></span><span></span><span></span></div>
        <div class="mock-main">
          <div class="mock-kpis"><span></span><span></span><span></span></div>
          <div class="mock-chart"><?php foreach ([40, 65, 50, 80, 60, 90, 72] as $h): ?><i style="height:<?= $h ?>%"></i><?php endforeach; ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php }

function section_sfi(): void
{
    $flow = [
        ['users', 'Tim sales', 'Mengisi aktivitas dan pipeline seperti biasa', false],
        ['db', 'AI Extractor', 'Menyatukan data dari spreadsheet, CRM, dan database', false],
        ['brain', 'AI Thinker', 'Mencari risiko, peluang, dan Next Best Action', true],
        ['send', 'AI Messenger', 'Mengirim ringkasan harian ke setiap peran', false],
        ['gauge', 'Business Cockpit', 'Dashboard AE, cabang, dan eksekutif', false],
    ];
    ?>
<section class="sfi" id="sfi">
  <div class="wrap">
    <div class="cols">
      <div class="l">
        <?= eyebrow_title('Solusi unggulan', 'Sales Force <em>Intelligence</em>') ?>
        <p class="lead">Tim sales menerima target, risiko, dan tindakan prioritas setiap pagi lewat WhatsApp. Pimpinan memantau semuanya dari satu cockpit.</p>
        <div class="flow">
          <?php foreach ($flow as [$ic, $b, $s, $hi]): ?>
          <div class="fs<?= $hi ? ' hi' : '' ?>">
            <div class="n"><?= icon($ic, 22) ?></div>
            <div><b><?= $b ?></b><span><?= $s ?></span></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="mt-36"><a class="btn w" href="solusi#sfi">Pelajari Sales Force Intelligence <?= arrow() ?></a></div>
      </div>
      <div class="phone-wrap">
        <div class="phone">
          <div class="scr">
            <div class="pbar">
              <div class="av"><img src="assets/logo/ikon-warna.svg" alt="" width="24" height="20"></div>
              <div><b>AGNIA Intelligence</b><span>online</span></div>
            </div>
            <div class="chat">
              <div class="day">Hari ini</div>
              <div class="msg">
                <div class="t">Selamat pagi, Budi. Ringkasan hari ini:</div>
                <div class="k"><span>Target bulan ini</span><b>62%</b></div>
                <div class="k"><span>Run-rate</span><b>85%</b></div>
                <div class="k"><span>Akun aktif</span><b>18</b></div>
                <div class="k"><span>Follow-up jatuh tempo</span><b>4</b></div>
                <div class="sec">Prioritas hari ini</div>
                <ol><li>Hubungi PT Contoh Sejahtera, 09.00</li><li>Kirim penawaran CV Maju Bersama</li><li>Eskalasi kendala integrasi ke tim teknis</li></ol>
                <div class="sec">Peringatan</div>
                <div class="muted">2 akun belum dihubungi lebih dari 30 hari.</div>
                <div class="tm">07.30</div>
              </div>
              <div class="reply">Siap, mulai dari PT Contoh Sejahtera.<div class="tm">07.42</div></div>
            </div>
          </div>
        </div>
        <div class="ill2">Contoh ilustrasi, nama dan angka fiktif</div>
      </div>
    </div>
  </div>
</section>
<?php }

function section_finance(): void
{
    $caps = [
        ['refresh', 'Otomasi pencatatan &amp; rekonsiliasi', 'Transaksi bank, kas, dan penjualan tercatat dan dicocokkan otomatis.'],
        ['file', 'Laporan keuangan otomatis', 'Laba rugi, neraca, dan arus kas tersedia tanpa rekap manual.'],
        ['target', 'Anggaran vs realisasi', 'Pantau pemakaian anggaran per unit, cabang, atau program.'],
        ['search', 'Analisis biaya &amp; efisiensi', 'AI menyorot pengeluaran yang tidak wajar dan peluang hemat.'],
        ['bell', 'Peringatan dini', 'Piutang jatuh tempo, kas menipis, dan anggaran hampir habis.'],
    ];
    // [pos, realisasi % dari anggaran]
    $bars = [['Gaji', 96], ['Operasional', 118], ['Transportasi', 141], ['Pemasaran', 82], ['IT', 74]];
    ?>
<section class="fin" id="keuangan">
  <div class="wrap">
    <div class="row">
      <div class="l">
        <?= eyebrow_title('Solusi · AI untuk keuangan', 'Laporan keuangan yang <em>rapi, cepat, dan bisa dipercaya</em>') ?>
        <p class="lead">Kami membangun sistem yang mengotomasi pencatatan, rekonsiliasi, dan laporan keuangan, lalu memakai AI untuk menemukan pemborosan dan peluang efisiensi.</p>
        <div class="diff"><div class="c"><?= icon('calc', 20) ?></div>Dibangun oleh tim yang paham akuntansi dan keuangan, bukan hanya teknologi.</div>
        <div class="caps">
          <?php foreach ($caps as [$ic, $b, $s]): ?>
          <div class="cap"><div class="ti"><?= icon($ic, 20) ?></div><div><b><?= $b ?></b><span><?= $s ?></span></div></div>
          <?php endforeach; ?>
        </div>
        <div class="mt-32"><a class="btn p" href="kontak?topik=keuangan#form">Konsultasikan sistem keuangan Anda <?= arrow() ?></a></div>
      </div>
      <div class="r">
        <div class="dash">
          <div class="dh"><div><b>Ringkasan Keuangan</b><span>Oktober 2026 · Semua cabang</span></div><span class="per">Bulan ini</span></div>
          <div class="kp">
            <div><span>Pendapatan</span><b>Rp 1,24 M</b><i class="up">▲ 8% vs bulan lalu</i></div>
            <div><span>Beban</span><b>Rp 905 jt</b><i class="dn">▲ 12% vs bulan lalu</i></div>
            <div><span>Laba bersih</span><b>Rp 335 jt</b><i class="up">27% margin</i></div>
            <div><span>Saldo kas</span><b>Rp 612 jt</b><i class="up">Cukup 2,4 bulan</i></div>
          </div>
          <div class="chart">
            <div class="ch">Anggaran vs realisasi per pos
              <div class="lg"><span><i class="sw-budget"></i>Anggaran</span><span><i class="sw-real"></i>Realisasi</span></div>
            </div>
            <div class="bars">
              <?php foreach ($bars as [$pos, $pct]): ?>
              <div class="g">
                <div class="p">
                  <i class="b-budget" style="height:<?= round(90 / 141 * 100) ?>%"></i>
                  <i class="b-real<?= $pct > 100 ? ' over' : '' ?>" style="height:<?= round($pct * 0.9 / 141 * 100) ?>%" title="<?= $pct ?>% dari anggaran"></i>
                </div>
                <span><?= $pos ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="ai"><div class="c"><?= icon('sparkle', 16, 2) ?></div><div><b>Insight AI</b><span>Biaya transportasi 41% di atas anggaran, terbesar di Cabang Timur. Biaya operasional juga melebihi anggaran 18%. Disarankan meninjau 12 transaksi terbesar.</span></div></div>
        </div>
        <div class="ill3">Contoh ilustrasi, angka fiktif</div>
      </div>
    </div>
    <div class="fit"><b>Cocok untuk:</b><span>UMKM dan toko</span><span>Yayasan dan lembaga non-profit</span><span>Perusahaan dengan banyak cabang</span><span>Tim keuangan yang masih rekap manual di Excel</span></div>
  </div>
</section>
<?php }

function section_pov(): void
{
    $phases = [
        ['MINGGU 1', 'Foundation', 'Memahami proses dan menentukan prioritas.', ['Pemetaan proses bisnis', 'Identifikasi data &amp; workflow', 'Menentukan KPI keberhasilan'], false],
        ['MINGGU 2–5', 'Build &amp; Validate', 'Membangun automation dan menguji dampaknya.', ['Membangun workflow &amp; AI', 'Integrasi data', 'Uji coba bersama user'], false],
        ['MINGGU 6', 'Impact', 'Mengukur hasil dan menentukan langkah berikutnya.', ['Review pencapaian KPI', 'Laporan Proof of Value', 'Roadmap scale-up'], true],
    ];
    ?>
<section class="pov" id="cara-kerja">
  <div class="wrap">
    <div class="hd2">
      <div><?= eyebrow_title('Cara kerja', 'Proof of Value <em>6 Minggu</em>') ?></div>
      <p class="lead">Mulai dari skala kecil, ukur dampaknya, lalu perluas automation ke area yang paling bernilai.</p>
    </div>
    <div class="pgrid">
      <?php foreach ($phases as $i => [$wk, $h, $p, $items, $hl]): ?>
      <div class="card pc">
        <div class="wk<?= $hl ? ' hl' : '' ?>"><?= $wk ?></div>
        <div class="bd">
          <div class="h"><span class="num"><?= $i + 1 ?></span><h3><?= $h ?></h3></div>
          <p><?= $p ?></p>
          <ul><?php foreach ($items as $it): ?><li><?= check() ?><?= $it ?></li><?php endforeach; ?></ul>
        </div>
      </div>
      <?php endforeach; ?>
      <div class="gate">
        <div class="dm"><div><?= icon('flag', 22, 2) ?></div></div>
        <b>Go / No-Go</b>
        <span>Anda memutuskan lanjut setelah melihat hasilnya, bukan sebelum.</span>
      </div>
    </div>
  </div>
</section>
<?php }

function section_before_after(): void
{
    $before = [['db', 'Data terpisah', 'Data tersebar di spreadsheet, aplikasi, dan chat.'], ['clock', 'Proses reaktif', 'Tim bergerak setelah masalah terjadi.'], ['users', 'Follow-up manual', 'Peluang bergantung pada ingatan tiap orang.'], ['eye', 'Visibilitas terbatas', 'Pimpinan melihat hasil setelah semuanya selesai.']];
    $after  = [['layers', 'Data terhubung', 'Data diolah menjadi insight yang relevan.'], ['target', 'Tindakan proaktif', 'Sistem mendeteksi risiko dan peluang lebih awal.'], ['zap', 'Workflow otomatis', 'Follow-up dan pengingat berjalan konsisten.'], ['gauge', 'Visibilitas real-time', 'Pimpinan melihat kondisi terkini dan memutuskan lebih cepat.']];
    ?>
<section class="ba">
  <div class="wrap">
    <div class="center"><?= eyebrow_title('Perubahan yang kami bangun', 'Dari Digital Operation Menuju <em>Intelligent Operation</em>') ?></div>
    <div class="bagrid">
      <div class="bacol bf">
        <div class="bat"><span class="tag b">SEBELUM</span><b>Digital operation</b></div>
        <?php foreach ($before as [$ic, $b, $s]): ?><div class="bar2"><div class="ii"><?= icon($ic, 20) ?></div><div><b><?= $b ?></b><span><?= $s ?></span></div></div><?php endforeach; ?>
      </div>
      <div class="vs">VS</div>
      <div class="bacol af">
        <div class="bat"><span class="tag a">SESUDAH</span><b>Intelligent operation</b></div>
        <?php foreach ($after as [$ic, $b, $s]): ?><div class="bar2"><div class="ii"><?= icon($ic, 20) ?></div><div><b><?= $b ?></b><span><?= $s ?></span></div></div><?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php }

function section_why(): void
{
    $items = [['Mulai dari satu proses', 'Satu use case bernilai tinggi dibuktikan dulu.'], ['Di atas sistem yang ada', 'Tidak memaksa Anda mengganti CRM atau database.'], ['Manusia memutuskan', 'AI memberi rekomendasi, keputusan tetap di tim Anda.'], ['Diukur dari baseline', 'Target disepakati sejak Discovery.'], ['Data dijaga', 'Akses per peran dan prinsip UU PDP.']];
    ?>
<section class="why">
  <div class="wrap">
    <div class="hd2">
      <div><?= eyebrow_title('Kenapa AGNIA', 'Cara kerja yang <em>bisa dipertanggungjawabkan</em>') ?></div>
      <p class="lead">Setiap proyek punya baseline, titik keputusan, dan ukuran dampak yang disepakati bersama.</p>
    </div>
    <div class="wgrid">
      <?php foreach ($items as $i => [$b, $s]): ?>
      <div class="card wc"><span class="num"><?= $i + 1 ?></span><b><?= $b ?></b><span><?= $s ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php }

function section_cta(): void
{ ?>
<section class="cta-wrap">
  <div class="wrap">
    <div class="cta">
      <img class="art" src="assets/images/simbol-cta.svg" alt="" aria-hidden="true" loading="lazy">
      <div class="cta-in">
        <?= eyebrow_title('Langkah pertama', 'Mulai dari <em>Satu Proses</em><br>Bernilai Tinggi') ?>
        <p class="lead">Dalam Discovery Workshop 90 menit, kita tentukan satu proses prioritas, memahami alur kerja nyatanya, dan menetapkan data awal untuk Proof of Value.</p>
        <div class="steps">
          <div class="st"><span class="num">1</span><b>Pemilik proses</b><span>Siapa PIC dan pengambil keputusannya</span></div>
          <div class="st"><span class="num">2</span><b>Alur kerja nyata</b><span>Bagaimana proses berjalan hari ini</span></div>
          <div class="st"><span class="num">3</span><b>Data awal</b><span>Metrik baseline sebelum automation</span></div>
        </div>
        <div class="ctas">
          <a class="btn p" href="kontak#form">Jadwalkan Discovery Workshop <?= arrow() ?></a>
          <a class="btn o" href="<?= e(wa_link('Halo AGNIA, saya ingin menjadwalkan Discovery Workshop.')) ?>"<?= wa_attrs() ?>>Hubungi kami di WhatsApp</a>
          <div class="meta">
            <span><?= icon('clock', 18) ?>90 menit</span>
            <span><?= icon('globe', 18) ?>Online atau tatap muka</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php }
