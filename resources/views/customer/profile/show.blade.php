@extends('layouts.customer')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Profil &amp; Keamanan Akun</div>
    <div class="page-subtitle">Kelola informasi data pribadi dan kata sandi akun Anda</div>
  </div>
</div>

<div class="profile-layout">
  <div>
    <div class="profile-sidebar-card">
      <div class="profile-avatar">{{ substr($user->name, 0, 1) }}</div>
      <div class="profile-name">{{ $user->name }}</div>
      <div class="profile-email">{{ $user->email }}</div>
      <div class="profile-stats-row" style="margin-top:16px">
        <div class="profile-stat">
          <div class="profile-stat-num">{{ $totalBorrowings }}</div>
          <div class="profile-stat-label">Transaksi</div>
        </div>
        <div class="profile-stat">
          <div class="profile-stat-num">{{ $activeBorrowings }}</div>
          <div class="profile-stat-label">Aktif</div>
        </div>
        <div class="profile-stat">
          <div class="profile-stat-num">{{ $completedBorrowings }}</div>
          <div class="profile-stat-label">Selesai</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Informasi Pribadi &amp; Password</div></div>
    <form action="{{ route('customer.profile.update') }}" method="POST" class="card-body" style="display:flex;flex-direction:column;gap:16px">
      @csrf
      @method('PUT')

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nama Lengkap <span class="required">*</span></label>
          <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" class="form-input" value="{{ $user->email }}" readonly style="background:var(--gray-50)"/>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nomor Telepon / WhatsApp <span class="required">*</span></label>
          <input type="tel" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required/>
        </div>
        <div class="form-group">
          <label class="form-label">ID Customer</label>
          <input type="text" class="form-input" value="CST-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}" readonly style="background:var(--gray-50)"/>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Lengkap</label>

        {{-- Dropdown Wilayah Bertingkat --}}
        <div style="background:#f8faff;border:1px solid #c7d7f9;border-radius:12px;padding:14px 16px;margin-bottom:10px;">
          <div style="font-size:12px;font-weight:700;color:#0f2d6b;margin-bottom:10px;">Pilih Wilayah</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
              <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Provinsi</label>
              <select id="sel-provinsi" class="form-input form-select" onchange="loadKota()" style="font-size:13px;width:100%;">
                <option value="">-- Pilih Provinsi --</option>
              </select>
            </div>
            <div>
              <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kota / Kabupaten</label>
              <select id="sel-kota" class="form-input form-select" onchange="loadKecamatan()" style="font-size:13px;width:100%;" disabled>
                <option value="">-- Pilih Kota --</option>
              </select>
            </div>
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kecamatan</label>
            <select id="sel-kecamatan" class="form-input form-select" onchange="syncWilayah()" style="font-size:13px;width:100%;" disabled>
              <option value="">-- Pilih Kecamatan --</option>
            </select>
          </div>
        </div>

        {{-- Detail Tambahan --}}
        <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Detail Alamat (nama jalan, nomor rumah, RT/RW, dll)</label>
        <textarea name="address" id="address-detail" class="form-input form-textarea" rows="2" placeholder="Cth: Jl. Merdeka No. 12, RT 03/RW 05">{{ old('address', $user->address) }}</textarea>
        <span style="font-size:11px;color:var(--gray-500);margin-top:4px;display:block;"><em>Pilih wilayah dari dropdown di atas, lalu isi detail jalan/nomor rumah di sini.</em></span>
      </div>

      <div class="section-divider"></div>

      <div style="font-size:14px;font-weight:700;color:var(--gray-800)">Ganti Password (Opsional)</div>

      <div class="form-group">
        <label class="form-label">Password Lama</label>
        <input type="password" name="old_password" class="form-input" placeholder="Masukkan password saat ini"/>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter"/>
        </div>
        <div class="form-group">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru"/>
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-top:8px">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const WILAYAH_BASE = 'https://www.emsifa.com/api-wilayah-indonesia/api';
  let _wCache = {};
  async function wFetch(url) {
    if (_wCache[url]) return _wCache[url];
    const r = await fetch(url); const d = await r.json();
    _wCache[url] = d; return d;
  }
  function selLoad(sel, txt) { sel.innerHTML = `<option>${txt}</option>`; sel.disabled = true; }

  async function loadProvinsi() {
    const sel = document.getElementById('sel-provinsi');
    selLoad(sel, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/provinces.json`);
      sel.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      data.forEach(p => sel.innerHTML += `<option value="${p.id}">${p.name}</option>`);
      sel.disabled = false;
    } catch { sel.innerHTML = '<option value="">Gagal</option>'; sel.disabled = false; }
  }

  async function loadKota() {
    const provId = document.getElementById('sel-provinsi').value;
    const selK = document.getElementById('sel-kota');
    const selKec = document.getElementById('sel-kecamatan');
    selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>'; selKec.disabled = true;
    if (!provId) { selK.innerHTML = '<option value="">-- Pilih Kota --</option>'; selK.disabled = true; return; }
    selLoad(selK, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/regencies/${provId}.json`);
      selK.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
      data.forEach(k => selK.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      selK.disabled = false;
    } catch { selK.innerHTML = '<option value="">Gagal</option>'; selK.disabled = false; }
  }

  async function loadKecamatan() {
    const kotaId = document.getElementById('sel-kota').value;
    const selKec = document.getElementById('sel-kecamatan');
    if (!kotaId) { selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>'; selKec.disabled = true; return; }
    selLoad(selKec, 'Memuat...');
    try {
      const data = await wFetch(`${WILAYAH_BASE}/districts/${kotaId}.json`);
      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      data.forEach(k => selKec.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      selKec.disabled = false;
    } catch { selKec.innerHTML = '<option value="">Gagal</option>'; selKec.disabled = false; }
  }

  function syncWilayah() {
    const provSel = document.getElementById('sel-provinsi');
    const kotaSel = document.getElementById('sel-kota');
    const kecSel  = document.getElementById('sel-kecamatan');
    const detail  = document.getElementById('address-detail');
    const kecName  = kecSel.options[kecSel.selectedIndex]?.text || '';
    const kotaName = kotaSel.options[kotaSel.selectedIndex]?.text || '';
    const provName = provSel.options[provSel.selectedIndex]?.text || '';
    if (!kecName || kecName.startsWith('--')) return;
    const wilayah = `${kecName}, ${kotaName}, ${provName}`;
    const prev = detail.dataset.lastWilayah || '';
    const cur  = detail.value.trim();
    if (!cur || cur === prev) { detail.value = wilayah; }
    else {
      let stripped = cur.replace(prev, '').replace(/,\s*$/, '').trim();
      detail.value = stripped ? `${stripped}, ${wilayah}` : wilayah;
    }
    detail.dataset.lastWilayah = wilayah;
  }

  document.addEventListener('DOMContentLoaded', loadProvinsi);
</script>
@endpush
