@extends('layouts.customer')

@section('title', 'Dashboard Customer')

@section('content')
<!-- HERO GREETING & QUICK ACTION -->
<div style="background: linear-gradient(135deg, #0f2d6b 0%, #1e40af 100%); border-radius: 16px; padding: 28px 32px; color: white; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(15, 45, 107, 0.25);">
  <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.1; pointer-events: none;">
    <svg width="240" height="240" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
  </div>

  <div style="position: relative; z-index: 1; max-width: 680px;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 12px; backdrop-filter: blur(8px);">
      <span>Portal Peminjaman Digital</span>
      <span>•</span>
      <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>
    <h1 style="font-size: 24px; font-weight: 800; line-height: 1.3; margin-bottom: 8px;">
      Halo, {{ Auth::user()->name }}!
    </h1>
    <p style="font-size: 14px; opacity: 0.85; line-height: 1.6; margin-bottom: 20px;">
      Cari dan ajukan peminjaman aset kantor PT Nusantara Digital Express secara instan dan pantau jadwal jatuh tempo Anda.
    </p>

    <!-- Quick Search on Dashboard -->
    <form action="{{ route('customer.catalog.index') }}" method="GET" style="display: flex; gap: 10px; max-width: 540px;">
      <div style="position: relative; flex: 1;">
        <input type="text" name="search" placeholder="Cari barang (Laptop, Kamera, Proyektor...)" style="width: 100%; padding: 12px 16px 12px 42px; border-radius: 10px; border: none; font-size: 13.5px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); outline: none;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
      <button type="submit" class="btn btn-primary" style="background: #2563eb; padding: 0 20px; border-radius: 10px; font-weight: 700; white-space: nowrap; border: 1px solid rgba(255,255,255,0.2);">
        Cari Barang
      </button>
    </form>
  </div>
</div>

<!-- DUE DATE / JATUH TEMPO WARNING BANNER -->
@if(isset($dueBorrowings) && $dueBorrowings->count() > 0)
  <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.08);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; flex-shrink: 0;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
          <h3 style="font-size: 14.5px; font-weight: 700; color: #92400e; margin-bottom: 2px;">
            Peringatan Pengembalian Barang (Jatuh Tempo)
          </h3>
          <p style="font-size: 13px; color: #b45309; margin: 0;">
            Anda memiliki <strong>{{ $dueBorrowings->count() }} peminjaman</strong> yang harus segera dikembalikan atau diperpanjang.
          </p>
        </div>
      </div>
      <div style="display: flex; gap: 8px;">
        @foreach($dueBorrowings as $db)
          <a href="{{ route('customer.borrowings.show', $db) }}" class="btn btn-warning btn-sm" style="font-size: 12px; font-weight: 600; text-decoration: none;">
            {{ $db->item->name }} (s/d {{ $db->return_date->format('d M') }}) →
          </a>
        @endforeach
      </div>
    </div>
  </div>
@endif

<!-- STATS CARDS (CLICKABLE) -->
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
  <a href="{{ route('customer.borrowings.index') }}" style="text-decoration: none; color: inherit;">
    <div class="stat-card" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow)'">
      <div class="stat-card-icon stat-icon-blue">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      </div>
      <div class="stat-card-info">
        <div class="stat-card-value">{{ $activeBorrowings }}</div>
        <div class="stat-card-label">Peminjaman Aktif</div>
      </div>
    </div>
  </a>

  <a href="{{ route('customer.borrowings.index') }}" style="text-decoration: none; color: inherit;">
    <div class="stat-card" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow)'">
      <div class="stat-card-icon stat-icon-yellow">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="stat-card-info">
        <div class="stat-card-value">{{ $pendingBorrowings }}</div>
        <div class="stat-card-label">Menunggu Persetujuan</div>
      </div>
    </div>
  </a>

  <a href="{{ route('customer.borrowings.index') }}" style="text-decoration: none; color: inherit;">
    <div class="stat-card" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow)'">
      <div class="stat-card-icon stat-icon-purple">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
      </div>
      <div class="stat-card-info">
        <div class="stat-card-value">{{ $pendingVerificationBorrowings }}</div>
        <div class="stat-card-label">Proses Pengembalian</div>
      </div>
    </div>
  </a>

  <a href="{{ route('customer.borrowings.history') }}" style="text-decoration: none; color: inherit;">
    <div class="stat-card" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow)'">
      <div class="stat-card-icon stat-icon-green">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div class="stat-card-info">
        <div class="stat-card-value">{{ $completedBorrowings }}</div>
        <div class="stat-card-label">Peminjaman Selesai</div>
      </div>
    </div>
  </a>
</div>

<!-- 2 COLUMN CONTENT GRID -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
  <!-- Barang Tersedia Terbaru -->
  <div class="card">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
      <div class="card-title" style="font-size: 15px;">Barang Siap Dipinjam</div>
      <a href="{{ route('customer.catalog.index') }}" class="btn btn-ghost btn-sm" style="color: #2563eb; font-weight: 600;">Lihat Semua →</a>
    </div>
    <div class="card-body" style="display: flex; flex-direction: column; gap: 12px; padding: 16px;">
      @forelse($recentItems as $item)
        <div style="display: flex; align-items: center; gap: 14px; padding: 10px 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#93c5fd'" onmouseout="this.style.borderColor='#e2e8f0'">
          <div style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #e2e8f0;">
            @if($item->image)
              <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              <div style="width: 100%; height: 100%; background: #2563eb; display: flex; align-items: center; justify-content: center; color: white;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              </div>
            @endif
          </div>
          <div style="flex: 1; min-width: 0;">
            <div style="font-size: 13.5px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
              {{ $item->category->name }} • <span style="color: #16a34a; font-weight: 600;">Stok: {{ $item->stock }}</span>
            </div>
          </div>
          <a href="{{ route('customer.catalog.show', $item) }}" class="btn btn-outline btn-sm" style="font-size: 12px; font-weight: 600;">
            Detail &amp; Pinjam
          </a>
        </div>
      @empty
        <div style="text-align: center; color: var(--gray-500); padding: 24px;">Belum ada barang di katalog.</div>
      @endforelse
    </div>
  </div>

  <!-- Peminjaman Terakhir Saya -->
  <div class="card">
    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
      <div class="card-title" style="font-size: 15px;">Aktivitas Peminjaman Saya</div>
      <a href="{{ route('customer.borrowings.index') }}" class="btn btn-ghost btn-sm" style="color: #2563eb; font-weight: 600;">Kelola Peminjaman →</a>
    </div>
    <div class="card-body" style="display: flex; flex-direction: column; gap: 12px; padding: 16px;">
      @forelse($recentBorrowings as $pb)
        <div style="padding: 12px 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <span style="font-size: 12px; font-weight: 700; color: var(--navy);">{{ $pb->borrow_code }}</span>
            @if($pb->status == 'Menunggu')
              <span class="badge badge-warning">Menunggu</span>
            @elseif($pb->status == 'Disetujui')
              <span class="badge badge-info">Disetujui</span>
            @elseif($pb->status == 'Dipinjam')
              <span class="badge badge-purple">Dipinjam</span>
            @elseif($pb->status == 'Menunggu Verifikasi')
              <span class="badge" style="background:#f0fdfa;color:#0d9488">Menunggu Verifikasi</span>
            @elseif($pb->status == 'Selesai')
              <span class="badge badge-success">Selesai</span>
            @else
              <span class="badge badge-danger">{{ $pb->status }}</span>
            @endif
          </div>
          <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">{{ $pb->item->name ?? '-' }}</div>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 11.5px; color: #64748b;">
            <span>Jadwal: {{ $pb->borrow_date->format('d/m/Y') }} – {{ $pb->return_date->format('d/m/Y') }}</span>
            <a href="{{ route('customer.borrowings.show', $pb) }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">Lihat Status →</a>
          </div>
        </div>
      @empty
        <div style="text-align: center; color: var(--gray-500); padding: 24px;">
          <p style="margin-bottom: 8px;">Anda belum memiliki riwayat peminjaman.</p>
          <a href="{{ route('customer.catalog.index') }}" class="btn btn-primary btn-sm">Mulai Pinjam Barang</a>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
