<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mahasiswa;

class MahasiswaApiTest extends TestCase
{
    public function test_get_all_mahasiswa()
    {
        $response = $this->getJson('/api/mahasiswa');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'pesan',
                     'jumlah_data',
                     'data' => [
                         '*' => ['id', 'nim', 'nama', 'jurusan', 'ipk']
                     ]
                 ]);
    }

    public function test_create_mahasiswa_success()
    {
        // Ensure clean test state
        Mahasiswa::where('nim', '2210114999')->delete();

        $payload = [
            'nim' => '2210114999',
            'nama' => 'Rifkillah muliya al-faris',
            'jurusan' => 'Teknik Informatika',
            'ipk' => 3.95
        ];

        $response = $this->postJson('/api/mahasiswa', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'nim' => '2210114999',
                         'nama' => 'Rifkillah muliya al-faris',
                     ]
                 ]);

        // Cleanup
        Mahasiswa::where('nim', '2210114999')->delete();
    }

    public function test_create_mahasiswa_validation_error()
    {
        $payload = [
            'nim' => '',
            'nama' => '',
            'jurusan' => '',
            'ipk' => 5.0 // Invalid IPK > 4.00
        ];

        $response = $this->postJson('/api/mahasiswa', $payload);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                     'pesan' => 'Validasi gagal! Periksa kembali data yang dikirim.',
                 ]);
    }

    public function test_show_mahasiswa_found_and_not_found()
    {
        $mhs = Mahasiswa::first();
        if ($mhs) {
            $response = $this->getJson('/api/mahasiswa/' . $mhs->id);
            $response->assertStatus(200)
                     ->assertJson([
                         'status' => true,
                         'data' => [
                             'id' => $mhs->id,
                             'nim' => $mhs->nim,
                         ]
                     ]);
        }

        $response404 = $this->getJson('/api/mahasiswa/999999');
        $response404->assertStatus(404)
                    ->assertJson([
                        'status' => false,
                        'pesan' => 'Data mahasiswa dengan ID tersebut tidak ditemukan!'
                    ]);
    }

    public function test_update_mahasiswa()
    {
        $temp = Mahasiswa::create([
            'nim' => '2210114888',
            'nama' => 'Testing Update',
            'jurusan' => 'Sistem Informasi',
            'ipk' => 3.50,
        ]);

        $updatePayload = [
            'nim' => '2210114888',
            'nama' => 'Testing Update Selesai',
            'jurusan' => 'Teknik Komputer',
            'ipk' => 3.80,
        ];

        $response = $this->putJson('/api/mahasiswa/' . $temp->id, $updatePayload);
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'pesan' => 'Data mahasiswa berhasil diperbarui!',
                     'data' => [
                         'nama' => 'Testing Update Selesai',
                         'jurusan' => 'Teknik Komputer'
                     ]
                 ]);

        $temp->delete();
    }

    public function test_delete_mahasiswa()
    {
        $temp = Mahasiswa::create([
            'nim' => '2210114777',
            'nama' => 'Testing Delete',
            'jurusan' => 'Sistem Informasi',
            'ipk' => 3.10,
        ]);

        $response = $this->deleteJson('/api/mahasiswa/' . $temp->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'pesan' => 'Data mahasiswa berhasil dihapus dari server API!'
                 ]);
    }

    public function test_client_mahasiswa_page()
    {
        $response = $this->get('/client-mahasiswa');

        $response->assertStatus(200);
        $response->assertSee('Rifkillah muliya al-faris');
        $response->assertSee('Portal Konsumen RESTful API Mahasiswa');
    }
}
