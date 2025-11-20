# 🐟 Exotic Mahseer – Backend API

Backend API untuk website **Exotic Mahseer** – mengelola katalog produk ikan, artikel, testimoni, hingga aktivitas brand, lengkap dengan:

- Admin panel cantik berbasis **Filament v4**
- Konten multi-bahasa (**Indonesia & English**) dengan **Spatie Translatable**
- Multi-currency dengan konversi harga otomatis
- REST API rapi untuk di-consume oleh frontend (Vite/React, dsb)

> Repo ini fokus sebagai **Headless Backend** – tidak ada Blade view untuk halaman publik, semua untuk API + admin dashboard.

---

## 🏗️ Tech Stack

- **Framework**: Laravel 12.x
- **Admin Panel**: Filament v4
- **Database**: MySQL 8+
- **Media**: [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)
- **Multi Bahasa**: [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable)
- **Multi Currency**
- **Autentikasi Admin**: Filament login (user dari tabel `users`)

---

## ✨ Fitur Utama

### 1. Admin Panel (Filament)

Module yang tersedia:

- **Produk**
  - CRUD produk
  - Kategori produk
  - Multi gambar via Media Library (`product_images`)
  - Harga dasar + auto convert multi currency
  - Toggle aktif/nonaktif
- **Artikel**
  - Gambar utama
  - Judul & isi multi-bahasa
  - Penulis (bukan user admin)
  - Status publish + tanggal terbit
- **Testimoni**
  - Nama, institusi/perusahaan, urutan tampil
  - Deskripsi multi-bahasa
  - Avatar foto
- **Kategori**
  - Tipe (`product`, `article`, dll)
  - Nama multi-bahasa
  - Slug SEO-friendly (otomatis dari nama ID)
- **Aktifitas**
  - Judul, deskripsi singkat
  - Gambar gallery (misal untuk section “Our Activities” di homepage)
- **Mata Uang**
  - Kode (IDR, USD, …)
  - Nama, simbol
  - `exchange_rate` terhadap base currency
  - Flag `is_active`, `is_default`

### 2. REST API untuk Frontend

- Semua response dalam format JSON
- Query parameter `lang=id|en` untuk translasi konten
- Multi currency:
  - `price_base` → harga dalam base currency (mis: IDR)
  - `prices` → list harga di semua currency aktif
  - `display_currency` & `display_price` → harga yang direkomendasikan tampil di frontend

### 3. Filtering & Sorting Produk

- Filter kategori via slug (`category=ikan-mahseer`)
- Filter harga (`min_price` & `max_price`)
- Sorting harga (`sort=Termurah` / `Termahal`)
- Hanya produk aktif yang keluar (`is_active = true`)