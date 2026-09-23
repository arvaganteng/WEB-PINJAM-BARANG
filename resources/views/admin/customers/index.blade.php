@extends('layouts.admin')

@section('title', 'Data Customer')

@section('content')
<div class="page-header-row">
  <div>
    <h2>👥 Data Customer</h2>
    <p>Kelola data akun customer, status akses, dan dokumen identitas peminjam.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="openCreateModal()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    <span>Tambah Customer Baru</span>
  </button>
</div>

<div class="card" style="margin-bottom:16px">
  <div class="card-body" style="padding:16px">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="filter-row">
      <div class="search-input-wrap">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="search" class="form-input" placeholder="Cari nama, email, atau no telp..." value="{{ request('search') }}" style="padding-left:36px;max-width:280px"/>
      </div>
      <select name="status" class="form-input form-select" style="max-width:150px">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
      @if(request('search') || request('status'))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost btn-sm" style="color:var(--gray-600)">Reset Filter</a>
      @endif
    </form>
  </div>
</div>

<div class="card">
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th style="width:45px;text-align:center;">No</th>
          <th>ID</th>
          <th>Nama Customer</th>
          <th>Email</th>
          <th>No. Telepon</th>
          <th>Total Pinjam</th>
          <th>Status</th>
          <th style="text-align:center;width:70px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $c)
          <tr>
            <td style="text-align:center;font-weight:600;color:var(--gray-500)">
              {{ $customers->firstItem() + $loop->index }}
            </td>
            <td style="font-weight:600;color:var(--navy)">CST-{{ str_pad($c->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <div class="user-avatar-sm cust-avatar" style="font-size:11px">{{ substr($c->name, 0, 1) }}</div>
                <div>
                  <div style="font-weight:600;color:var(--navy)">{{ $c->name }}</div>
                  @if($c->id_card_image)
                    <span style="font-size:10px;color:#16a34a;background:#f0fdf4;padding:1px 6px;border-radius:4px;border:1px solid #bbf7d0;">✓ KTP Terlampir</span>
                  @endif
                </div>
              </div>
            </td>
            <td style="color:var(--gray-600)">{{ $c->email }}</td>
            <td>{{ $c->phone ?? '-' }}</td>
            <td><span style="font-weight:700;color:var(--blue)">{{ $c->borrowings_count }}</span> transaksi</td>
            <td>
              @if($c->status == 'aktif')
                <span class="badge badge-success">Aktif</span>
              @else
                <span class="badge badge-gray">Nonaktif</span>
              @endif
            </td>
            <td style="text-align:center">
              <div class="action-dropdown">
                <button type="button" class="action-dropdown-toggle" onclick="toggleActionDropdown(this, event)" title="Menu Aksi">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="5" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="19" r="1.5" fill="currentColor"/>
                  </svg>
                </button>
                <div class="action-dropdown-menu">
                  <!-- LIHAT DETAIL -->
                  <button type="button" class="action-dropdown-item" onclick="openDetailModal({{ json_encode($c) }})">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue)"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>Lihat Detail</span>
                  </button>

                  <!-- EDIT CUSTOMER -->
                  <button type="button" class="action-dropdown-item" onclick="openEditModal({{ json_encode($c) }})">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--yellow)"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>Edit Data</span>
                  </button>

                  <!-- TOGGLE STATUS -->
                  <form action="{{ route('admin.customers.toggle-status', $c) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Apakah Anda yakin ingin {{ $c->status == 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }} customer {{ addslashes($c->name) }}?', 'Ubah Status Customer', 'question', 'Ya, Ubah', '{{ $c->status == 'aktif' ? '#ef4444' : '#10b981' }}')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="action-dropdown-item">
                      @if($c->status == 'aktif')
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#ef4444"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        <span>Nonaktifkan Akun</span>
                      @else
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#10b981"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>Aktifkan Akun</span>
                      @endif
                    </button>
                  </form>

                  <div class="action-dropdown-divider"></div>

                  <!-- HAPUS CUSTOMER -->
                  <form action="{{ route('admin.customers.destroy', $c) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Hapus akun customer \'{{ addslashes($c->name) }}\'? Tindakan ini tidak dapat dibatalkan.', 'Hapus Customer?', 'warning', 'Ya, Hapus', '#ef4444')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-dropdown-item danger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                      <span>Hapus Customer</span>
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center;color:var(--gray-500);padding:24px">Tidak ada data customer ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:16px;">
    {{ $customers->withQueryString()->links() }}
  </div>
</div>

@push('scripts')
<script>
  function openCreateModal() {
    openModal(`
      <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <div class="modal-title">👤 Tambah Customer Baru</div>
          <button type="button" class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" placeholder="Contoh: Ahmad Budiman" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Email <span class="required">*</span></label>
            <input type="email" name="email" class="form-input" placeholder="Contoh: ahmad@gmail.com" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Password Akun <span class="required">*</span></label>
            <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6"/>
          </div>
          <div class="form-group">
            <label class="form-label">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-input" placeholder="Contoh: 081234567890"/>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Domisili</label>
            <div style="background:#f8faff;border:1px solid #c7d7f9;border-radius:12px;padding:14px 16px;margin-bottom:8px;">
              <div style="font-size:12px;font-weight:700;color:#0f2d6b;margin-bottom:10px;">🗺️ Pilih Wilayah</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
                <div>
                  <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Provinsi</label>
                  <select id="add-prov" class="form-input form-select" onchange="loadKotaAdd()" style="font-size:13px;width:100%;">
                    <option value="">-- Pilih Provinsi --</option>
                  </select>
                </div>
                <div>
                  <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kota / Kabupaten</label>
                  <select id="add-kota" class="form-input form-select" onchange="loadKecAdd()" style="font-size:13px;width:100%;" disabled>
                    <option value="">-- Pilih Kota --</option>
                  </select>
                </div>
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kecamatan</label>
                <select id="add-kec" class="form-input form-select" onchange="syncAddWilayah()" style="font-size:13px;width:100%;" disabled>
                  <option value="">-- Pilih Kecamatan --</option>
                </select>
              </div>
            </div>
            <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Detail Alamat</label>
            <textarea name="address" id="add-address-detail" class="form-input form-textarea" rows="2" placeholder="Cth: Jl. Merdeka No. 12, RT 03/RW 05"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Status Akun <span class="required">*</span></label>
            <select name="status" class="form-input form-select" required>
              <option value="aktif" selected>Aktif</option>
              <option value="nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Customer</button>
        </div>
      </form>
    `);
  }

  function openEditModal(c) {
    openModal(`
      <form action="/admin/customers/${c.id}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <div class="modal-title">✏️ Edit Data Customer</div>
          <button type="button" class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="${c.name || ''}" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Email <span class="required">*</span></label>
            <input type="email" name="email" class="form-input" value="${c.email || ''}" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Password Baru <span style="font-weight:400;color:#64748b">(Kosongkan jika tidak ingin merubah)</span></label>
            <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" minlength="6"/>
          </div>
          <div class="form-group">
            <label class="form-label">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-input" value="${c.phone || ''}"/>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Domisili</label>
            <div style="background:#f8faff;border:1px solid #c7d7f9;border-radius:12px;padding:14px 16px;margin-bottom:8px;">
              <div style="font-size:12px;font-weight:700;color:#0f2d6b;margin-bottom:10px;">🗺️ Pilih Wilayah</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
                <div>
                  <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Provinsi</label>
                  <select id="edit-prov" class="form-input form-select" onchange="loadKotaEdit()" style="font-size:13px;width:100%;">
                    <option value="">-- Pilih Provinsi --</option>
                  </select>
                </div>
                <div>
                  <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kota / Kabupaten</label>
                  <select id="edit-kota" class="form-input form-select" onchange="loadKecEdit()" style="font-size:13px;width:100%;" disabled>
                    <option value="">-- Pilih Kota --</option>
                  </select>
                </div>
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Kecamatan</label>
                <select id="edit-kec" class="form-input form-select" onchange="syncEditWilayah()" style="font-size:13px;width:100%;" disabled>
                  <option value="">-- Pilih Kecamatan --</option>
                </select>
              </div>
            </div>
            <label style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;display:block;">Detail Alamat</label>
            <textarea name="address" id="edit-address-detail" class="form-input form-textarea" rows="2" placeholder="Cth: Jl. Merdeka No. 12, RT 03/RW 05">${c.address || ''}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Status Akun <span class="required">*</span></label>
            <select name="status" class="form-input form-select" required>
              <option value="aktif" ${c.status === 'aktif' ? 'selected' : ''}>Aktif</option>
              <option value="nonaktif" ${c.status === 'nonaktif' ? 'selected' : ''}>Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    `);
  }

  function openDetailModal(c) {
    const ktpUrl = c.id_card_image ? `/storage/${c.id_card_image}` : null;
    openModal(`
      <div class="modal-header">
        <div>
          <div class="modal-title">Profil Customer: ${c.name}</div>
          <div style="font-size:12px;color:var(--gray-500)">ID: CST-${String(c.id).padStart(3, '0')}</div>
        </div>
        <button type="button" class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <div class="modal-body">
        <div class="detail-row"><div class="detail-key">Nama Lengkap</div><div class="detail-val" style="font-weight:700;color:var(--navy)">${c.name}</div></div>
        <div class="detail-row"><div class="detail-key">Email</div><div class="detail-val">${c.email}</div></div>
        <div class="detail-row"><div class="detail-key">No. Telepon</div><div class="detail-val">${c.phone || '-'}</div></div>
        <div class="detail-row"><div class="detail-key">Alamat Domisili</div><div class="detail-val">${c.address || '-'}</div></div>
        <div class="detail-row"><div class="detail-key">Total Peminjaman</div><div class="detail-val"><span class="badge badge-info">${c.borrowings_count || 0} transaksi</span></div></div>
        <div class="detail-row"><div class="detail-key">Status Akun</div><div class="detail-val"><span class="badge ${c.status === 'aktif' ? 'badge-success' : 'badge-gray'}">${c.status}</span></div></div>
        
        <!-- Dokumen Identitas KTP -->
        ${ktpUrl ? `
          <div class="detail-row" style="align-items:flex-start;background:#F8FAFC;padding:12px;border-radius:10px;border:1px solid #E2E8F0;margin-top:12px;">
            <div class="detail-key" style="font-weight:700;color:#0F172A;">🪪 Dokumen Identitas</div>
            <div class="detail-val">
              <button type="button" class="btn btn-sm" style="background:#0B1F6B;color:#ffffff;display:inline-flex;align-items:center;gap:6px;font-size:12px;padding:6px 12px;border-radius:6px;cursor:pointer;" onclick="previewKtpSoft('${ktpUrl}', '🪪 KTP Customer: ${c.name}')">
                🔍 Lihat Foto KTP/KK Jaminan
              </button>
            </div>
          </div>
        ` : `
          <div class="detail-row">
            <div class="detail-key">Dokumen Identitas</div>
            <div class="detail-val" style="color:#94A3B8;font-style:italic;">Belum melampirkan KTP/KK</div>
          </div>
        `}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup</button>
      </div>
    `);
  }

  function toggleActionDropdown(btn, event) {
    event.stopPropagation();
    const dropdown = btn.nextElementSibling;
    const isOpen = dropdown.classList.contains('show');
    
    document.querySelectorAll('.action-dropdown-menu.show').forEach(el => {
      if (el !== dropdown) el.classList.remove('show');
    });
    document.querySelectorAll('.action-dropdown-toggle.active').forEach(el => {
      if (el !== btn) el.classList.remove('active');
    });

    if (!isOpen) {
      const rect = btn.getBoundingClientRect();
      const spaceBelow = window.innerHeight - rect.bottom;
      if (spaceBelow < 220 && rect.top > 220) {
        dropdown.style.top = 'auto';
        dropdown.style.bottom = 'calc(100% + 5px)';
      } else {
        dropdown.style.top = 'calc(100% + 5px)';
        dropdown.style.bottom = 'auto';
      }
      dropdown.classList.add('show');
      btn.classList.add('active');
    } else {
      dropdown.classList.remove('show');
      btn.classList.remove('active');
    }
  }

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
      document.querySelectorAll('.action-dropdown-menu.show').forEach(el => el.classList.remove('show'));
      document.querySelectorAll('.action-dropdown-toggle.active').forEach(el => el.classList.remove('active'));
    }
  });

  // ===== WILAYAH INDONESIA =====
  const WB = 'https://www.emsifa.com/api-wilayah-indonesia/api';
  let _wc = {};
  async function wF(url) {
    if (_wc[url]) return _wc[url];
    const r = await fetch(url); const d = await r.json();
    _wc[url] = d; return d;
  }
  function selLd(id, txt) { const s = document.getElementById(id); if(s){s.innerHTML=`<option>${txt}</option>`;s.disabled=true;} }

  // --- TAMBAH ---
  async function initAddWilayah() {
    selLd('add-prov','Memuat...');
    try {
      const data = await wF(`${WB}/provinces.json`);
      const s = document.getElementById('add-prov');
      if(!s) return;
      s.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      data.forEach(p => s.innerHTML += `<option value="${p.id}">${p.name}</option>`);
      s.disabled = false;
    } catch { const s=document.getElementById('add-prov'); if(s){s.innerHTML='<option value="">Gagal</option>';s.disabled=false;} }
  }
  async function loadKotaAdd() {
    const provId = document.getElementById('add-prov')?.value;
    const sK = document.getElementById('add-kota'); const sKec = document.getElementById('add-kec');
    if(sKec){sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';sKec.disabled=true;}
    if(!provId){if(sK){sK.innerHTML='<option value="">-- Pilih Kota --</option>';sK.disabled=true;} return;}
    selLd('add-kota','Memuat...');
    try {
      const data = await wF(`${WB}/regencies/${provId}.json`);
      if(!sK) return;
      sK.innerHTML='<option value="">-- Pilih Kota/Kabupaten --</option>';
      data.forEach(k => sK.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      sK.disabled=false;
    } catch { if(sK){sK.innerHTML='<option value="">Gagal</option>';sK.disabled=false;} }
  }
  async function loadKecAdd() {
    const kotaId = document.getElementById('add-kota')?.value;
    const sKec = document.getElementById('add-kec');
    if(!kotaId){if(sKec){sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';sKec.disabled=true;} return;}
    selLd('add-kec','Memuat...');
    try {
      const data = await wF(`${WB}/districts/${kotaId}.json`);
      if(!sKec) return;
      sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';
      data.forEach(k => sKec.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      sKec.disabled=false;
    } catch { if(sKec){sKec.innerHTML='<option value="">Gagal</option>';sKec.disabled=false;} }
  }
  function syncAddWilayah() {
    const pS=document.getElementById('add-prov'), kS=document.getElementById('add-kota'), kcS=document.getElementById('add-kec'), det=document.getElementById('add-address-detail');
    if(!kcS||!det) return;
    const kn=kcS.options[kcS.selectedIndex]?.text||'', kotn=kS?.options[kS.selectedIndex]?.text||'', pn=pS?.options[pS.selectedIndex]?.text||'';
    if(!kn||kn.startsWith('--')) return;
    const w=`${kn}, ${kotn}, ${pn}`;
    const prev=det.dataset.lw||'', cur=det.value.trim();
    det.value = (!cur||cur===prev) ? w : (cur.replace(prev,'').replace(/,\s*$/,'').trim() ? cur.replace(prev,'').replace(/,\s*$/,'').trim()+', '+w : w);
    det.dataset.lw=w;
  }

  // --- EDIT ---
  async function initEditWilayah() {
    selLd('edit-prov','Memuat...');
    try {
      const data = await wF(`${WB}/provinces.json`);
      const s = document.getElementById('edit-prov');
      if(!s) return;
      s.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      data.forEach(p => s.innerHTML += `<option value="${p.id}">${p.name}</option>`);
      s.disabled = false;
    } catch { const s=document.getElementById('edit-prov'); if(s){s.innerHTML='<option value="">Gagal</option>';s.disabled=false;} }
  }
  async function loadKotaEdit() {
    const provId = document.getElementById('edit-prov')?.value;
    const sK = document.getElementById('edit-kota'); const sKec = document.getElementById('edit-kec');
    if(sKec){sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';sKec.disabled=true;}
    if(!provId){if(sK){sK.innerHTML='<option value="">-- Pilih Kota --</option>';sK.disabled=true;} return;}
    selLd('edit-kota','Memuat...');
    try {
      const data = await wF(`${WB}/regencies/${provId}.json`);
      if(!sK) return;
      sK.innerHTML='<option value="">-- Pilih Kota/Kabupaten --</option>';
      data.forEach(k => sK.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      sK.disabled=false;
    } catch { if(sK){sK.innerHTML='<option value="">Gagal</option>';sK.disabled=false;} }
  }
  async function loadKecEdit() {
    const kotaId = document.getElementById('edit-kota')?.value;
    const sKec = document.getElementById('edit-kec');
    if(!kotaId){if(sKec){sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';sKec.disabled=true;} return;}
    selLd('edit-kec','Memuat...');
    try {
      const data = await wF(`${WB}/districts/${kotaId}.json`);
      if(!sKec) return;
      sKec.innerHTML='<option value="">-- Pilih Kecamatan --</option>';
      data.forEach(k => sKec.innerHTML += `<option value="${k.id}">${k.name}</option>`);
      sKec.disabled=false;
    } catch { if(sKec){sKec.innerHTML='<option value="">Gagal</option>';sKec.disabled=false;} }
  }
  function syncEditWilayah() {
    const pS=document.getElementById('edit-prov'), kS=document.getElementById('edit-kota'), kcS=document.getElementById('edit-kec'), det=document.getElementById('edit-address-detail');
    if(!kcS||!det) return;
    const kn=kcS.options[kcS.selectedIndex]?.text||'', kotn=kS?.options[kS.selectedIndex]?.text||'', pn=pS?.options[pS.selectedIndex]?.text||'';
    if(!kn||kn.startsWith('--')) return;
    const w=`${kn}, ${kotn}, ${pn}`;
    const prev=det.dataset.lw||'', cur=det.value.trim();
    det.value = (!cur||cur===prev) ? w : (cur.replace(prev,'').replace(/,\s*$/,'').trim() ? cur.replace(prev,'').replace(/,\s*$/,'').trim()+', '+w : w);
    det.dataset.lw=w;
  }

  // Panggil init wilayah setelah modal terbuka
  const _origOpenModal = typeof openModal === 'function' ? openModal : null;
  document.addEventListener('modal-opened', function() {
    if (document.getElementById('add-prov')) initAddWilayah();
    if (document.getElementById('edit-prov')) initEditWilayah();
  });
  // Fallback: observe DOM changes
  const _mo = new MutationObserver(() => {
    if (document.getElementById('add-prov') && !document.getElementById('add-prov').dataset.init) {
      document.getElementById('add-prov').dataset.init = '1';
      initAddWilayah();
    }
    if (document.getElementById('edit-prov') && !document.getElementById('edit-prov').dataset.init) {
      document.getElementById('edit-prov').dataset.init = '1';
      initEditWilayah();
    }
  });
  _mo.observe(document.body, { childList: true, subtree: true });
</script>
@endpush
@endsection
