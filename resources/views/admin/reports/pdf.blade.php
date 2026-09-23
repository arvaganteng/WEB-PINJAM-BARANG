<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <title>Laporan Peminjaman Barang – PT Nusantara Digital Express</title>
  <style>
    @page {
      margin: 18mm 14mm 16mm 14mm;
      size: A4 landscape;
    }
    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 11px;
      color: #1e293b;
      line-height: 1.4;
      margin: 0;
      padding: 0;
    }

    /* HEADER / KOP SURAT */
    .kop-table {
      width: 100%;
      border-bottom: 2px solid #0B1F6B;
      padding-bottom: 12px;
      margin-bottom: 16px;
    }
    .kop-logo {
      width: 140px;
      vertical-align: middle;
    }
    .kop-logo img {
      max-width: 140px;
      height: auto;
    }
    .kop-text {
      text-align: left;
      vertical-align: middle;
      padding-left: 14px;
    }
    .kop-title {
      font-size: 18px;
      font-weight: bold;
      color: #0B1F6B;
      margin: 0 0 2px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .kop-subtitle {
      font-size: 12px;
      font-weight: bold;
      color: #2563EB;
      margin: 0 0 4px;
    }
    .kop-meta {
      font-size: 9.5px;
      color: #64748b;
      margin: 0;
    }
    .kop-right {
      text-align: right;
      vertical-align: middle;
    }
    .doc-badge {
      display: inline-block;
      padding: 5px 12px;
      background: #EFF6FF;
      border: 1px solid #BFDBFE;
      color: #1D4ED8;
      font-size: 11px;
      font-weight: bold;
      border-radius: 4px;
      text-transform: uppercase;
    }

    /* REPORT INFO */
    .info-table {
      width: 100%;
      margin-bottom: 14px;
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 6px;
      padding: 8px 12px;
    }
    .info-table td {
      font-size: 10.5px;
      padding: 2px 8px;
    }
    .info-label {
      color: #64748B;
      font-weight: bold;
      width: 130px;
    }
    .info-value {
      color: #0F172A;
      font-weight: bold;
    }

    /* STATS SUMMARY BOXES */
    .stats-table {
      width: 100%;
      margin-bottom: 16px;
      border-collapse: collapse;
    }
    .stats-table td {
      padding: 8px;
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      text-align: center;
      width: 20%;
    }
    .stat-val {
      font-size: 14px;
      font-weight: bold;
      color: #0B1F6B;
    }
    .stat-lbl {
      font-size: 9px;
      color: #64748B;
      text-transform: uppercase;
      font-weight: bold;
      margin-top: 2px;
    }

    /* MAIN DATA TABLE */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .data-table th {
      background: #0B1F6B;
      color: #ffffff;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      padding: 7px 8px;
      text-align: left;
      border: 1px solid #0B1F6B;
    }
    .data-table td {
      padding: 6px 8px;
      border: 1px solid #CBD5E1;
      font-size: 10px;
      vertical-align: middle;
    }
    .data-table tr:nth-child(even) {
      background: #F8FAFC;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }

    /* STATUS BADGES */
    .badge {
      display: inline-block;
      padding: 2px 7px;
      border-radius: 3px;
      font-size: 9px;
      font-weight: bold;
    }
    .badge-selesai { background: #DCFCE7; color: #15803D; }
    .badge-dipinjam { background: #DBEAFE; color: #1D4ED8; }
    .badge-disetujui { background: #F3E8FF; color: #7E22CE; }
    .badge-menunggu { background: #FEF3C7; color: #B45309; }
    .badge-ditolak { background: #FEE2E2; color: #B91C1C; }

    /* FOOTER SIGNATURES */
    .sign-table {
      width: 100%;
      margin-top: 15px;
      page-break-inside: avoid;
    }
    .sign-table td {
      vertical-align: top;
      font-size: 10.5px;
    }
    .sign-box {
      text-align: center;
      width: 250px;
    }
    .sign-line {
      margin-top: 55px;
      border-bottom: 1px solid #334155;
      width: 180px;
      display: inline-block;
    }
  </style>
</head>
<body>

  <!-- KOP SURAT / HEADER -->
  <table class="kop-table" cellpadding="0" cellspacing="0">
    <tr>
      <td class="kop-logo">
        @if(!empty($logoBase64))
          <img src="{{ $logoBase64 }}" alt="Logo"/>
        @endif
      </td>
      <td class="kop-text">
        <div class="kop-title">PT Nusantara Digital Express</div>
        <div class="kop-subtitle">Sistem Peminjaman &amp; Inventaris Barang Perusahaan</div>
        <div class="kop-meta">
          Jl. Logistik Digital No. 88, Jakarta &bull; Telp: (021) 555-7890 &bull; Email: support@nusantara-express.com
        </div>
      </td>
      <td class="kop-right">
        <div class="doc-badge">Laporan Resmi</div>
        <div style="font-size: 9px; color: #64748B; margin-top: 4px;">
          Tgl Cetak: {{ date('d/m/Y H:i') }} WIB
        </div>
      </td>
    </tr>
  </table>

  <!-- INFORMASI FILTER -->
  <table class="info-table" cellpadding="0" cellspacing="0">
    <tr>
      <td class="info-label">Periode Laporan:</td>
      <td class="info-value">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</td>
      <td class="info-label">Filter Status:</td>
      <td class="info-value">{{ $status ?? 'Semua Status' }}</td>
      <td class="info-label">Dicetak Oleh:</td>
      <td class="info-value">{{ auth()->user()->name ?? 'Administrator' }}</td>
    </tr>
  </table>

  <!-- STATISTIK RINGKASAN -->
  <table class="stats-table" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <div class="stat-val">{{ $stats['total'] }}</div>
        <div class="stat-lbl">Total Pengajuan</div>
      </td>
      <td>
        <div class="stat-val" style="color: #16A34A;">{{ $stats['approved'] }}</div>
        <div class="stat-lbl">Disetujui / Selesai</div>
      </td>
      <td>
        <div class="stat-val" style="color: #2563EB;">Rp {{ number_format($stats['rental_revenue'], 0, ',', '.') }}</div>
        <div class="stat-lbl">Sewa Barang</div>
      </td>
      <td>
        <div class="stat-val" style="color: #16A34A;">Rp {{ number_format($stats['fine_revenue'], 0, ',', '.') }}</div>
        <div class="stat-lbl">Denda Lunas</div>
      </td>
      <td>
        <div class="stat-val" style="color: #0B1F6B;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        <div class="stat-lbl">Total Pendapatan</div>
      </td>
    </tr>
  </table>

  <!-- TABEL DATA -->
  <table class="data-table" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th class="text-center" style="width: 25px;">No</th>
        <th style="width: 80px;">Kode</th>
        <th style="width: 130px;">Peminjam (Customer)</th>
        <th>Barang yang Dipinjam</th>
        <th class="text-center" style="width: 70px;">Tgl Pinjam</th>
        <th class="text-right" style="width: 75px;">Biaya Sewa</th>
        <th class="text-right" style="width: 85px;">Denda</th>
        <th class="text-right" style="width: 85px;">Total Biaya</th>
        <th class="text-center" style="width: 65px;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($borrowings as $index => $b)
        @php
          $fineAmount = $b->returnRecord->fine_amount ?? 0;
          $fineStatus = $b->returnRecord->fine_payment_status ?? null;
          $finePaid = ($fineStatus === 'Lunas') ? $fineAmount : 0;
          $grandTotal = $b->total_price + $finePaid;
        @endphp
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td><strong>{{ $b->borrow_code }}</strong></td>
          <td>
            <strong>{{ $b->user->name ?? '-' }}</strong><br/>
            <span style="color: #64748B; font-size: 9px;">{{ $b->user->email ?? '' }}</span>
          </td>
          <td>{{ $b->item->name ?? '-' }}</td>
          <td class="text-center">{{ $b->borrow_date->format('d/m/Y') }}</td>
          <td class="text-right">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
          <td class="text-right">
            @if($fineAmount > 0)
              <span style="color:#DC2626; font-weight:bold;">Rp {{ number_format($fineAmount, 0, ',', '.') }}</span>
              <br/>
              <span style="font-size:8px; color: {{ $fineStatus === 'Lunas' ? '#16A34A' : ($fineStatus === 'Menunggu Verifikasi' ? '#D97706' : '#DC2626') }}; font-weight:bold;">
                ({{ $fineStatus ?? 'Belum Bayar' }})
              </span>
            @else
              <span style="color:#94A3B8;">Rp 0</span>
            @endif
          </td>
          <td class="text-right" style="font-weight: bold; color:#0B1F6B;">
            Rp {{ number_format($grandTotal, 0, ',', '.') }}
          </td>
          <td class="text-center">
            @if($b->status == 'Selesai')
              <span class="badge badge-selesai">Selesai</span>
            @elseif($b->status == 'Dipinjam')
              <span class="badge badge-dipinjam">Dipinjam</span>
            @elseif($b->status == 'Disetujui')
              <span class="badge badge-disetujui">Disetujui</span>
            @elseif($b->status == 'Menunggu')
              <span class="badge badge-menunggu">Menunggu</span>
            @else
              <span class="badge badge-ditolak">{{ $b->status }}</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center" style="padding: 24px; color: #94A3B8;">
            Tidak ada data transaksi peminjaman pada periode ini.
          </td>
        </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr style="background: #F1F5F9; font-weight: bold;">
        <td colspan="7" class="text-right" style="padding: 8px;">TOTAL KESELURUHAN (SEWA + DENDA LUNAS):</td>
        <td class="text-right" style="padding: 8px; color: #0B1F6B; font-size: 11px;">
          Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
        </td>
        <td class="text-center" style="padding: 8px;">{{ $borrowings->count() }} Data</td>
      </tr>
    </tfoot>
  </table>

  <!-- TANDA TANGAN -->
  <table class="sign-table">
    <tr>
      <td style="width: 65%;">
        <div style="font-size: 9px; color: #64748B;">
          <em>Dokumen ini dicetak secara komputerisasi dari Sistem Informasi Peminjaman Barang PT Nusantara Digital Express.</em>
        </div>
      </td>
      <td class="text-right">
        <div class="sign-box" style="float: right;">
          <div>Jakarta, {{ date('d F Y') }}</div>
          <div style="font-weight: bold; margin-top: 4px;">Penanggung Jawab Inventaris,</div>
          <div class="sign-line"></div>
          <div style="font-weight: bold; margin-top: 4px;">{{ auth()->user()->name ?? 'Administrator' }}</div>
          <div style="font-size: 9px; color: #64748B;">PT Nusantara Digital Express</div>
        </div>
      </td>
    </tr>
  </table>

</body>
</html>
