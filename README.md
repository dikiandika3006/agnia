# Website AGNIA

Website PHP (tanpa framework/database), responsif desktop–tablet–ponsel, dibangun dari aset & desain di `D:\AGNIA\Website`.

## Halaman
| URL | File |
|---|---|
| `/` | `index.php` — Beranda |
| `/layanan` | `layanan.php` — detail 4 layanan, aplikasi, Proof of Value |
| `/solusi` | `solusi.php` — Sales Force Intelligence, AI Keuangan, Sistem Manajemen Toko |
| `/tentang` | `tentang.php` |
| `/kontak` | `kontak.php` — form + WhatsApp/email + FAQ |
| `/kebijakan-privasi` | `kebijakan-privasi.php` |

## Menjalankan
Laragon: Start All, lalu buka `http://agnia.test` (atau `http://localhost/agnia`). URL bersih memakai `.htaccess` (Apache/LiteSpeed).

## Admin (`/admin`)
- **Pengaturan** — WhatsApp, email, LinkedIn, lokasi, domain, email penerima form.
- **Pesan masuk** — pesan dari form kontak, hapus & unduh CSV.
- **Screenshot** — unggah gambar Sistem Manajemen Toko (menggantikan mockup).
- **Akun** — ganti password.

Password pertama **hanya bisa dibuat dari komputer lokal** (Laragon). Saat upload ke hosting, sertakan `storage/admin.json`.
Login dikunci 15 menit setelah 5 kali salah. Semua data admin ada di `storage/` (tidak bisa diakses publik) — folder ini harus bisa ditulis oleh PHP.

## Sebelum online
1. Buat password admin di lokal, lalu isi pengaturan kontak lewat `/admin`.
2. Pesan form dikirim ke email penerima bila `mail()` aktif di hosting; selalu tersimpan juga di admin.
3. Minta tinjauan legal untuk isi Kebijakan Privasi.

## Struktur
- `includes/header.php`, `footer.php` — kerangka halaman & navigasi
- `includes/sections.php` — section yang dipakai ulang (layanan, aplikasi, SFI, keuangan, PoV, CTA, dll.)
- `css/agnia-tokens.css` (token brand), `css/style.css` (gaya + responsif), `js/main.js` (menu mobile, animasi)
