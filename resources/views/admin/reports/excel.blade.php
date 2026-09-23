<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Peminjaman Barang</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }
    .title { font-size: 15pt; font-weight: bold; color: #0B1F6B; text-align: center; }
    .subtitle { font-size: 11pt; font-weight: bold; color: #2563EB; text-align: center; }
    .meta { font-size: 9pt; color: #555555; text-align: center; }
    .th-header { background-color: #0B1F6B; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; height: 28px; }
    .td-data { border: 1px solid #D1D5DB; vertical-align: middle; }
    .td-number { border: 1px solid #D1D5DB; text-align: right; }
    .td-center { border: 1px solid #D1D5DB; text-align: center; }
    .td-total { background-color: #E2E8F0; font-weight: bold; border: 1px solid #000000; height: 24px; }
    .stat-header { background-color: #EFF6FF; font-weight: bold; border: 1px solid #BFDBFE; text-align: center; }
    .stat-val { font-weight: bold; text-align: center; border: 1px solid #BFDBFE; font-size: 11pt; }
  </style>
</head>
<body>
  <table border="1" style="border-collapse: collapse;">
    <!-- TITLE -->
    <tr>
      <td colspan="11" class="title" style="border: none;">PT NUSANTARA DIGITAL EXPRESS</td>
    </tr>
    <tr>
      <td colspan="11" class="subtitle" style="border: none;">LAPORAN TRANSAKSI PEMINJAMAN &amp; DENDA BARANG INVENTARIS</td>
    </tr>
    <tr>
      <td colspan="11" class="meta" style="border: none;">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }} 
        | Status: {{ $status ?? 'Semua' }} 
        | Dicetak: {{ date('d/m/Y H:i') }} WIB (Oleh: {{ auth()->user()->name ?? 'Admin' }})
      </td>
    </tr>
    <tr><td colspan="11" style="border: none;"></td></tr>

    <!-- SUMMARY STATS TABLE -->
    <tr>
      <th colspan="2" class="stat-header">Total Pengajuan</th>
      <th colspan="2" class="stat-header">Disetujui / Selesai</th>
      <th colspan="2" class="stat-header">Pendapatan Sewa</th>
      <th colspan="2" class="stat-header">Denda Lunas</th>
      <th colspan="3" class="stat-header">Total Pendapatan (Sewa + Denda)</th>
    </tr>
    <tr>
      <td colspan="2" class="stat-val" style="color:#0B1F6B;">{{ $stats['total'] }}</td>
      <td colspan="2" class="stat-val" style="color:#16A34A;">{{ $stats['approved'] }}</td>
      <td colspan="2" class="stat-val" style="color:#2563EB;">Rp {{ number_format($stats['rental_revenue'], 0, ',', '.') }}</td>
      <td colspan="2" class="stat-val" style="color:#16A34A;">Rp {{ number_format($stats['fine_revenue'], 0, ',', '.') }}</td>
      <td colspan="3" class="stat-val" style="color:#0B1F6B;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
    </tr>
    <tr><td colspan="11" style="border: none;"></td></tr>

    <!-- DATA HEADERS -->
    <tr>
      <th class="th-header" style="width: 40px;">No</th>
      <th class="th-header" style="width: 130px;">Kode Peminjaman</th>
      <th class="th-header" style="width: 180px;">Nama Customer</th>
      <th class="th-header" style="width: 200px;">Email / Kontak</th>
      <th class="th-header" style="width: 200px;">Barang yang Dipinjam</th>
      <th class="th-header" style="width: 110px;">Tanggal Pinjam</th>
      <th class="th-header" style="width: 120px;">Biaya Sewa (Rp)</th>
      <th class="th-header" style="width: 120px;">Nominal Denda (Rp)</th>
      <th class="th-header" style="width: 110px;">Status Denda</th>
      <th class="th-header" style="width: 140px;">Total Pembayaran (Rp)</th>
      <th class="th-header" style="width: 100px;">Status</th>
    </tr>

    <!-- ROWS -->
    @forelse($borrowings as $index => $b)
      @php
        $fineAmount = $b->returnRecord->fine_amount ?? 0;
        $fineStatus = $b->returnRecord->fine_payment_status ?? 'Tidak Ada';
        $finePaid = ($fineStatus === 'Lunas') ? $fineAmount : 0;
        $grandTotal = $b->total_price + $finePaid;
      @endphp
      <tr>
        <td class="td-center">{{ $index + 1 }}</td>
        <td class="td-center" style="font-weight:bold;">{{ $b->borrow_code }}</td>
        <td class="td-data">{{ $b->user->name ?? '-' }}</td>
        <td class="td-data">{{ $b->user->email ?? '-' }} / {{ $b->user->phone ?? '-' }}</td>
        <td class="td-data">{{ $b->item->name ?? '-' }}</td>
        <td class="td-center">{{ $b->borrow_date->format('d/m/Y') }}</td>
        <td class="td-number">{{ number_format($b->total_price, 0, ',', '.') }}</td>
        <td class="td-number" style="color: {{ $fineAmount > 0 ? '#DC2626' : '#000' }}; font-weight: {{ $fineAmount > 0 ? 'bold' : 'normal' }};">
          {{ number_format($fineAmount, 0, ',', '.') }}
        </td>
        <td class="td-center">{{ $fineStatus }}</td>
        <td class="td-number" style="font-weight:bold; color:#0B1F6B;">
          {{ number_format($grandTotal, 0, ',', '.') }}
        </td>
        <td class="td-center">{{ $b->status }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="11" class="td-center" style="padding: 16px; color: #888888;">
          Tidak ada data transaksi peminjaman pada periode ini.
        </td>
      </tr>
    @endforelse

    <!-- TOTAL FOOTER -->
    <tr>
      <td colspan="9" class="td-total" style="text-align: right;">TOTAL PENDAPATAN (SEWA + DENDA LUNAS):</td>
      <td class="td-total" style="text-align: right; color: #0B1F6B;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
      <td class="td-total" style="text-align: center;">{{ $borrowings->count() }} Data</td>
    </tr>
  </table>
</body>
</html>
