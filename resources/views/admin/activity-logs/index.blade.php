@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')

@push('styles')
<style>
  .log-filter-bar {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
  }
  .log-filter-bar .filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .log-filter-bar label {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .04em;
  }
  .log-filter-bar input,
  .log-filter-bar select {
    padding: 7px 11px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    min-width: 150px;
  }
  .log-filter-bar input:focus,
  .log-filter-bar select:focus {
    border-color: #2563eb;
    background: #fff;
  }
  .log-table { width: 100%; border-collapse: collapse; }
  .log-table th {
    background: #f1f5f9;
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 10px 14px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
  }
  .log-table td {
    padding: 11px 14px;
    font-size: 13px;
    color: #374151;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
  }
  .log-table tr:hover td { background: #f8fafc; }

  .log-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
  }
  .log-badge.blue   { background: #dbeafe; color: #1d4ed8; }
  .log-badge.purple { background: #ede9fe; color: #6d28d9; }
  .log-badge.teal   { background: #ccfbf1; color: #0f766e; }
  .log-badge.orange { background: #ffedd5; color: #c2410c; }
  .log-badge.yellow { background: #fef9c3; color: #a16207; }
  .log-badge.red    { background: #fee2e2; color: #b91c1c; }
  .log-badge.gray   { background: #f1f5f9; color: #475569; }

  .log-action-chip {
    font-size: 11px;
    background: #f1f5f9;
    color: #475569;
    padding: 2px 8px;
    border-radius: 6px;
    font-family: monospace;
  }
  .log-time {
    font-size: 11.5px;
    color: #94a3b8;
    white-space: nowrap;
  }
  .log-ip {
    font-size: 11px;
    color: #94a3b8;
    font-family: monospace;
  }
  .page-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
  }
  .page-header-row h2 {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
  }
  .page-header-row p {
    font-size: 13px;
    color: #64748b;
    margin-top: 2px;
  }
</style>
@endpush

@section('content')
<div class="page-header-row">
  <div>
    <h2>🗒️ Log Aktivitas Sistem</h2>
    <p>Rekam jejak semua aksi penting yang terjadi di sistem, termasuk aktivitas admin dan customer.</p>
  </div>
  <div style="display:flex;gap:8px;align-items:center;">
    <span style="font-size:13px;color:#64748b;">Total: <strong>{{ $logs->total() }}</strong> log</span>
    <form action="{{ route('admin.activity-logs.clear-old') }}" method="POST" onsubmit="return confirmSoft(event, 'Hapus semua log lebih dari 30 hari? Tindakan ini tidak dapat dibatalkan.', 'Hapus Log Lama?', 'warning', 'Ya, Hapus Log', '#ef4444');">
      @csrf @method('DELETE')
      <input type="hidden" name="days" value="30">
      <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;font-size:12px;padding:6px 12px;border-radius:8px;cursor:pointer;">
        🗑️ Hapus Log &gt;30 Hari
      </button>
    </form>
  </div>
</div>

@if(session('success'))
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:#166534;font-size:13.5px;font-weight:500;">
    ✅ {{ session('success') }}
  </div>
@endif

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.activity-logs.index') }}" class="log-filter-bar">
  <div class="filter-group" style="flex:1;min-width:180px;">
    <label>Cari Deskripsi / Pelaku</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, aksi, deskripsi...">
  </div>
  <div class="filter-group">
    <label>Kategori Aksi</label>
    <select name="category">
      <option value="">Semua Kategori</option>
      @foreach($categories as $key => $label)
        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
  </div>
  <div class="filter-group">
    <label>Role Pelaku</label>
    <select name="causer_role">
      <option value="">Semua Role</option>
      <option value="admin" {{ request('causer_role') === 'admin' ? 'selected' : '' }}>Admin</option>
      <option value="customer" {{ request('causer_role') === 'customer' ? 'selected' : '' }}>Customer</option>
    </select>
  </div>
  <div class="filter-group">
    <label>Dari Tanggal</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}">
  </div>
  <div class="filter-group">
    <label>Sampai Tanggal</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}">
  </div>
  <button type="submit" class="btn btn-primary" style="padding:7px 18px;border-radius:8px;font-size:13px;align-self:flex-end;">
    🔍 Filter
  </button>
  @if(request()->hasAny(['search','category','causer_role','date_from','date_to']))
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-ghost btn-sm" style="align-self:flex-end;font-size:13px;padding:7px 12px;">✕ Reset</a>
  @endif
</form>

{{-- Log Table --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
  <div style="overflow-x:auto;">
    <table class="log-table">
      <thead>
        <tr>
          <th style="width:50px">#</th>
          <th style="width:140px">Waktu</th>
          <th style="width:110px">Kategori</th>
          <th style="width:160px">Aksi</th>
          <th>Deskripsi</th>
          <th style="width:130px">Pelaku</th>
          <th style="width:90px">Role</th>
          <th style="width:110px">IP Address</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          @php
            $color = match(true) {
              str_starts_with($log->action, 'auth.')      => 'blue',
              str_starts_with($log->action, 'borrowing.') => 'purple',
              str_starts_with($log->action, 'return.')    => 'teal',
              str_starts_with($log->action, 'item.')      => 'orange',
              str_starts_with($log->action, 'category.')  => 'yellow',
              str_starts_with($log->action, 'customer.')  => 'red',
              default                                      => 'gray',
            };
            $categoryLabel = match(true) {
              str_starts_with($log->action, 'auth.')      => 'Autentikasi',
              str_starts_with($log->action, 'borrowing.') => 'Peminjaman',
              str_starts_with($log->action, 'return.')    => 'Pengembalian',
              str_starts_with($log->action, 'item.')      => 'Barang',
              str_starts_with($log->action, 'category.')  => 'Kategori',
              str_starts_with($log->action, 'customer.')  => 'Customer',
              default                                      => 'Lainnya',
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
          <tr>
            <td style="color:#94a3b8;font-size:12px;">{{ $logs->firstItem() + $loop->index }}</td>
            <td>
              <div class="log-time">{{ $log->created_at->format('d/m/Y') }}</div>
              <div class="log-time" style="font-size:10.5px;">{{ $log->created_at->format('H:i:s') }}</div>
              <div class="log-time" style="font-size:10px;">{{ $log->created_at->diffForHumans() }}</div>
            </td>
            <td>
              <span class="log-badge {{ $color }}">{{ $icon }} {{ $categoryLabel }}</span>
            </td>
            <td>
              <span class="log-action-chip">{{ $log->action }}</span>
            </td>
            <td style="max-width:320px;">
              <div style="font-size:13px;color:#1e293b;line-height:1.5;">{{ $log->description }}</div>
              @if($log->properties)
                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:4px;">
                  @foreach($log->properties as $k => $v)
                    @if($v)
                      <span style="font-size:10.5px;background:#f1f5f9;border-radius:4px;padding:1px 6px;color:#64748b;font-family:monospace;">{{ $k }}: {{ $v }}</span>
                    @endif
                  @endforeach
                </div>
              @endif
            </td>
            <td>
              <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $log->causer_name ?? 'Sistem' }}</div>
            </td>
            <td>
              @if($log->causer_role === 'admin')
                <span style="font-size:11px;background:#dbeafe;color:#1d4ed8;padding:2px 8px;border-radius:6px;font-weight:600;">Admin</span>
              @elseif($log->causer_role === 'customer')
                <span style="font-size:11px;background:#f0fdf4;color:#166534;padding:2px 8px;border-radius:6px;font-weight:600;">Customer</span>
              @else
                <span style="font-size:11px;background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:6px;">—</span>
              @endif
            </td>
            <td class="log-ip">{{ $log->ip_address ?? '—' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:40px 20px;">
              <div style="font-size:36px;margin-bottom:10px;">📋</div>
              <div style="font-size:14px;color:#64748b;font-weight:500;">Belum ada log aktivitas</div>
              <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Log akan muncul setelah ada aktivitas di sistem</div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($logs->hasPages())
    <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
      {{ $logs->links() }}
    </div>
  @endif
</div>
@endsection
