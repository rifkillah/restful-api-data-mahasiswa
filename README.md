# Layanan RESTful API Data Mahasiswa dan Portal Web Konsumen API

Contoh implementasi **RESTful API** di Laravel untuk mengelola data mahasiswa, dilengkapi portal web sederhana yang mengonsumsi API tersebut menggunakan JavaScript `fetch`.

## Fitur

- CRUD data mahasiswa melalui REST API: daftar, tambah, detail, ubah, hapus.
- Validasi input (`nim` unik, `nama`, `jurusan`, `ipk` 0–4.00) dengan respons error 422.
- Respons JSON konsisten (`status`, `pesan`, `data`).
- Portal konsumen di `/client-mahasiswa` untuk menampilkan, menambah, dan menghapus data lewat API.
- Data awal mahasiswa melalui seeder.
- Feature test API (`tests/Feature/MahasiswaApiTest.php`).

## Teknologi

- PHP 8.3+
- Laravel
- Blade + JavaScript (fetch)
- SQLite (default)

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

- Portal konsumen: http://localhost:8000/client-mahasiswa
- API: http://localhost:8000/api/mahasiswa

## Endpoint API

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/mahasiswa` | Daftar semua mahasiswa |
| POST | `/api/mahasiswa` | Tambah mahasiswa baru |
| GET | `/api/mahasiswa/{id}` | Detail mahasiswa |
| PUT/PATCH | `/api/mahasiswa/{id}` | Ubah data mahasiswa |
| DELETE | `/api/mahasiswa/{id}` | Hapus mahasiswa |

Contoh request:

```bash
curl -X POST http://localhost:8000/api/mahasiswa \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"nim":"2410114999","nama":"Nama Mahasiswa","jurusan":"Teknik Informatika","ipk":3.75}'
```

Field data: `nim`, `nama`, `jurusan`, `ipk`.

## Pengujian

```bash
php artisan test
```

## Struktur Penting

- `app/Http/Controllers/Api/MahasiswaApiController.php` — seluruh endpoint API.
- `app/Models/Mahasiswa.php` — model dan field yang dapat diisi.
- `routes/api.php` — `Route::apiResource('mahasiswa', ...)`.
- `database/seeders/DatabaseSeeder.php` — data contoh.
- `resources/views/client_mahasiswa.blade.php` — portal konsumen API.
