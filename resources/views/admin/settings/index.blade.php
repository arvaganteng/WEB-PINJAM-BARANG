@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Pengaturan Sistem</div>
    <div class="page-subtitle">Konfigurasi data perusahaan dan preferensi administrator</div>
  </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
  @csrf
  <div class="settings-sections">
    <div class="settings-section">
      <div class="settings-section-header">
        <div class="settings-section-icon fi-blue">🏢</div>
        <div>
          <div class="settings-section-title">Informasi Perusahaan</div>
          <div class="settings-section-desc">Identitas perusahaan PT Nusantara Digital Express</div>
        </div>
      </div>
      <div class="settings-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-input" value="PT Nusantara Digital Express" readonly/>
          </div>
          <div class="form-group">
            <label class="form-label">Singkatan</label>
            <input type="text" class="form-input" value="NDE" readonly/>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Email Dukungan</label>
            <input type="email" class="form-input" value="info@nde.co.id" readonly/>
          </div>
          <div class="form-group">
            <label class="form-label">Nomor Kontak</label>
            <input type="tel" class="form-input" value="(021) 1234-5678" readonly/>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Alamat Kantor</label>
          <textarea class="form-input form-textarea" readonly>Gedung NDE Tower Lt. 12, Jl. Sudirman Kav. 52-53, Jakarta Pusat</textarea>
        </div>
      </div>
    </div>

    <div class="settings-section">
      <div class="settings-section-header">
        <div class="settings-section-icon fi-purple">📋</div>
        <div>
          <div class="settings-section-title">Informasi Operasional Inventaris</div>
          <div class="settings-section-desc">Jam operasional dan lokasi serah terima aset inventaris</div>
        </div>
      </div>
      <div class="settings-body">
        <div class="form-group">
          <label class="form-label">Jam & Lokasi Layanan Inventaris</label>
          <input type="text" class="form-input" value="Senin - Jumat (08:00 - 17:00 WIB) | Gedung NDE Lt. 2 (Ruang Inventaris & Logistik)" readonly/>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
