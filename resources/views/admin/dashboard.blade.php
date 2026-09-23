@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
  /* Dashboard spacing override */
  .admin-content {
    padding: 18px 22px;
  }
  .admin-content > * + * {
    margin-top: 0 !important;
  }
  
  .dashboard-welcome {
    background: linear-gradient(135deg, #0f2d6b 0%, #1a3f8f 100%);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 14px;
    color: white;
    box-shadow: 0 4px 20px rgba(15, 45, 107, 0.15);
    position: relative;
    overflow: hidden;
  }
  
  .dashboard-welcome::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    border-radius: 50%;
  }
  
  .dashboard-welcome-content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
  }
  
  .dashboard-welcome h1 {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 4px;
    line-height: 1.2;
  }
  
  .dashboard-welcome p {
    font-size: 13px;
    opacity: 0.9;
    line-height: 1.4;
  }
  
  .dashboard-time {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 10px 16px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
  }
  
  .dashboard-time-icon {
    font-size: 28px;
    line-height: 1;
  }
  
  .dashboard-time-text {
    font-size: 12px;
    opacity: 0.85;
    margin-bottom: 2px;
  }
  
  .dashboard-time-date {
    font-size: 15px;
    font-weight: 700;
    line-height: 1.2;
  }
  
  /* Stats Grid */
  .stats-grid-enhanced {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 14px;
  }
  @media (max-width: 900px) {
    .stats-grid-enhanced { grid-template-columns: repeat(2, 1fr); }
  }
  
  .stat-card-enhanced {
    background: white;
    border-radius: 12px;
    padding: 18px 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  
  .stat-card-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--accent-color);
    transform: scaleY(0);
    transition: transform 0.3s ease;
    transform-origin: top;
  }
  
  .stat-card-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border-color: var(--accent-color);
  }
  
  .stat-card-enhanced:hover::before {
    transform: scaleY(1);
  }
  
  .stat-card-enhanced.navy { --accent-color: #0f2d6b; }
  .stat-card-enhanced.green { --accent-color: #16a34a; }
  .stat-card-enhanced.blue { --accent-color: #2563eb; }
  .stat-card-enhanced.yellow { --accent-color: #d97706; }
  .stat-card-enhanced.purple { --accent-color: #7c3aed; }
  .stat-card-enhanced.teal { --accent-color: #0d9488; }
  
  .stat-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
  }
  
  .stat-card-icon-enhanced {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    flex-shrink: 0;
  }
  
  .stat-card-value-enhanced {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
  }
  
  .stat-card-label-enhanced {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 0;
    line-height: 1.4;
  }
  
  .stat-card-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    padding-top: 10px;
    margin-top: 10px;
    border-top: 1px solid #f1f5f9;
  }
  
  .stat-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 11px;
    line-height: 1.2;
  }
  
  .stat-trend.up {
    background: #f0fdf4;
    color: #16a34a;
  }
  
  .stat-trend.neutral {
    background: #f1f5f9;
    color: #64748b;
  }
  
  /* Quick Actions */
  .quick-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
    flex-wrap: wrap;
  }
  
  /* Tables */
  .tables-row {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }
  
  .card-enhanced {
    background: white;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
  }
  
  .card-enhanced:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  }
  
  .card-header-enhanced {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: #fafbfc;
  }
  
  .card-title-enhanced {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
    line-height: 1.2;
  }
  
  .card-subtitle-enhanced {
    font-size: 13px;
    color: #64748b;
    line-height: 1.4;
  }
  
  .table-wrapper {
    overflow-x: auto;
  }
  
  .table-enhanced {
    width: 100%;
    border-collapse: collapse;
  }
  
  .table-enhanced thead {
    background: #f8fafc;
  }
  
  .table-enhanced th {
    padding: 12px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    background: #f8fafc;
  }
  
  .table-enhanced td {
    padding: 14px 16px;
    font-size: 14px;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }
  
  .table-enhanced tbody tr:hover td {
    background: #f8fafc;
  }
  
  .table-enhanced tbody tr:last-child td {
    border-bottom: none;
  }
  
  .empty-state {
    text-align: center;
    padding: 60px 24px;
  }
  
  .empty-state-icon {
    font-size: 56px;
    margin-bottom: 16px;
    opacity: 0.4;
    line-height: 1;
  }
  
  .empty-state-text {
    font-size: 14px;
    color: #64748b;
    line-height: 1.5;
  }
  
  .badge-enhanced {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    line-height: 1.2;
  }
  
  .code-cell {
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-weight: 600;
    color: #0f2d6b;
    font-size: 13px;
  }
  
  .amount-cell {
    font-weight: 700;
    color: #0f172a;
    font-size: 15px;
  }
  
  /* Responsive */
  @media (max-width: 1024px) {
    .stats-grid-enhanced {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .stats-grid-enhanced {
      grid-template-columns: 1fr;
    }
    
    .dashboard-welcome {
      padding: 24px;
    }
    
    .dashboard-welcome-content {
      flex-direction: column;
      align-items: flex-start;
    }
    
    .dashboard-time {
      width: 100%;
    }
    
    .stat-card-value-enhanced {
      font-size: 28px;
    }
  }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="dashboard-welcome">
  <div class="dashboard-welcome-content">
    <div>
      <h1>Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}</h1>
      <p>Kelola sistem peminjaman barang PT Nusantara Digital Express dengan mudah dan efisien</p>
    </div>
    <div class="dashboard-time">
      <div class="dashboard-time-icon">📅</div>
      <div>
        <div class="dashboard-time-text">Hari ini</div>
        <div class="dashboard-time-date">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Grid -->
<div class="stats-grid-enhanced">
  <div class="stat-card-enhanced navy">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $totalItems }}</div>
        <div class="stat-card-label-enhanced">Total Unit Barang</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-navy">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend neutral">📊 Terdaftar di sistem</span>
    </div>
  </div>

  <div class="stat-card-enhanced green">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $availableItems }}</div>
        <div class="stat-card-label-enhanced">Unit Tersedia</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-green">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend up">✓ Siap dipinjam</span>
    </div>
  </div>

  <div class="stat-card-enhanced blue">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $borrowedItems }}</div>
        <div class="stat-card-label-enhanced">Sedang Dipinjam</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-blue">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend neutral">🔄 Unit keluar</span>
    </div>
  </div>

  <div class="stat-card-enhanced yellow">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $pendingBorrowings }}</div>
        <div class="stat-card-label-enhanced">Menunggu Verifikasi</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-yellow">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend {{ $pendingBorrowings > 0 ? 'neutral' : 'up' }}">
        {{ $pendingBorrowings > 0 ? '⚠️ Perlu tindakan' : '✓ Semua terverifikasi' }}
      </span>
    </div>
  </div>

  <div class="stat-card-enhanced purple">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $pendingReturns }}</div>
        <div class="stat-card-label-enhanced">Pengembalian Pending</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-purple">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend {{ $pendingReturns > 0 ? 'neutral' : 'up' }}">
        {{ $pendingReturns > 0 ? '🔍 Perlu pemeriksaan' : '✓ Semua terverifikasi' }}
      </span>
    </div>
  </div>

  <div class="stat-card-enhanced teal">
    <div class="stat-card-top">
      <div>
        <div class="stat-card-value-enhanced">{{ $completedBorrowings }}</div>
        <div class="stat-card-label-enhanced">Peminjaman Selesai</div>
      </div>
      <div class="stat-card-icon-enhanced stat-icon-teal">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>
    <div class="stat-card-footer">
      <span class="stat-trend neutral">📊 Total riwayat</span>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
  <a href="{{ route('admin.items.index') }}" class="btn btn-admin">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Barang Baru
  </a>
  <a href="{{ route('admin.borrowings.index') }}" class="btn btn-outline">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Kelola Peminjaman
  </a>
  <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Lihat Laporan
  </a>
</div>

<!-- ===== CHARTS SECTION ===== -->
<div style="display:grid;grid-template-columns:1fr 380px;gap:14px;margin-bottom:14px;" class="charts-row">

  <!-- Chart 1: Tren Peminjaman 6 Bulan Terakhir -->
  <div class="card-enhanced">
    <div class="card-header-enhanced">
      <div>
        <div class="card-title-enhanced">📈 Tren Peminjaman &amp; Pengembalian</div>
        <div class="card-subtitle-enhanced">6 bulan terakhir berdasarkan transaksi database</div>
      </div>
      <div style="display:flex;align-items:center;gap:12px;">
        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#2563eb;">
          <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#2563eb;"></span> Pinjam
        </span>
        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#16a34a;">
          <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#16a34a;"></span> Selesai
        </span>
      </div>
    </div>
    <div style="padding:20px 24px 16px;">
      <canvas id="trendChart" height="90"></canvas>
    </div>
  </div>

  <!-- Chart 2: Distribusi Status Peminjaman (Donut) -->
  <div class="card-enhanced">
    <div class="card-header-enhanced">
      <div>
        <div class="card-title-enhanced">🍩 Status Transaksi</div>
        <div class="card-subtitle-enhanced">Distribusi status seluruh peminjaman</div>
      </div>
    </div>
    <div style="padding:16px 24px;display:flex;flex-direction:column;align-items:center;gap:14px;">
      <div style="position:relative;width:160px;height:160px;">
        <canvas id="statusChart" width="160" height="160"></canvas>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
          <div style="font-size:26px;font-weight:800;color:#0f172a;">{{ $totalBorrowingsCount }}</div>
          <div style="font-size:10.5px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">Total</div>
        </div>
      </div>
      <!-- Legend -->
      <div style="width:100%;display:flex;flex-direction:column;gap:6px;">
        @php
          $legendItems = [
            ['label'=>'Selesai','color'=>'#16a34a','key'=>'Selesai'],
            ['label'=>'Dipinjam','color'=>'#2563eb','key'=>'Dipinjam'],
            ['label'=>'Disetujui','color'=>'#0d9488','key'=>'Disetujui'],
            ['label'=>'Menunggu','color'=>'#d97706','key'=>'Menunggu'],
            ['label'=>'Ditolak','color'=>'#dc2626','key'=>'Ditolak'],
          ];
        @endphp
        @foreach($legendItems as $item)
          @php $cnt = $statusCounts[$item['key']] ?? 0; @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;font-size:12.5px;">
            <span style="display:flex;align-items:center;gap:8px;">
              <span style="width:10px;height:10px;border-radius:50%;background:{{ $item['color'] }};flex-shrink:0;display:inline-block;"></span>
              <span style="color:#475569;font-weight:500;">{{ $item['label'] }}</span>
            </span>
            <span style="font-weight:700;color:#0f172a;">{{ $cnt }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>

<!-- Data Tables -->
<div class="tables-row">
  <!-- Peminjaman Terbaru -->
  <div class="card-enhanced">
    <div class="card-header-enhanced">
      <div>
        <div class="card-title-enhanced">📋 Peminjaman Terbaru</div>
        <div class="card-subtitle-enhanced">Transaksi peminjaman terbaru dari database</div>
      </div>
      <a href="{{ route('admin.borrowings.index') }}" class="btn btn-ghost btn-sm">
        Lihat Semua →
      </a>
    </div>
    <div class="table-wrapper">
      <table class="table-enhanced">
        <thead>
          <tr>
            <th style="width:140px">Kode Transaksi</th>
            <th>Customer</th>
            <th>Barang</th>
            <th style="width:120px">Tgl. Pinjam</th>
            <th style="width:130px">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentBorrowings as $b)
            <tr>
              <td><span class="code-cell">{{ $b->borrow_code }}</span></td>
              <td>{{ $b->user->name ?? '-' }}</td>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  @if($b->item && $b->item->image)
                    <img src="{{ asset('storage/' . $b->item->image) }}" alt="{{ $b->item->name }}" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;flex-shrink:0;">
                  @else
                    <div style="width:36px;height:36px;border-radius:6px;background:#e8f0fe;border:1px solid #cbd5e1;display:flex;align-items:center;justify-content:center;color:#0f2d6b;flex-shrink:0;">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                  @endif
                  <span style="font-weight:500;">{{ $b->item->name ?? '-' }}</span>
                </div>
              </td>
              <td style="white-space:nowrap;color:#64748b;font-size:13px">{{ $b->borrow_date->format('d M Y') }}</td>
              <td>
                @if($b->status == 'Menunggu')
                  <span class="badge-enhanced badge-warning">⏳ Menunggu</span>
                @elseif($b->status == 'Disetujui')
                  <span class="badge-enhanced badge-info">✓ Disetujui</span>
                @elseif($b->status == 'Dipinjam')
                  <span class="badge-enhanced badge-purple">🔄 Dipinjam</span>
                @elseif($b->status == 'Selesai')
                  <span class="badge-enhanced badge-success">✓ Selesai</span>
                @else
                  <span class="badge-enhanced badge-danger">✕ {{ $b->status }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">
                <div class="empty-state">
                  <div class="empty-state-icon">📭</div>
                  <div class="empty-state-text">Belum ada data peminjaman di database</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pengembalian Pending -->
  <div class="card-enhanced">
    <div class="card-header-enhanced">
      <div>
        <div class="card-title-enhanced">🔍 Pengembalian Menunggu Verifikasi</div>
        <div class="card-subtitle-enhanced">Barang yang dikembalikan dan perlu diperiksa kondisinya</div>
      </div>
      <a href="{{ route('admin.returns.index') }}" class="btn btn-ghost btn-sm">
        Lihat Semua →
      </a>
    </div>
    <div class="table-wrapper">
      <table class="table-enhanced">
        <thead>
          <tr>
            <th style="width:140px">Kode Pinjam</th>
            <th>Customer</th>
            <th>Barang</th>
            <th style="width:120px">Tgl. Kembali</th>
            <th style="width:110px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendingReturnsList as $r)
            <tr>
              <td><span class="code-cell">{{ $r->borrowing->borrow_code }}</span></td>
              <td>{{ $r->borrowing->user->name ?? '-' }}</td>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  @if($r->borrowing->item && $r->borrowing->item->image)
                    <img src="{{ asset('storage/' . $r->borrowing->item->image) }}" alt="{{ $r->borrowing->item->name }}" style="width:36px;height:36px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;flex-shrink:0;">
                  @else
                    <div style="width:36px;height:36px;border-radius:6px;background:#e8f0fe;border:1px solid #cbd5e1;display:flex;align-items:center;justify-content:center;color:#0f2d6b;flex-shrink:0;">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                  @endif
                  <span style="font-weight:500;">{{ $r->borrowing->item->name ?? '-' }}</span>
                </div>
              </td>
              <td style="white-space:nowrap;color:#64748b;font-size:13px">{{ $r->return_date->format('d M Y') }}</td>
              <td>
                <a href="{{ route('admin.returns.index') }}" class="btn btn-sm btn-primary" style="white-space:nowrap;font-size:12px;padding:6px 10px">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  Verifikasi
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">
                <div class="empty-state">
                  <div class="empty-state-icon">✅</div>
                  <div class="empty-state-text">Tidak ada pengembalian yang menunggu verifikasi</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="card-enhanced" style="margin-bottom:14px;">
  <div class="card-header-enhanced">
    <div>
      <div class="card-title-enhanced">🗒️ Aktivitas Terbaru</div>
      <div class="card-subtitle-enhanced">8 aksi terakhir yang tercatat di sistem</div>
    </div>
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
  </div>
  <div style="padding:0 4px 4px;">
    @forelse($recentLogs as $log)
      @php
        $color = match(true) {
          str_starts_with($log->action, 'auth.')      => '#dbeafe',
          str_starts_with($log->action, 'borrowing.') => '#ede9fe',
          str_starts_with($log->action, 'return.')    => '#ccfbf1',
          str_starts_with($log->action, 'item.')      => '#ffedd5',
          str_starts_with($log->action, 'category.')  => '#fef9c3',
          str_starts_with($log->action, 'customer.')  => '#fee2e2',
          default                                      => '#f1f5f9',
        };
        $textColor = match(true) {
          str_starts_with($log->action, 'auth.')      => '#1d4ed8',
          str_starts_with($log->action, 'borrowing.') => '#6d28d9',
          str_starts_with($log->action, 'return.')    => '#0f766e',
          str_starts_with($log->action, 'item.')      => '#c2410c',
          str_starts_with($log->action, 'category.')  => '#a16207',
          str_starts_with($log->action, 'customer.')  => '#b91c1c',
          default                                      => '#475569',
        };
        $icon = match(true) {
          str_starts_with($log->action, 'auth.')      => '🔐',
          str_starts_with($log->action, 'borrowing.') => '📋',
          str_starts_with($log->action, 'return.')    => '📦',
          str_starts_with($log->action, 'item.')      => '🏷️',
          str_starts_with($log->action, 'category.')  => '🗂️',
          str_starts_with($log->action, 'customer.')  => '👤',
          default                                      => '📌',
        };
      @endphp
      <div style="display:flex;align-items:flex-start;gap:12px;padding:10px 16px;border-bottom:1px solid #f1f5f9;">
        <div style="width:32px;height:32px;border-radius:8px;background:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">{{ $icon }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:13px;color:#1e293b;font-weight:500;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->description }}</div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
            <span style="background:{{ $color }};color:{{ $textColor }};padding:1px 7px;border-radius:5px;font-weight:600;font-size:10.5px;">{{ $log->action }}</span>
            &nbsp;·&nbsp;{{ $log->causer_name ?? 'Sistem' }}
            &nbsp;·&nbsp;{{ $log->created_at->diffForHumans() }}
          </div>
        </div>
      </div>
    @empty
      <div style="text-align:center;padding:28px;color:#94a3b8;font-size:13px;">Belum ada log aktivitas. Log akan muncul setelah ada aksi di sistem.</div>
    @endforelse
  </div>
</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ── 1. Tren Chart (Line) ──────────────────────────────────
  const trendCtx = document.getElementById('trendChart').getContext('2d');

  const gradient1 = trendCtx.createLinearGradient(0, 0, 0, 220);
  gradient1.addColorStop(0, 'rgba(37,99,235,0.20)');
  gradient1.addColorStop(1, 'rgba(37,99,235,0)');

  const gradient2 = trendCtx.createLinearGradient(0, 0, 0, 220);
  gradient2.addColorStop(0, 'rgba(22,163,74,0.18)');
  gradient2.addColorStop(1, 'rgba(22,163,74,0)');

  new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: @json($chartMonths),
      datasets: [
        {
          label: 'Peminjaman',
          data: @json($chartBorrowData),
          borderColor: '#2563eb',
          backgroundColor: gradient1,
          borderWidth: 2.5,
          pointRadius: 4,
          pointBackgroundColor: '#2563eb',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          tension: 0.4,
          fill: true,
        },
        {
          label: 'Pengembalian Selesai',
          data: @json($chartReturnData),
          borderColor: '#16a34a',
          backgroundColor: gradient2,
          borderWidth: 2.5,
          pointRadius: 4,
          pointBackgroundColor: '#16a34a',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          tension: 0.4,
          fill: true,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#94a3b8',
          bodyColor: '#f1f5f9',
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} transaksi`
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: '#94a3b8', font: { size: 12 } },
          border: { display: false }
        },
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
          ticks: {
            color: '#94a3b8',
            font: { size: 11 },
            stepSize: 1,
            precision: 0
          },
          border: { display: false }
        }
      }
    }
  });

  // ── 2. Status Donut Chart ────────────────────────────────
  const statusCtx = document.getElementById('statusChart').getContext('2d');
  const statusCounts = @json($statusCounts);

  const labels = ['Selesai', 'Dipinjam', 'Disetujui', 'Menunggu', 'Ditolak'];
  const colors = ['#16a34a', '#2563eb', '#0d9488', '#d97706', '#dc2626'];
  const data   = labels.map(l => statusCounts[l] ?? 0);
  const total  = data.reduce((a, b) => a + b, 0);

  new Chart(statusCtx, {
    type: 'doughnut',
    data: {
      labels,
      datasets: [{
        data,
        backgroundColor: colors,
        borderColor: '#fff',
        borderWidth: 3,
        hoverBorderWidth: 0,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: false,
      cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#94a3b8',
          bodyColor: '#f1f5f9',
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: ctx => {
              const pct = total ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
              return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
            }
          }
        }
      }
    }
  });

});
</script>
@endpush
@endsection
