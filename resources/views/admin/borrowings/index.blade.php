@extends('layouts.admin')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Manajemen Peminjaman</div>
    <div class="page-subtitle">Verifikasi, setujui, tolak, perpanjang, dan kelola seluruh transaksi peminjaman aset</div>
  </div>
</div>

<div class="card" style="margin-bottom:16px">
  <div class="card-body" style="padding:16px">
    <form method="GET" action="{{ route('admin.borrowings.index') }}" class="filter-row" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
      <div class="search-input-wrap">
        <span class="search-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
        <input type="text" name="search" class="form-input" placeholder="Cari kode, customer, barang..." value="{{ request('search') }}" style="padding-left:36px;max-width:240px"/>
      </div>
      <select name="status" class="form-input form-select" style="max-width:150px">
        <option value="">Semua Status</option>
        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Retur</option>
        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
      </select>
      <select name="extension" class="form-input form-select" style="max-width:160px">
        <option value="">Semua Perpanjangan</option>
        <option value="Pending" {{ request('extension') == 'Pending' ? 'selected' : '' }}>⏳ Minta Perpanjangan</option>
        <option value="Approved" {{ request('extension') == 'Approved' ? 'selected' : '' }}>✓ Perpanjangan Disetujui</option>
        <option value="Rejected" {{ request('extension') == 'Rejected' ? 'selected' : '' }}>✕ Perpanjangan Ditolak</option>
      </select>
      <input type="date" name="date" class="form-input" value="{{ request('date') }}" style="max-width:150px"/>
      <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    </form>
  </div>
</div>

<div class="card" style="overflow:hidden;">
  @push('styles')
  <style>
    .borrow-list { display: flex; flex-direction: column; }
    .borrow-item {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr auto;
      gap: 0;
      padding: 14px 20px;
      border-bottom: 1px solid var(--gray-100);
      align-items: center;
      transition: background 0.15s;
    }
    .borrow-item:last-child { border-bottom: none; }
    .borrow-item:hover { background: var(--gray-50); }
    .borrow-item-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .borrow-item-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; flex-shrink: 0; }
    .borrow-item-img-ph { width: 40px; height: 40px; border-radius: 8px; background: #e8f0fe; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; color: #0f2d6b; flex-shrink: 0; }
    .borrow-item-info { min-width: 0; }
    .borrow-item-code { font-size: 11px; font-weight: 700; color: var(--navy); font-family: monospace; letter-spacing: 0.3px; }
    .borrow-item-name { font-size: 13px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; }
    .borrow-item-sub { font-size: 11px; color: var(--gray-500); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; }
    .borrow-item-mid { min-width: 0; }
    .borrow-item-customer { font-size: 13px; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
    .borrow-item-loc { font-size: 11px; color: var(--gray-500); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
    .borrow-item-right { display: flex; flex-direction: column; gap: 4px; align-items: flex-start; min-width: 0; }
    .borrow-item-dates { font-size: 11px; color: var(--gray-500); white-space: nowrap; }
    .borrow-item-badges { display: flex; flex-wrap: wrap; gap: 4px; }
    .borrow-list-header {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr auto;
      gap: 0;
      padding: 10px 20px;
      background: var(--gray-50);
      border-bottom: 2px solid var(--gray-200);
    }
    .borrow-list-header span { font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .05em; }
    .borrow-action-col { width: 44px; display: flex; justify-content: center; }
  </style>
  @endpush

  <div class="borrow-list-header">
    <span>Barang &amp; Kode</span>
    <span>Customer &amp; Lokasi</span>
    <span>Tanggal &amp; Status</span>
    <span style="width:44px;"></span>
  </div>

  <div class="borrow-list">
    @forelse($borrowings as $b)
    <div class="borrow-item">
      {{-- COL 1: Item --}}
      <div class="borrow-item-left">
        @if($b->item && $b->item->image)
          <img src="{{ asset('storage/' . $b->item->image) }}" alt="{{ $b->item->name }}" class="borrow-item-img">
        @else
          <div class="borrow-item-img-ph">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
          </div>
        @endif
        <div class="borrow-item-info">
          <div class="borrow-item-code">{{ $b->borrow_code }}</div>
          <div class="borrow-item-name" title="{{ $b->item->name ?? '-' }}">{{ $b->item->name ?? '-' }}</div>
          <div class="borrow-item-sub">
            {{ $b->quantity }} unit
            @if($b->item && $b->item->storage_location)
              &nbsp;·&nbsp; 🏢 {{ Str::limit($b->item->storage_location, 20) }}
            @endif
          </div>
        </div>
      </div>

      {{-- COL 2: Customer & Alamat --}}
      <div class="borrow-item-mid">
        <div class="borrow-item-customer" title="{{ $b->user->name ?? '-' }}">{{ $b->user->name ?? '-' }}</div>
        @if($b->user && $b->user->address)
          <div class="borrow-item-loc" style="color:#475569;font-size:11px;margin-top:2px;" title="Domisili KTP: {{ $b->user->address }}">
            <span style="font-weight:700;color:#334155;">Rumah:</span> {{ Str::limit($b->user->address, 20) }}
          </div>
        @endif
        @if($b->location)
          <div class="borrow-item-loc" style="color:#0284c7;font-size:11px;margin-top:1px;" title="Lokasi Pemakaian: {{ $b->location }}">
            <span style="font-weight:700;color:#0369a1;">Pakai:</span> {{ Str::limit($b->location, 20) }}
            @if($b->latitude && $b->longitude)
              <a href="https://www.google.com/maps?q={{ $b->latitude }},{{ $b->longitude }}" target="_blank" style="color:#0284c7;text-decoration:none;font-weight:700;">(Maps)</a>
            @endif
          </div>
        @endif
      </div>

      {{-- COL 3: Dates + Badges --}}
      <div class="borrow-item-right">
        <div class="borrow-item-dates">
          {{ $b->borrow_date->format('d/m/Y') }} → {{ $b->return_date->format('d/m/Y') }}
        </div>
        <div class="borrow-item-badges">
          {{-- Status badge --}}
          @if($b->status == 'Menunggu') <span class="badge badge-warning" style="font-size:10px;">Menunggu</span>
          @elseif($b->status == 'Disetujui') <span class="badge badge-info" style="font-size:10px;">Disetujui</span>
          @elseif($b->status == 'Dipinjam') <span class="badge badge-purple" style="font-size:10px;">Dipinjam</span>
          @elseif($b->status == 'Menunggu Verifikasi') <span class="badge" style="background:#f0fdfa;color:#0d9488;font-size:10px;">Menunggu Retur</span>
          @elseif($b->status == 'Selesai') <span class="badge badge-success" style="font-size:10px;">Selesai</span>
          @else <span class="badge badge-danger" style="font-size:10px;">{{ $b->status }}</span>
          @endif

          {{-- Payment badge --}}
          @if($b->payment_method === 'Gratis' || $b->total_price <= 0)
            <span style="font-size:10px;color:#94a3b8;">Gratis</span>
          @elseif($b->payment_status === 'Lunas')
            <span class="badge badge-success" style="font-size:10px;">Lunas</span>
          @elseif($b->payment_status === 'Menunggu Verifikasi')
            <span class="badge badge-warning" style="font-size:10px;"><span class="badge-pulse-dot"></span> Verifikasi</span>
          @elseif($b->payment_status === 'Ditolak')
            <span class="badge badge-danger" style="font-size:10px;">Ditolak</span>
          @else
            <span class="badge" style="background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;font-size:10px;">Belum Bayar</span>
          @endif

          {{-- Extension badge --}}
          @if($b->extension_status == 'Pending')
            <span class="badge badge-warning" style="font-size:10px;">Perpanjang</span>
          @elseif($b->extension_status == 'Approved')
            <span class="badge badge-success" style="font-size:10px;">Perpanjang</span>
          @elseif($b->extension_status == 'Rejected')
            <span class="badge badge-danger" style="font-size:10px;">Perpanjang</span>
          @endif
        </div>
      </div>

      {{-- COL 4: Aksi --}}
      <div class="borrow-action-col">
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
            <button type="button" class="action-dropdown-item" onclick="openDetailModal({{ json_encode($b->load(['user', 'item.category', 'returnRecord'])) }})">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue)"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <span>Lihat Detail</span>
            </button>

            <!-- DOKUMEN JAMINAN (FOTO KTP/KK) -->
            @php $ktpImage = $b->id_card_image ?: ($b->user->id_card_image ?? null); @endphp
            @if($ktpImage)
              <button type="button" class="action-dropdown-item" onclick="viewKtpModal('{{ asset('storage/' . $ktpImage) }}', '{{ addslashes($b->user->name ?? 'Customer') }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--purple)"><rect x="2" y="5" width="20" height="14" rx="2.5"/><circle cx="8" cy="12" r="2"/><line x1="13" y1="10" x2="18" y2="10"/><line x1="13" y1="14" x2="17" y2="14"/></svg>
                <span>Foto KTP Jaminan</span>
              </button>
            @endif

            <!-- ACTION: VERIFIKASI PEMBAYARAN -->
            @if($b->payment_status === 'Menunggu Verifikasi' && $b->total_price > 0 && $b->payment_method !== 'Gratis')
              <div class="action-dropdown-divider"></div>
              @if($b->payment_proof)
                <button type="button" class="action-dropdown-item" onclick="viewPaymentProof('{{ asset('storage/' . $b->payment_proof) }}', '{{ addslashes($b->borrow_code) }}', {{ $b->id }}, '{{ number_format($b->total_price, 0, ',', '.') }}')" style="color:#f59e0b;font-weight:600">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  <span>Verifikasi Pembayaran</span>
                </button>
              @else
                <form action="{{ route('admin.borrowings.confirm-payment', $b) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Konfirmasi pembayaran LUNAS untuk {{ $b->borrow_code }}?', 'Konfirmasi Lunas?', 'question', 'Ya, Konfirmasi', '#10b981')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="action-dropdown-item" style="color:var(--green);font-weight:600">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Konfirmasi Lunas (Tunai)</span>
                  </button>
                </form>
              @endif
            @endif

            <!-- ACTION: SERAHKAN BARANG FISIK (Jika Disetujui) -->
            @if($b->status == 'Disetujui')
              <div class="action-dropdown-divider"></div>
              <form action="{{ route('admin.borrowings.release', $b) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Konfirmasi serahkan barang fisik {{ addslashes($b->item->name ?? 'Barang') }} ke {{ addslashes($b->user->name ?? 'Customer') }}?', 'Serahkan Barang?', 'question', 'Ya, Serahkan', '#2563eb')">
                @csrf
                @method('PATCH')
                <button type="submit" class="action-dropdown-item" style="color:var(--blue);font-weight:600">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                  <span>Serahkan Barang</span>
                </button>
              </form>
            @endif

            <!-- ACTION: SETUJUI / TOLAK PENGAJUAN (Jika Menunggu) -->
            @if($b->status == 'Menunggu')
              <div class="action-dropdown-divider"></div>
              <form action="{{ route('admin.borrowings.approve', $b) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Setujui pengajuan peminjaman {{ $b->borrow_code }}?', 'Setujui Peminjaman?', 'question', 'Ya, Setujui', '#10b981')">
                @csrf
                @method('PATCH')
                <button type="submit" class="action-dropdown-item" style="color:var(--green);font-weight:600">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <span>Setujui Pinjam</span>
                </button>
              </form>
              <button type="button" class="action-dropdown-item danger" onclick="openRejectModal({{ $b->id }}, '{{ $b->borrow_code }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                <span>Tolak Pinjam</span>
              </button>
            @endif

            <!-- ACTION: PERPANJANGAN TANGGAL (Jika Pending) -->
            @if($b->extension_status == 'Pending')
              <div class="action-dropdown-divider"></div>
              <form action="{{ route('admin.borrowings.approve-extension', $b) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Setujui perpanjangan tanggal s/d {{ $b->extension_date?->format('d/m/Y') }}?', 'Setujui Perpanjangan?', 'question', 'Ya, Setujui', '#10b981')">
                @csrf
                @method('PATCH')
                <button type="submit" class="action-dropdown-item" style="color:var(--green);font-weight:600">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <span>Setujui Perpanjang</span>
                </button>
              </form>
              <form action="{{ route('admin.borrowings.reject-extension', $b) }}" method="POST" style="margin:0" onsubmit="return confirmSoft(event, 'Tolak perpanjangan tanggal?', 'Tolak Perpanjangan?', 'warning', 'Ya, Tolak', '#ef4444')">
                @csrf
                @method('PATCH')
                <button type="submit" class="action-dropdown-item danger">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  <span>Tolak Perpanjang</span>
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
    @empty
    <div style="padding:32px;text-align:center;color:var(--gray-400);">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px;opacity:0.4"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      <div style="font-size:13.5px;">Tidak ada transaksi peminjaman ditemukan.</div>
    </div>
    @endforelse
  </div>

  <div style="padding: 14px 20px; border-top: 1px solid var(--gray-100);">
    {{ $borrowings->withQueryString()->links() }}
  </div>
</div>

@push('scripts')
<script>
  function formatDetailDate(dateStr) {
    if (!dateStr) return '-';
    const cleanStr = String(dateStr).split('T')[0];
    const parts = cleanStr.split('-');
    if (parts.length === 3) {
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      const monthIdx = parseInt(parts[1], 10) - 1;
      if (monthIdx >= 0 && monthIdx < 12) {
        return `${parseInt(parts[2], 10)} ${months[monthIdx]} ${parts[0]}`;
      }
      return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
  }

  function openDetailModal(b) {
    const ktpUrl = b.id_card_image ? `/storage/${b.id_card_image}` : (b.user?.id_card_image ? `/storage/${b.user.id_card_image}` : null);
    const customerName = (b.user?.name || 'Customer').replace(/'/g, "\\'");

    openModal(`
      <div class="modal-header" style="background:#0f2d6b;color:#fff;padding:16px 20px;">
        <div>
          <div class="modal-title" style="color:#fff;font-size:16px;font-weight:800;">Detail Peminjaman ${b.borrow_code}</div>
          <div style="font-size:12px;color:rgba(255,255,255,0.8);margin-top:2px;">Diajukan oleh ${b.user?.name || '-'} pada ${formatDetailDate(b.borrow_date)}</div>
        </div>
        <button class="modal-close" style="color:#fff;" onclick="closeModal()">&times;</button>
      </div>
      <div class="modal-body" style="padding:20px;background:#f8fafc;display:flex;flex-direction:column;gap:16px;">
        
        <!-- CARD 1: PROFIL PEMINJAM & DOMISILI ASLI -->
        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:12px;padding:16px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
          <div style="font-size:11px;font-weight:800;color:#2563eb;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil &amp; Domisili Identitas Peminjam
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:13px;margin-bottom:12px;">
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Nama Customer</span>
              <strong style="color:#0f172a;">${b.user?.name || '-'}</strong>
            </div>
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Kontak Telepon / WA</span>
              <strong style="color:#0f172a;">${b.user?.phone || '-'}</strong>
            </div>
          </div>
          
          <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 12px;margin-bottom:12px;">
            <div style="font-size:11px;font-weight:700;color:#1e40af;margin-bottom:2px;">Alamat Tempat Tinggal / Domisili User:</div>
            <div style="font-size:13px;color:#1e293b;line-height:1.5;font-weight:500;">${b.user?.address || 'Belum mengisi alamat domisili di profil'}</div>
          </div>

          ${ktpUrl ? `
            <div style="display:flex;align-items:center;justify-content:space-between;background:#f1f5f9;padding:10px 14px;border-radius:8px;border:1px solid #e2e8f0;flex-wrap:wrap;gap:10px;">
              <div>
                <div style="font-size:12px;color:#0f172a;font-weight:700;">Dokumen Jaminan (KTP / KK)</div>
                <div style="font-size:11px;color:#64748b;">Cocokkan dengan alamat domisili resmi di atas</div>
              </div>
              <button type="button" class="btn btn-sm" style="background:#2563eb;color:#ffffff;font-size:12px;padding:6px 14px;border-radius:6px;font-weight:700;" onclick="viewKtpModal('${ktpUrl}', '${customerName}')">
                Lihat Foto KTP Jaminan
              </button>
            </div>
          ` : `
            <div style="font-size:12px;color:#94a3b8;font-style:italic;">Dokumen KTP/KK belum diunggah</div>
          `}
        </div>

        <!-- CARD 2: ASET & LOKASI PEMAKAIAN -->
        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:12px;padding:16px;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
          <div style="font-size:11px;font-weight:800;color:#0284c7;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Aset &amp; Lokasi Pemakaian Barang
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:13px;margin-bottom:12px;">
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Nama Barang Inventaris</span>
              <strong style="color:#0f172a;">${b.item?.name || '-'}</strong> (${b.quantity} unit)
            </div>
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Asal Gudang Penyimpanan</span>
              <strong style="color:#0284c7;">${b.item?.storage_location || 'Gudang Utama Lt. 1'}</strong>
            </div>
          </div>

          <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:10px 12px;margin-bottom:12px;">
            <div style="font-size:11px;font-weight:700;color:#0369a1;margin-bottom:2px;">Tujuan / Alamat Lokasi Pemakaian:</div>
            <div style="font-size:13px;color:#0f172a;line-height:1.5;font-weight:600;">${b.location || 'Tidak mencantumkan alamat lokasi khusus'}</div>
            
            ${b.latitude && b.longitude ? `
              <div style="margin-top:8px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span style="font-size:11px;color:#0369a1;background:#e0f2fe;padding:2px 8px;border-radius:4px;font-weight:600;">
                  GPS: ${Number(b.latitude).toFixed(5)}, ${Number(b.longitude).toFixed(5)}
                </span>
                <a href="https://www.google.com/maps?q=${b.latitude},${b.longitude}" target="_blank" class="btn btn-sm" style="background:#0284c7;color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;text-decoration:none;font-weight:600;">
                  Buka Titik Peta Google Maps
                </a>
              </div>
              <div style="margin-top:10px;border-radius:8px;overflow:hidden;border:1px solid #93c5fd;height:140px;">
                <iframe width="100%" height="140" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=${b.latitude},${b.longitude}&z=15&output=embed"></iframe>
              </div>
            ` : (b.location ? `
              <div style="margin-top:8px;">
                <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(b.location)}" target="_blank" class="btn btn-sm" style="background:#0284c7;color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;text-decoration:none;font-weight:600;">
                  Cari di Google Maps
                </a>
              </div>
              <div style="margin-top:10px;border-radius:8px;overflow:hidden;border:1px solid #93c5fd;height:140px;">
                <iframe width="100%" height="140" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=${encodeURIComponent(b.location)}&z=15&output=embed"></iframe>
              </div>
            ` : '')}
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:12.5px;">
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Keperluan Pemakaian</span>
              <div style="color:#334155;font-weight:500;">${b.purpose || '-'}</div>
            </div>
            <div>
              <span style="color:#64748b;font-size:11px;display:block;">Durasi Sewa</span>
              <div style="color:#334155;font-weight:600;">${b.duration_days} Hari (${formatDetailDate(b.borrow_date)} s/d ${formatDetailDate(b.return_date)})</div>
            </div>
          </div>
        </div>

        <!-- CARD 3: STATUS BIAYA & CATATAN -->
        <div style="background:#ffffff;border:1px solid #cbd5e1;border-radius:12px;padding:14px 16px;box-shadow:0 2px 8px rgba(0,0,0,0.03);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
          <div>
            <span style="font-size:11px;color:#64748b;display:block;">Total Biaya Sewa</span>
            <strong style="font-size:16px;color:#0f172a;">Rp ${Number(b.total_price).toLocaleString('id-ID')}</strong>
          </div>
          <div>
            <span style="font-size:11px;color:#64748b;display:block;margin-bottom:2px;">Status Peminjaman</span>
            <span class="badge badge-info" style="font-size:12px;">${b.status}</span>
          </div>
        </div>

        ${b.extension_date ? `<div style="background:#fffbeb;border:1px solid #fde68a;padding:12px;border-radius:10px;font-size:12.5px;color:#92400e;"><strong>Permohonan Perpanjangan:</strong> s/d ${formatDetailDate(b.extension_date)} (${b.extension_status})<br><span style="font-size:11.5px;color:#b45309;">Alasan: ${b.extension_reason || '-'}</span></div>` : ''}
        ${b.notes ? `<div style="background:#f8fafc;border:1px solid #e2e8f0;padding:12px;border-radius:10px;font-size:12.5px;color:#475569;"><strong>Catatan Customer:</strong> ${b.notes}</div>` : ''}
        ${b.rejection_reason ? `<div style="background:#fef2f2;border:1px solid #fca5a5;padding:12px;border-radius:10px;font-size:12.5px;color:#991b1b;"><strong>Alasan Penolakan:</strong> ${b.rejection_reason}</div>` : ''}
      </div>
      
      <div class="modal-footer" style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;background:#ffffff;border-top:1px solid #e2e8f0;">
        <div>
          ${b.status === 'Menunggu' ? `
            <form action="/admin/borrowings/${b.id}/approve" method="POST" style="display:inline">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <input type="hidden" name="_method" value="PATCH"/>
              <button type="submit" class="btn btn-sm" style="background:#16a34a;color:#fff;font-weight:700;padding:8px 18px;border-radius:8px;">Setujui Pengajuan Ini</button>
            </form>
          ` : ''}
        </div>
        <button type="button" class="btn btn-secondary" onclick="closeModal()" style="padding:8px 18px;border-radius:8px;">Tutup</button>
      </div>
    `);
  }

  function viewKtpModal(url, customerName) {
    openModal(`
      <div class="modal-header" style="background:#0B1F6B;color:#fff;padding:14px 20px;">
        <div>
          <div class="modal-title" style="color:#fff;font-size:16px;">Dokumen Identitas Jaminan (KTP / KK)</div>
          <div style="font-size:12px;color:rgba(255,255,255,0.8)">Nama Peminjam: <strong>${customerName}</strong></div>
        </div>
        <button class="modal-close" style="color:#fff;" onclick="closeModal()">✕</button>
      </div>
      <div class="modal-body" style="padding:20px;text-align:center;background:#F8FAFC;">
        <div style="background:#fff;padding:10px;border-radius:12px;border:1px solid #E2E8F0;box-shadow:0 4px 16px rgba(0,0,0,0.08);display:inline-block;max-width:100%;">
          <img src="${url}" alt="Dokumen Jaminan ${customerName}" style="max-width:100%;max-height:480px;border-radius:8px;object-fit:contain;display:block;margin:0 auto;"/>
        </div>
        <div style="margin-top:14px;font-size:12.5px;color:#475569;background:#EFF6FF;border:1px solid #BFDBFE;padding:8px 14px;border-radius:8px;display:inline-block;">
          <em>Pastikan NIK, Nama Lengkap, dan Alamat sesuai dengan data peminjam sebelum menyetujui.</em>
        </div>
      </div>
      <div class="modal-footer" style="display:flex;justify-content:space-between;align-items:center;">
        <a href="${url}" download class="btn btn-ghost btn-sm" style="border:1px solid #CBD5E1;display:inline-flex;align-items:center;gap:6px;">
          Unduh Dokumen Asli
        </a>
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup Pratinjau</button>
      </div>
    `);
  }

  function openRejectModal(id, code) {
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Tolak Peminjaman ${code}</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="/admin/borrowings/${id}/reject" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="_method" value="PATCH"/>
        <div class="modal-body">
          <div class="info-alert yellow" style="margin-bottom:12px">Berikan alasan yang jelas agar customer memahami alasan penolakan.</div>
          <div class="form-group">
            <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
            <textarea name="rejection_reason" class="form-input form-textarea" placeholder="Contoh: Barang sedang dalam proses pemeliharaan berkala..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-danger">Konfirmasi Penolakan</button>
        </div>
      </form>
    `);
  }

  function viewPaymentProof(proofUrl, borrowCode, borrowingId, totalPrice) {
    openModal(`
      <div class="modal-header" style="background:#f59e0b;color:#fff;padding:14px 20px;">
        <div>
          <div class="modal-title" style="color:#fff;">Verifikasi Bukti Pembayaran</div>
          <div style="font-size:12px;color:rgba(255,255,255,0.85)">${borrowCode} — Rp ${totalPrice}</div>
        </div>
        <button class="modal-close" style="color:#fff;" onclick="closeModal()">✕</button>
      </div>
      <div class="modal-body" style="padding:20px;text-align:center;background:#f8fafc;">
        <div style="background:#fff;padding:10px;border-radius:12px;border:1px solid #e2e8f0;box-shadow:0 4px 16px rgba(0,0,0,0.08);display:inline-block;max-width:100%;">
          <img src="${proofUrl}" alt="Bukti Pembayaran" style="max-width:100%;max-height:400px;border-radius:8px;object-fit:contain;display:block;margin:0 auto;"/>
        </div>
      </div>
      <div class="modal-footer" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <form action="/admin/borrowings/${borrowingId}/confirm-payment" method="POST" style="display:inline" onsubmit="return confirmSoft(event,'Konfirmasi pembayaran LUNAS untuk ${borrowCode}?','Konfirmasi Lunas?','question','Ya, Konfirmasi','#10b981')">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="_method" value="PATCH"/>
            <button type="submit" class="btn btn-sm" style="background:#16a34a;color:#fff;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
              Konfirmasi LUNAS
            </button>
          </form>
          <button type="button" class="btn btn-sm btn-danger" style="display:inline-flex;align-items:center;gap:6px;" onclick="openRejectPaymentModal(${borrowingId}, '${borrowCode}')">
            Tolak Bukti
          </button>
        </div>
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup</button>
      </div>
    `);
  }

  function openRejectPaymentModal(id, code) {
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Tolak Bukti Pembayaran ${code}</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="/admin/borrowings/${id}/reject-payment" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="_method" value="PATCH"/>
        <div class="modal-body">
          <div class="info-alert yellow" style="margin-bottom:12px">⚠️ Berikan alasan yang jelas agar customer bisa mengunggah ulang bukti yang benar.</div>
          <div class="form-group">
            <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
            <textarea name="payment_rejection_reason" class="form-input form-textarea" placeholder="Contoh: Bukti transfer tidak terbaca / nominal tidak sesuai..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-danger">Konfirmasi Penolakan</button>
        </div>
      </form>
    `);
  }

  function toggleActionDropdown(btn, event) {
    event.stopPropagation();
    const dropdown = btn.nextElementSibling;
    const isOpen = dropdown.classList.contains('show');
    
    // Tutup dropdown lain yang terbuka
    document.querySelectorAll('.action-dropdown-menu.show').forEach(el => {
      if (el !== dropdown) el.classList.remove('show');
    });
    document.querySelectorAll('.action-dropdown-toggle.active').forEach(el => {
      if (el !== btn) el.classList.remove('active');
    });

    if (!isOpen) {
      // Periksa apakah posisi di dekat bawah viewport
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
</script>
@endpush
@endsection
