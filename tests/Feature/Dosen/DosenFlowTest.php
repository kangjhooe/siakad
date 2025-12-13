<?php

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\JadwalKuliah;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'dosen']);
    $this->prodi = Prodi::factory()->create();
    $this->dosen = Dosen::factory()->create([
        'user_id' => $this->user->id,
        'prodi_id' => $this->prodi->id,
    ]);
});

test('dosen can view their dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('dosen.dashboard'));
    
    $response->assertStatus(200);
});

test('dosen can view bimbingan list', function () {
    $response = $this->actingAs($this->user)->get(route('dosen.bimbingan.index'));
    
    $response->assertStatus(200);
});

test('dosen can view presensi list', function () {
    $response = $this->actingAs($this->user)->get(route('dosen.presensi.index'));
    
    $response->assertStatus(200);
});

test('dosen can view skripsi bimbingan list', function () {
    $response = $this->actingAs($this->user)->get(route('dosen.skripsi.index'));
    
    $response->assertStatus(200);
});

test('dosen can view kp bimbingan list', function () {
    $response = $this->actingAs($this->user)->get(route('dosen.kp.index'));
    
    $response->assertStatus(200);
});

test('dosen cannot access mahasiswa routes', function () {
    $response = $this->actingAs($this->user)->get(route('mahasiswa.dashboard'));
    
    $response->assertStatus(403);
});
