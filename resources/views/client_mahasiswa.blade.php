<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Portal Konsumen RESTful API Mahasiswa</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 40px; }
.card { max-width: 820px; margin: auto; background: white; padding: 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 12px; }
h2 { margin: 0; color: #2c3e50; }
.badge-status { background: #27ae60; color: white; padding: 6px 14px; border-radius: 12px; font-size: 12px; font-weight: bold; }
.alert { display: none; padding: 10px 14px; border-radius: 5px; font-size: 13px; margin-top: 15px; }
.alert-sukses { background: #d4edda; color: #155724; }
.alert-error { background: #f8d7da; color: #721c24; }
.form-row { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
.form-row input { flex: 1; min-width: 150px; padding: 9px; border: 1px solid #ccc; border-radius: 5px; }
.btn-simpan { background: #3498db; color: white; border: none; padding: 9px 18px; border-radius: 5px; font-weight: bold; cursor: pointer; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
th { background: #2c3e50; color: white; text-align: left; padding: 10px; }
td { padding: 10px; border-bottom: 1px solid #eee; }
.btn-hapus { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; cursor: pointer; }
</style>
</head>
<body>
<div class="card">
    <div class="header-row">
        <h2>Portal Konsumen RESTful API Mahasiswa</h2>
        <span class="badge-status" id="indikatorApi">API STATUS: 200 OK</span>
    </div>
    <div id="kotakPesan" class="alert"></div>

    <!-- Form Input untuk Mengirim Request POST ke RESTful API -->
    <form id="formApiMahasiswa" class="form-row">
        <input type="text" id="nim" placeholder="NIM (Contoh: 2210114001)" required>
        <input type="text" id="nama" placeholder="Nama Mahasiswa" required>
        <input type="text" id="jurusan" placeholder="Jurusan" required>
        <input type="number" step="0.01" id="ipk" placeholder="IPK (0-4)" required>
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
</div>

<script>
const API_URL = '/api/mahasiswa';

function tampilkanPesan(teks, sukses = true) {
    const box = document.getElementById('kotakPesan');
    box.style.display = 'block';
    box.className = 'alert ' + (sukses ? 'alert-sukses' : 'alert-error');
    box.innerText = (sukses ? '' : 'X ') + teks;
}

// 1. Fungsi Mengkonsumsi Endpoint GET /api/mahasiswa
async function muatDataDariApi() {
    const res = await fetch(API_URL, { headers: { 'Accept': 'application/json' } });
    const json = await res.json();
    const tbody = document.getElementById('tabelBodyApi');
    tbody.innerHTML = '';
    
    if (json.data.length === 0) {
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
            // Mengambil pesan dari json.pesan atau json.message agar tidak undefined
            const pesanError = json.pesan || json.message || 'Validasi gagal atau terjadi kesalahan server!';
            tampilkanPesan(pesanError, false);
        }
    } catch (err) {
        tampilkanPesan('Gagal terhubung ke server API!', false);
    }
});

// 3. Fungsi Mengkonsumsi Endpoint DELETE /api/mahasiswa/{id}
async function hapusViaApi(id) {
    if (!confirm('Yakin ingin menghapus data ini?')) return;

    const res = await fetch(`${API_URL}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    });
    const json = await res.json();
    tampilkanPesan(json.pesan, res.ok);
    muatDataDariApi();
}

// Panggil fungsi GET saat halaman pertama kali dibuka
muatDataDariApi();
</script>
</body>
</html>