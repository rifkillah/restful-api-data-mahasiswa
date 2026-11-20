<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Konsumen RESTful API Mahasiswa - Rifkillah muliya al-faris</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 40px; }
        .card { max-width: 860px; margin: auto; background: white; padding: 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 12px; }
        h2 { margin: 0; color: #2c3e50; }
        .author-tag { color: #7f8c8d; font-size: 13px; margin-top: 4px; }
        .badge-status { background: #27ae60; color: white; padding: 6px 14px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .alert { display: none; padding: 10px 14px; border-radius: 5px; font-size: 13px; margin-top: 15px; }
        .alert-sukses { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .form-row { display: flex; gap: 10px; margin-top: 18px; }
        .form-row input { flex: 1; padding: 9px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-simpan { background: #3498db; color: white; border: none; padding: 9px 18px; border-radius: 5px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-simpan:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th { background: #2c3e50; color: white; text-align: left; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .btn-hapus { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-hapus:hover { background: #c0392b; }
        .watermark-footer { margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ddd; text-align: center; font-size: 12px; color: #64748b; }
        .watermark-badge { display: inline-block; background: #e2e8f0; color: #334155; padding: 4px 10px; border-radius: 6px; font-weight: 600; margin-left: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header-row">
            <div>
                <h2>Portal Konsumen RESTful API Mahasiswa</h2>
                <div class="author-tag">Praktikum Pemrograman Web &bull; <strong>Rifkillah muliya al-faris</strong></div>
            </div>
            <span class="badge-status" id="indikatorApi">API STATUS: 200 OK</span>
        </div>
        <div id="kotakPesan" class="alert"></div>

        <!-- Form Input untuk Mengirim Request POST ke RESTful API -->
        <form id="formApiMahasiswa" class="form-row">
            <input type="text" id="nim" placeholder="NIM (Contoh: 2210114001)" required>
            <input type="text" id="nama" placeholder="Nama Mahasiswa" required>
            <input type="text" id="jurusan" placeholder="Jurusan" required>
            <input type="number" step="0.01" min="0" max="4" id="ipk" placeholder="IPK (0-4)" required>
            <button type="submit" class="btn-simpan">+ Simpan ke API</button>
        </form>

        <!-- Tabel yang Diisi Otomatis dari Response JSON GET /api/mahasiswa -->
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jurusan</th>
                    <th>IPK</th>
                    <th>Aksi API</th>
                </tr>
            </thead>
            <tbody id="tabelBodyApi">
                <tr><td colspan="5">Memuat data dari endpoint /api/mahasiswa...</td></tr>
            </tbody>
        </table>

        <!-- Watermark Rifkillah muliya al-faris -->
        <div class="watermark-footer">
            <span>Watermark Sistem:</span>
            <span class="watermark-badge">Rifkillah muliya al-faris</span>
            &bull; Database: <strong>MySQL (phpMyAdmin)</strong>
        </div>
    </div>

    <script>
        const API_URL = '/api/mahasiswa';

        function tampilkanPesan(teks, sukses = true) {
            const box = document.getElementById('kotakPesan');
            box.style.display = 'block';
            box.className = 'alert ' + (sukses ? 'alert-sukses' : 'alert-error');
            box.innerText = (sukses ? '✓ ' : '✗ ') + teks;
        }

        // 1. Fungsi Mengkonsumsi Endpoint GET /api/mahasiswa
        async function muatDataDariApi() {
            try {
                const res = await fetch(API_URL, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                const tbody = document.getElementById('tabelBodyApi');
                tbody.innerHTML = '';

                if (!json.data || json.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5">Belum ada data di dalam API.</td></tr>';
                    return;
                }

                json.data.forEach(mhs => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${mhs.nim}</td>
                            <td>${mhs.nama}</td>
                            <td>${mhs.jurusan}</td>
                            <td>${mhs.ipk}</td>
                            <td>
                                <button class="btn-hapus" onclick="hapusViaApi(${mhs.id})">Hapus (DELETE)</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (err) {
                tampilkanPesan('Gagal memuat data dari API: ' + err.message, false);
            }
        }

        // 2. Fungsi Mengkonsumsi Endpoint POST /api/mahasiswa
        document.getElementById('formApiMahasiswa').addEventListener('submit', async function(e) {
            e.preventDefault();
            const payload = {
                nim: document.getElementById('nim').value,
                nama: document.getElementById('nama').value,
                jurusan: document.getElementById('jurusan').value,
                ipk: document.getElementById('ipk').value
            };

            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if (res.status === 201) {
                    tampilkanPesan(json.pesan, true);
                    this.reset();
                    muatDataDariApi();
                } else {
                    let pesanError = json.pesan || 'Validasi gagal!';
                    if (json.errors) {
                        const errorDetails = Object.values(json.errors).flat().join(', ');
                        pesanError += ' (' + errorDetails + ')';
                    }
                    tampilkanPesan(pesanError, false);
                }
            } catch (err) {
                tampilkanPesan('Terjadi kesalahan jaringan/server: ' + err.message, false);
            }
        });

        // 3. Fungsi Mengkonsumsi Endpoint DELETE /api/mahasiswa/{id}
        async function hapusViaApi(id) {
            if (!confirm('Apakah kamu yakin ingin menghapus data mahasiswa ini via API?')) {
                return;
            }

            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                const json = await res.json();
                tampilkanPesan(json.pesan, res.ok);
                muatDataDariApi();
            } catch (err) {
                tampilkanPesan('Terjadi kesalahan saat menghapus: ' + err.message, false);
            }
        }

        // Panggil fungsi GET saat halaman pertama kali dibuka
        muatDataDariApi();
    </script>
</body>
</html>
