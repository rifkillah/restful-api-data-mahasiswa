<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaApiController extends Controller
{
    // 1. GET /api/mahasiswa -> Menampilkan seluruh data mahasiswa
    public function index()
    {
        $mahasiswas = Mahasiswa::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Daftar data mahasiswa berhasil diambil',
            'jumlah_data' => $mahasiswas->count(),
            'data' => $mahasiswas
        ], 200);
    }

    // 2. POST /api/mahasiswa -> Menambahkan data mahasiswa baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'ipk' => 'required|numeric|between:0,4.00',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal! Periksa kembali data yang dikirim.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa = Mahasiswa::create($request->only(['nim', 'nama', 'jurusan', 'ipk']));

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil menambahkan data mahasiswa baru via POST /api/mahasiswa (201 Created)!',
            'data' => $mahasiswa
        ], 201);
    }

    // 3. GET /api/mahasiswa/{id} -> Menampilkan detail 1 mahasiswa
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data mahasiswa dengan ID tersebut tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Detail data mahasiswa ditemukan',
            'data' => $mahasiswa
        ], 200);
    }

    // 4. PUT /api/mahasiswa/{id} -> Memperbarui data mahasiswa
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data mahasiswa tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $id,
            'nama' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'ipk' => 'required|numeric|between:0,4.00',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal saat memperbarui data!',
                'errors' => $validator->errors()
            ], 422);
        }

        $mahasiswa->update($request->only(['nim', 'nama', 'jurusan', 'ipk']));

        return response()->json([
            'status' => true,
            'pesan' => 'Data mahasiswa berhasil diperbarui!',
            'data' => $mahasiswa
        ], 200);
    }

    // 5. DELETE /api/mahasiswa/{id} -> Menghapus data mahasiswa
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data mahasiswa tidak ditemukan!'
            ], 404);
        }

        $mahasiswa->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Data mahasiswa berhasil dihapus dari server API!'
        ], 200);
    }
}
