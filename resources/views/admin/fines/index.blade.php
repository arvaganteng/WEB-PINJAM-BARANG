@extends('layouts.admin')

@section('title', 'Kelola Denda')

@push('styles')
<style>
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .stat-green { background: #dcfce7; color: #15803d; }
  .stat-yellow { background: #fef3c7; color: #b45309; }
  .stat-red { background: #fee2e2; color: #b91c1c; }

  .stat-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 4px;
  }

  .stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
  }

  .stat-subtext {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 2px;
  }

  .filter-bar {
    background: #ffffff;
    border-radius: 16px;
    padding: 16px 20px;
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .status-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .status-tab {
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
  }

  .status-tab:hover {
    background: #f1f5f9;
    color: #0f172a;
  }

  .status-tab.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
  }

  .search-form {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .search-input {
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    outline: none;
    width: 260px;
    transition: border-color 0.2s;
  }

  .search-input:focus {
    border-color: #2563eb;
  }

  .table-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
  }

  .custom-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 13.5px;
  }

  .custom-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    padding: 14px 18px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 12.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .custom-table td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: middle;
  }

  .custom-table tr:last-child td {
    border-bottom: none;
  }

  .custom-table tr:hover td {
    background: #f8fafc;
  }

  .badge-soft {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
  }

  .badge-soft-success { background: #dcfce7; color: #15803d; }
  .badge-soft-warning { background: #fef3c7; color: #b45309; }
  .badge-soft-danger { background: #fee2e2; color: #b91c1c; }
  .badge-soft-info { background: #e0f2fe; color: #0369a1; }

  .btn-confirm-fine {
    background: #16a34a;
    color: #ffffff;
    border: none;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 6px rgba(22, 163, 74, 0.2);
  }

  .btn-confirm-fine:hover {
    background: #15803d;
    transform: translateY(-1px);
  }

  .btn-proof-preview {
    background: #f1f5f9;
    color: #2563eb;
    border: 1px solid #cbd5e1;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .btn-proof-preview:hover {
    background: #e2e8f0;
    color: #1d4ed8;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">💰 Kelola Denda Customer</div>
    <div class="page-subtitle">Pantau penerimaan denda, konfirmasi pembayaran transfer QRIS/Bank, dan verifikasi pelunasan cash</div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-green">💰</div>
    <div>
      <div class="stat-label">Total Denda Lunas</div>
      <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      <div class="stat-subtext">{{ $paidCount }} transaksi telah dilunasi</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-yellow">⏳</div>
    <div>
      <div class="stat-label">Menunggu Verifikasi</div>
      <div class="stat-value">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
      <div class="stat-subtext">{{ $pendingCount }} konfirmasi pembayaran masuk</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon stat-red">⚠️</div>
    <div>
      <div class="stat-label">Total Belum Lunas</div>
      <div class="stat-value">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
      <div class="stat-subtext">{{ $unpaidCount }} transaksi denda belum dibayar</div>
    </div>
  </div>
</div>

<!-- Filter & Search Bar -->
<div class="filter-bar">
  <div class="status-tabs">
    <a href="{{ route('admin.fines.index', array_filter(['search' => request('search')])) }}" 
       class="status-tab {{ !request('status') ? 'active' : '' }}">
      Semua Transaksi
    </a>
    <a href="{{ route('admin.fines.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}" 
       class="status-tab {{ request('status') === 'pending' ? 'active' : '' }}">
      ⏳ Menunggu Verifikasi ({{ $pendingCount }})
    </a>
    <a href="{{ route('admin.fines.index', array_filter(['status' => 'unpaid', 'search' => request('search')])) }}" 
       class="status-tab {{ request('status') === 'unpaid' ? 'active' : '' }}">
      ⚠️ Belum Lunas ({{ $unpaidCount }})
    </a>
    <a href="{{ route('admin.fines.index', array_filter(['status' => 'paid', 'search' => request('search')])) }}" 
       class="status-tab {{ request('status') === 'paid' ? 'active' : '' }}">
      ✅ Sudah Lunas ({{ $paidCount }})
    </a>
  </div>

  <form action="{{ route('admin.fines.index') }}" method="GET" class="search-form">
    @if(request('status'))
      <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <input type="text" name="search" class="search-input" placeholder="Cari customer, barang, kode..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-secondary" style="padding:8px 14px; font-size:13px; border-radius:10px;">Cari</button>
    @if(request('search'))
      <a href="{{ route('admin.fines.index', array_filter(['status' => request('status')])) }}" class="btn btn-outline" style="padding:8px 12px; font-size:13px; border-radius:10px;">Reset</a>
    @endif
  </form>
</div>

<!-- Data Table Card -->
<div class="table-card">
  @if($fines->count() > 0)
    <table class="custom-table">
      <thead>
        <tr>
          <th>Transaksi & Customer</th>
          <th>Barang</th>
          <th>Denda & Keterlambatan</th>
          <th>Metode & Status</th>
          <th style="text-align: right;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($fines as $fine)
          <tr>
            {{-- Kolom 1: Kode + Customer --}}
            <td>
              <div style="font-weight:700; color:#0f172a; font-size:13px;">{{ $fine->return_code }}</div>
              <div style="font-size:11.5px; color:#64748b;">Ref: {{ $fine->borrowing->borrow_code ?? '-' }}</div>
              <div style="font-size:11px; color:#94a3b8; margin-top:2px;">📅 {{ $fine->returned_at ? $fine->returned_at->format('d M Y') : '-' }}</div>
              <div style="margin-top:6px; padding-top:6px; border-top:1px dashed #e2e8f0;">
                <div style="font-weight:600; color:#0f172a; font-size:12.5px;">{{ $fine->borrowing->user->name ?? 'Customer' }}</div>
                <div style="font-size:11.5px; color:#64748b;">{{ $fine->borrowing->user->email ?? '-' }}</div>
              </div>
            </td>
            {{-- Kolom 2: Barang --}}
            <td>
              <div style="font-weight:600; color:#0f172a; font-size:13px;">{{ $fine->borrowing->item->name ?? '-' }}</div>
              <div style="font-size:12px; color:#64748b;">{{ $fine->borrowing->quantity ?? 1 }} unit</div>
            </td>
            {{-- Kolom 3: Denda + Status Keterlambatan --}}
            <td>
              <div style="font-weight:800; font-size:15px; color:#dc2626; margin-bottom:6px;">
                Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
              </div>
              @if($fine->late_days > 0)
                <span class="badge-soft badge-soft-danger" style="font-size:11px;">⏱️ Terlambat {{ $fine->late_days }}h</span>
              @else
                <span class="badge-soft badge-soft-info" style="font-size:11px;">✓ Tepat Waktu</span>
              @endif
              @if($fine->penalty_fee > 0)
                <div style="font-size:11px; color:#dc2626; margin-top:3px;">+Kondisi: Rp {{ number_format($fine->penalty_fee, 0, ',', '.') }}</div>
              @endif
            </td>
            {{-- Kolom 4: Metode + Status --}}
            <td>
              {{-- Metode --}}
              @if($fine->fine_payment_method === 'Transfer')
                <span class="badge-soft badge-soft-info" style="font-size:11px; margin-bottom:4px;">💳 Transfer/QRIS</span>
                @if($fine->fine_payment_proof)
                  <br>
                  <button type="button" class="btn-proof-preview" onclick="previewProofImage('{{ asset('storage/' . $fine->fine_payment_proof) }}', '{{ $fine->return_code }}')">
                    🖼️ Lihat Bukti
                  </button>
                @endif
              @elseif($fine->fine_payment_method === 'Cash')
                <span class="badge-soft badge-soft-warning" style="font-size:11px;">💵 Cash</span>
              @else
                <span style="font-size:11px; color:#94a3b8;">– Belum Pilih</span>
              @endif
              {{-- Status --}}
              <div style="margin-top:6px;">
                @if($fine->fine_payment_status === 'Lunas')
                  <span class="badge-soft badge-soft-success" style="font-size:11px;">✅ LUNAS</span>
                  <div style="font-size:10.5px; color:#64748b; margin-top:2px;">oleh {{ $fine->verifiedBy->name ?? 'Admin' }}</div>
                @elseif($fine->fine_payment_status === 'Menunggu Verifikasi')
                  <span class="badge-soft badge-soft-warning" style="font-size:11px;">⏳ Menunggu Verifikasi</span>
                @else
                  <span class="badge-soft badge-soft-danger" style="font-size:11px;">⚠️ Belum Bayar</span>
                @endif
              </div>
            </td>
            {{-- Kolom 5: Aksi --}}
            <td style="text-align: right;">
              @if($fine->fine_payment_status !== 'Lunas')
                <form action="{{ route('admin.returns.confirm-fine', $fine->id) }}" method="POST" id="confirm-fine-form-{{ $fine->id }}" style="display:inline;">
                  @csrf
                  @method('PATCH')
                  <button type="button" class="btn-confirm-fine" onclick="handleConfirmFine('{{ $fine->id }}', '{{ $fine->return_code }}', '{{ number_format($fine->fine_amount, 0, ',', '.') }}', '{{ $fine->fine_payment_method ?? 'Cash/Transfer' }}')">
                    ✓ Konfirmasi Lunas
                  </button>
                </form>
              @else
                <span style="font-size:12px; color:#16a34a; font-weight:600;">✓ Selesai</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @if($fines->hasPages())
      <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
        {{ $fines->withQueryString()->links() }}
      </div>
    @endif
  @else
    <div style="padding: 60px 20px; text-align: center;">
      <div style="font-size: 48px; margin-bottom: 16px;">✨</div>
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Tidak Ada Data Denda</h3>
      <p style="font-size: 13px; color: #64748b; margin: 0;">Tidak ditemukan catatan denda yang sesuai dengan filter atau pencarian Anda.</p>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  function handleConfirmFine(id, code, amountFormatted, method) {
    Swal.fire({
      title: 'Konfirmasi Pelunasan Denda?',
      html: `
        <div style="text-align:left; font-size:13.5px; line-height:1.6; color:#475569;">
          <p style="margin-bottom:10px;">Anda akan mengonfirmasi pelunasan denda untuk pengembalian <strong>${code}</strong>.</p>
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px; margin-bottom:10px;">
            <div><strong>Nominal Denda:</strong> <span style="color:#dc2626; font-weight:700;">Rp ${amountFormatted}</span></div>
            <div><strong>Metode Bayar:</strong> ${method}</div>
          </div>
          <p style="margin:0; font-size:12px; color:#64748b;">Status denda akan diperbarui menjadi <strong>LUNAS</strong>.</p>
        </div>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Setujui Pelunasan',
      cancelButtonText: 'Batal',
      customClass: {
        popup: 'swal-soft-popup',
        title: 'swal-soft-title',
        htmlContainer: 'swal-soft-content',
        confirmButton: 'swal-soft-confirm-btn',
        cancelButton: 'swal-soft-cancel-btn'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('confirm-fine-form-' + id).submit();
      }
    });
  }

  function previewProofImage(imageUrl, returnCode) {
    Swal.fire({
      title: 'Bukti Pembayaran Denda (' + returnCode + ')',
      imageUrl: imageUrl,
      imageAlt: 'Bukti Transfer',
      imageStyle: 'max-height: 400px; border-radius: 12px; border: 1px solid #cbd5e1;',
      confirmButtonText: 'Tutup',
      customClass: {
        popup: 'swal-soft-popup',
        title: 'swal-soft-title',
        confirmButton: 'swal-soft-cancel-btn'
      },
      buttonsStyling: false
    });
  }
</script>
@endpush
