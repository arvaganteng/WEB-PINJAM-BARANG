@extends('layouts.customer')

@section('title', 'Invoice - ' . $borrowing->borrow_code)

@push('styles')
<style>
  @media print {
    body * { visibility: hidden; }
    #invoice-printable, #invoice-printable * { visibility: visible; }
    #invoice-printable { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
    .invoice-wrap { box-shadow: none !important; border: none !important; }
  }

  .invoice-wrap {
    max-width: 760px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 40px rgba(11,31,107,0.10);
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }

  .invoice-header {
    background: linear-gradient(135deg, #0b1f6b 0%, #1e40af 100%);
    padding: 32px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
  }

  .invoice-logo-area img {
    height: 54px;
    width: auto;
    object-fit: contain;
    filter: brightness(0) invert(1);
  }

  .invoice-meta {
    text-align: right;
    color: #fff;
  }

  .invoice-meta .inv-code {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 1px;
  }

  .invoice-meta .inv-date {
    font-size: 12px;
    opacity: 0.75;
    margin-top: 4px;
  }

  .invoice-status-bar {
    background: {{ $borrowing->payment_status === 'Lunas' ? '#f0fdf4' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#fffbeb' : '#fef2f2') }};
    border-bottom: 2px solid {{ $borrowing->payment_status === 'Lunas' ? '#86efac' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#fde68a' : '#fecaca') }};
    padding: 14px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }

  .inv-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 14px;
    padding: 6px 18px;
    border-radius: 100px;
  }

  .inv-status-badge.lunas { background: #dcfce7; color: #15803d; border: 1.5px solid #86efac; }
  .inv-status-badge.menunggu { background: #fef3c7; color: #92400e; border: 1.5px solid #fde68a; }
  .inv-status-badge.belum { background: #fee2e2; color: #b91c1c; border: 1.5px solid #fecaca; }
  .inv-status-badge.ditolak { background: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5; }

  .invoice-body {
    padding: 30px 36px;
  }

  .inv-section-title {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f1f5f9;
  }

  .inv-party-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
  }

  .inv-party-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px 18px;
    border: 1px solid #e2e8f0;
  }

  .inv-party-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 6px;
  }

  .inv-party-name {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 2px;
  }

  .inv-party-info {
    font-size: 12px;
    color: #64748b;
    line-height: 1.5;
  }

  .inv-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .inv-table thead tr {
    background: #0b1f6b;
    color: #fff;
  }

  .inv-table th {
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    text-align: left;
  }

  .inv-table th:last-child, .inv-table td:last-child {
    text-align: right;
  }

  .inv-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
  }

  .inv-table tbody tr:nth-child(even) {
    background: #f8fafc;
  }

  .inv-table td {
    padding: 12px 14px;
    font-size: 13px;
    color: #334155;
  }

  .inv-total-box {
    background: linear-gradient(135deg, #0b1f6b, #1e40af);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .inv-total-label {
    font-size: 13px;
    opacity: 0.8;
  }

  .inv-total-amount {
    font-size: 28px;
    font-weight: 900;
    letter-spacing: -0.5px;
  }

  .inv-payment-info {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 24px;
  }

  .inv-payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }

  .inv-payment-row:last-child {
    border-bottom: none;
  }

  .inv-payment-key {
    color: #64748b;
    font-weight: 500;
  }

  .inv-payment-val {
    font-weight: 700;
    color: #0f172a;
  }

  .inv-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 18px 36px;
    text-align: center;
    font-size: 11.5px;
    color: #94a3b8;
    line-height: 1.6;
  }

  .inv-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
  }

  @media (max-width: 640px) {
    .invoice-header { padding: 20px; }
    .invoice-body { padding: 20px; }
    .inv-party-grid { grid-template-columns: 1fr; }
    .inv-footer { padding: 14px 20px; }
  }
</style>
@endpush

@section('content')
<div id="invoice-printable">

  <!-- Action Bar -->
  <div class="inv-action-bar no-print">
    <a href="{{ route('customer.borrowings.show', $borrowing) }}" class="btn btn-ghost btn-sm" style="padding: 6px 0; color: #475569; font-weight: 600;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Kembali ke Detail Peminjaman
    </a>
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
      <button onclick="window.print()" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 7px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Cetak Invoice
      </button>
    </div>
  </div>

  <!-- Invoice Card -->
  <div class="invoice-wrap">

    <!-- Header -->
    <div class="invoice-header">
      <div class="invoice-logo-area">
        <img src="{{ asset('images/logo.png') }}" alt="PT Nusantara Digital Express"/>
        <div style="color: rgba(255,255,255,0.7); font-size: 11px; margin-top: 6px;">PT Nusantara Digital Express</div>
      </div>
      <div class="invoice-meta">
        <div class="inv-code">INVOICE</div>
        <div style="font-size: 16px; font-weight: 700; margin-top: 4px;">{{ $borrowing->borrow_code }}</div>
        <div class="inv-date">Tanggal: {{ $borrowing->created_at->translatedFormat('d F Y') }}</div>
      </div>
    </div>

    <!-- Status Bar -->
    <div class="invoice-status-bar">
      <div style="font-size: 13px; color: #475569; font-weight: 500;">
        Status Pembayaran Sewa Barang Inventaris
      </div>
      @if($borrowing->payment_status === 'Lunas')
        <span class="inv-status-badge lunas">LUNAS</span>
      @elseif($borrowing->payment_status === 'Menunggu Verifikasi')
        <span class="inv-status-badge menunggu">Menunggu Konfirmasi Admin</span>
      @elseif($borrowing->payment_status === 'Ditolak')
        <span class="inv-status-badge ditolak">Bukti Ditolak — Perlu Unggah Ulang</span>
      @else
        <span class="inv-status-badge belum">Belum Dibayar</span>
      @endif
    </div>

    <!-- Body -->
    <div class="invoice-body">

      <!-- Pihak -->
      <div class="inv-party-grid">
        <div class="inv-party-box">
          <div class="inv-party-label">📤 Dari (Pihak Pemberi Pinjam)</div>
          <div class="inv-party-name">PT Nusantara Digital Express</div>
          <div class="inv-party-info">
            Inventaris Barang & Aset Teknologi<br/>
            Sistem Manajemen Internal NDE
          </div>
        </div>
        <div class="inv-party-box">
          <div class="inv-party-label">📥 Kepada (Peminjam)</div>
          <div class="inv-party-name">{{ $borrowing->user->name }}</div>
          <div class="inv-party-info">
            {{ $borrowing->user->email }}<br/>
            {{ $borrowing->user->phone ?? '-' }}
          </div>
        </div>
      </div>

      <!-- Tabel Rincian -->
      <div class="inv-section-title">📋 Rincian Peminjaman</div>
      <table class="inv-table">
        <thead>
          <tr>
            <th style="width:40%">Deskripsi</th>
            <th>Durasi</th>
            <th>Qty</th>
            <th>Harga/Hari</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div style="font-weight: 700; color: #0f172a;">{{ $borrowing->item->name }}</div>
              <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                Kode: {{ $borrowing->item->code ?? '-' }} &bull;
                Kategori: {{ $borrowing->item->category->name ?? '-' }}
              </div>
              <div style="font-size: 11px; color: #64748b;">
                {{ $borrowing->borrow_date->format('d/m/Y') }} s/d {{ $borrowing->return_date->format('d/m/Y') }}
              </div>
            </td>
            <td>{{ $borrowing->duration_days }} hari</td>
            <td>{{ $borrowing->quantity }} unit</td>
            <td>Rp {{ number_format($borrowing->item->price_per_day ?? 0, 0, ',', '.') }}</td>
            <td style="font-weight: 700; color: #0b1f6b;">Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Total -->
      <div class="inv-total-box">
        <div>
          <div class="inv-total-label">Total Tagihan Peminjaman</div>
          @if($borrowing->payment_status === 'Lunas')
            <div style="font-size: 12px; opacity: 0.7; margin-top: 2px;">
              ✅ Dibayar {{ $borrowing->paid_at ? 'pada ' . $borrowing->paid_at->translatedFormat('d F Y, H:i') : '' }} via {{ $borrowing->payment_method }}
            </div>
          @endif
        </div>
        <div class="inv-total-amount">
          @if($borrowing->total_price <= 0)
            GRATIS
          @else
            Rp {{ number_format($borrowing->total_price, 0, ',', '.') }}
          @endif
        </div>
      </div>

      <!-- Info Pembayaran -->
      <div class="inv-section-title">💳 Informasi Pembayaran</div>
      <div class="inv-payment-info">
        <div class="inv-payment-row">
          <span class="inv-payment-key">Metode Pembayaran</span>
          <span class="inv-payment-val">{{ $borrowing->payment_method ?? 'Belum Dipilih' }}</span>
        </div>
        <div class="inv-payment-row">
          <span class="inv-payment-key">Status Pembayaran</span>
          <span class="inv-payment-val" style="color: {{ $borrowing->payment_status === 'Lunas' ? '#15803d' : ($borrowing->payment_status === 'Menunggu Verifikasi' ? '#92400e' : '#b91c1c') }}">
            {{ $borrowing->payment_status }}
          </span>
        </div>
        @if($borrowing->paid_at)
          <div class="inv-payment-row">
            <span class="inv-payment-key">Tanggal Lunas</span>
            <span class="inv-payment-val">{{ $borrowing->paid_at->translatedFormat('d F Y, H:i') }} WIB</span>
          </div>
        @endif
        @if($borrowing->payment_notes)
          <div class="inv-payment-row" style="align-items: flex-start;">
            <span class="inv-payment-key">Catatan</span>
            <span class="inv-payment-val" style="text-align: right; max-width: 60%;">{{ $borrowing->payment_notes }}</span>
          </div>
        @endif
        <div class="inv-payment-row">
          <span class="inv-payment-key">Keperluan Peminjaman</span>
          <span class="inv-payment-val" style="text-align: right; max-width: 60%;">{{ $borrowing->purpose }}</span>
        </div>
        <div class="inv-payment-row">
          <span class="inv-payment-key">Lokasi Penggunaan</span>
          <span class="inv-payment-val" style="text-align: right; max-width: 60%;">{{ $borrowing->location ?? '-' }}</span>
        </div>
      </div>

      @if($borrowing->payment_status !== 'Lunas' && $borrowing->total_price > 0)
        <!-- Info Rekening -->
        <div class="inv-section-title">🏦 Rekening &amp; QRIS Pembayaran Resmi NDE</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
          <div style="background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ asset('images/payments/bca.svg') }}" alt="BCA" height="32" style="border-radius: 4px;"/>
            <div>
              <div style="font-size: 11px; color: #0369a1; font-weight: 700; margin-bottom: 2px;">BANK BCA</div>
              <div style="font-size: 18px; font-weight: 900; color: #0b1f6b; font-family: monospace; letter-spacing: 1px;">8830-9921-4412</div>
              <div style="font-size: 11px; color: #475569; margin-top: 2px;">a/n PT Nusantara Digital Express</div>
            </div>
          </div>
          <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ asset('images/payments/mandiri.svg') }}" alt="Mandiri" height="32" style="border-radius: 4px;"/>
            <div>
              <div style="font-size: 11px; color: #15803d; font-weight: 700; margin-bottom: 2px;">BANK MANDIRI</div>
              <div style="font-size: 18px; font-weight: 900; color: #0b1f6b; font-family: monospace; letter-spacing: 1px;">1420-0012-9981-2</div>
              <div style="font-size: 11px; color: #475569; margin-top: 2px;">a/n PT Nusantara Digital Express</div>
            </div>
          </div>
        </div>
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; font-size: 12.5px; color: #92400e; margin-bottom: 20px; line-height: 1.6;">
          ⚠️ <strong>Penting:</strong> Cantumkan kode peminjaman <strong>{{ $borrowing->borrow_code }}</strong> sebagai berita transfer. Simpan bukti transfer dan unggah melalui halaman detail peminjaman.
        </div>
      @endif

      <!-- Denda (jika ada) -->
      @if($borrowing->returnRecord && $borrowing->returnRecord->fine_amount > 0)
        <div class="inv-section-title">⚠️ Tagihan Denda Keterlambatan / Kondisi Barang</div>
        <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
              <div style="font-weight: 700; color: #991b1b; margin-bottom: 4px;">Denda: Rp {{ number_format($borrowing->returnRecord->fine_amount, 0, ',', '.') }}</div>
              <div style="font-size: 12px; color: #b91c1c;">
                Kondisi barang dikembalikan: <strong>{{ $borrowing->returnRecord->item_condition ?? '-' }}</strong>
              </div>
              @if($borrowing->returnRecord->admin_notes)
                <div style="font-size: 12px; color: #b91c1c; margin-top: 3px;">Catatan admin: {{ $borrowing->returnRecord->admin_notes }}</div>
              @endif
            </div>
            <div>
              @if($borrowing->returnRecord->fine_payment_status === 'Lunas')
                <span style="background: #dcfce7; color: #15803d; font-weight: 700; font-size: 13px; padding: 5px 14px; border-radius: 100px; border: 1px solid #86efac;">✓ Denda Lunas</span>
              @else
                <span style="background: #fee2e2; color: #b91c1c; font-weight: 700; font-size: 13px; padding: 5px 14px; border-radius: 100px; border: 1px solid #fca5a5;">⚠️ {{ $borrowing->returnRecord->fine_payment_status }}</span>
              @endif
            </div>
          </div>
        </div>
      @endif

    </div>

    <!-- Footer -->
    <div class="inv-footer">
      <div style="margin-bottom: 6px;">
        Dokumen ini merupakan bukti resmi transaksi peminjaman barang inventaris PT Nusantara Digital Express.<br/>
        Dicetak otomatis oleh Sistem Inventaris NDE pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
      </div>
      <div>
        Untuk pertanyaan, hubungi inventaris@nde.co.id &bull; Telp: (021) 5XXX-XXXX
      </div>
    </div>

  </div><!-- end invoice-wrap -->

</div><!-- end invoice-printable -->
@endsection
