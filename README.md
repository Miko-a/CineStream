LAPORAN FP PWEB B - CineStream
---
| Name           | NRP        |
| ---            | ---        |
| Afsal Murtaza |  5025241190  |
| Farrel Jatmiko Aji | 5025241193 |
| Raihan Rasyid Ramadhan | 5025241224 |

# Frontend & Backend Development

Pengembangan aplikasi ini menggunakan arsitektur Monolithic sederhana di mana logika backend dan tampilan frontend terintegrasi dalam struktur Native PHP.

Frontend (Antarmuka Pengguna) :

- Framework CSS : Menggunakan Bootstrap 5 untuk menjamin responsivitas (mobile-friendly) dan grid layout yang rapi.

- Desain UI : Mengusung tema Dark Mode mirip Netflix untuk kenyamanan visual saat menonton.

- Komponen Utama :

    - Navbar : Navigasi responsif untuk Login, Register, dan Kategori.

    - Hero Section : Carousel untuk menampilkan film trending.

    - Movie Grid : Tampilan daftar film menggunakan Card Component Bootstrap.

- Interaktivitas : Menggunakan JavaScript (Vanilla) untuk validasi form sederhana dan manipulasi DOM (seperti modal untuk detail film).

Backend (Logika Server) :

- Bahasa : Native PHP (tanpa framework) untuk menangani logika bisnis.

- Sesi (Session Management) : Menggunakan session_start() untuk membatasi akses halaman. Halaman seperti dashboard.php atau watch.php hanya bisa diakses jika user telah login.

- Keamanan Dasar :

    - Penggunaan password_hash() (Bcrypt) untuk mengenkripsi password pengguna sebelum disimpan ke database.

    - Penggunaan mysqli_real_escape_string() atau Prepared Statements untuk mencegah SQL Injection dasar.


# Database Implementation

Penyimpanan data menggunakan MySQL. Struktur database dirancang untuk mengelola pengguna dan konten film.

Nama Database : cinestream_db (Asumsi nama default)
- users : Menyimpan data akun pengguna (id (PK), username, email, password, created_at)
- movies : Menyimpan metadata film (id (PK), title, description, genre, release_date, video_url, poster_url)
- genres : Kategorisasi film (id (PK), name)
- watchlist : Daftar tontonan pengguna (id (PK), user_id (FK), movie_id (FK))

Catatan Implementasi : Koneksi database dilakukan melalui file konfigurasi terpisah (misalnya config.php atau db.php) agar mudah dikelola.


# Integrasi API

CineStream menerapkan integrasi data untuk memperkaya konten tanpa harus menginput satu per satu secara manual.

Sumber Data Eksternal (TMDB API) :

- Aplikasi mengambil metadata film (Poster, Sinopsis, Rating, Judul) menggunakan API publik seperti The Movie Database (TMDB).

- Metode Fetching : Menggunakan fungsi cURL pada PHP atau fetch() pada JavaScript untuk melakukan request GET ke endpoint API.

- Contoh Endpoint : https://api.themoviedb.org/3/movie/popular?api_key=KEY_ANDA.

Pengolahan JSON : Data yang diterima dalam format JSON di-decode oleh PHP (json_decode) untuk kemudian ditampilkan dalam looping HTML di halaman utama.


# Pengujian (Testing)

Berdasarkan kode, pendekatan pengujian yang dilakukan adalah Manual Testing. Tidak terlihat integrasi framework pengujian otomatis seperti PHPUnit untuk Unit Test atau Selenium untuk End-to-End Test.

Scope Testing / Pengujian manual mencakup :

- Fungsional : Memastikan semua fitur (login, registrasi, pemesanan) berjalan sesuai alur bisnis.

- Antarmuka (UI) : Memeriksa responsivitas dan konsistensi tampilan.

- Keamanan Dasar : Menguji kerentanan seperti SQL Injection dan validasi input.


# Diagram Sistem Arsitektur CineStream

Untuk memvisualisasikan alur kerja sistem CineStream, berikut adalah diagram alur data utama.

Flowchart Alur Pengguna (User Flow)

Diagram ini menjelaskan langkah pengguna dari mulai membuka web hingga menonton film.

- Start -> Halaman Landing

- Decision : Punya Akun?

    - No -> Halaman Register -> Input Data -> Simpan DB -> Login.

    - Yes -> Halaman Login -> Validasi DB.

- Dashboard Utama -> Pilih Film -> Halaman Detail -> Play Movie.

- End (Logout).

Use Case Diagram

- Aktor : Pengguna (User), Admin.

- User : Login, Register, Mencari Film, Menonton Film, Menambah ke Watchlist.

- Admin : Login, Menambah Film (CRUD), Mengelola User.


# User Guide CineStream
A. Akses Awal Sistem

- Buka CineStream

- Halaman utama akan menampilkan daftar film.

B. Panduan untuk Penonton (User)

- Registrasi untuk membuat akun baru / masukkan username dan password di halaman login.

- Memilih Film :  Jelajahi katalog film di halaman utama & Klik film yang diinginkan.

C. Panduan untuk Administrator

- Akses Dashboard Admin : Login dengan kredensial khusus admin melalui halaman login (/admin/login.php atau serupa).

- Manajemen Konten: Kelola Film : Tambah, edit, atau hapus film dari katalog.

- Manajemen User : Kelola data pengguna.

D. Troubleshooting Umum

- Login Gagal : Pastikan username dan password benar.

- Halaman Tidak Muncul : Periksa koneksi internet. Pastikan URL yang dimasukkan benar.


# Kesimpulan dan Rekomendasi

CineStream berhasil diimplementasikan sebagai aplikasi web monolitik berbasis PHP native yang fungsional dengan pemisahan logika admin, user, dan API. Sistem ini cocok untuk skala kecil hingga menengah.

Rekomendasi Pengembangan Lanjutan :

- Keamanan : Implementasi prepared statements secara konsisten untuk mencegah SQL Injection dan hash password yang kuat (misal, password_hash()).

- Kode : Menerapkan pola MVC yang eksplisit untuk memisahkan logika bisnis, data, dan tampilan. Pertimbangkan framework seperti Laravel atau CodeIgniter.

- Pengujian : Integrasikan PHPUnit untuk Unit Testing dan Selenium untuk Automated UI Testing gaman.

- API : Dokumentasi API menggunakan OpenAPI/Swagger untuk kemudahan integrasi di masa depan.


# Pembagian Jobdesc

Dari kami rata semua pembagiannya + mengerjakan bersama, saling menambahkan & melengkapi 1 sama lain juga.

Jadi overall bisa ditulis :
- Afsal Murtaza |  5025241190 = Frontend & Backend Development, Pembuatan Laporan, Pembuatan Login/Register
- Farrel Jatmiko Aji | 5025241193 = Frontend & Backend Development, Pembuatan Database, API OMDB
- Raihan Rasyid Ramadhan | 5025241224 = Frontend Development User & Admin
