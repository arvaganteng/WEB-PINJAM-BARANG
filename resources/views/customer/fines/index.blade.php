@extends('layouts.customer')

@section('title', 'Denda Saya')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
  <!-- Header Page -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
        Denda Saya
      </h1>
      <p style="font-size: 13.5px; color: #64748b; margin: 0;">
        Pantau tagihan denda keterlambatan atau kerusakan barang serta lakukan pelunasan via Transfer/Cash
      </p>
    </div>
    
    <a href="{{ route('customer.borrowings.index') }}" class="btn btn-outline btn-sm">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Lihat Peminjaman Saya
    </a>
  </div>

  <!-- Metric Summary Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 28px;">
    <div class="fine-metric-card">
      <div style="width: 46px; height: 46px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <div>
        <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Seluruh Denda</div>
        <div style="font-size: 19px; font-weight: 800; color: #0f172a;">Rp {{ number_format($totalFineAmount, 0, ',', '.') }}</div>
      </div>
    </div>

    <div class="fine-metric-card">
      <div style="width: 46px; height: 46px; border-radius: 12px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div>
        <div style="font-size: 12px; color: #991b1b; font-weight: 600; text-transform: uppercase;">Tagihan Belum Lunas</div>
        <div style="font-size: 19px; font-weight: 800; color: #dc2626;">Rp {{ number_format($unpaidFineAmount, 0, ',', '.') }}</div>
      </div>
    </div>

    <div class="fine-metric-card">
      <div style="width: 46px; height: 46px; border-radius: 12px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <div>
        <div style="font-size: 12px; color: #15803d; font-weight: 600; text-transform: uppercase;">Denda Sudah Lunas</div>
        <div style="font-size: 19px; font-weight: 800; color: #16a34a;">Rp {{ number_format($paidFineAmount, 0, ',', '.') }}</div>
      </div>
    </div>
  </div>

  <!-- Filter Pills -->
  <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
    <a href="{{ route('customer.fines.index') }}" class="btn btn-sm" style="border-radius: 20px; font-size: 12.5px; padding: 6px 16px; {{ !request('status') ? 'background: #0f172a; color: #ffffff;' : 'background: #ffffff; color: #475569; border: 1px solid #cbd5e1;' }}">
      Semua Denda
    </a>
    <a href="{{ route('customer.fines.index', ['status' => 'unpaid']) }}" class="btn btn-sm" style="border-radius: 20px; font-size: 12.5px; padding: 6px 16px; {{ request('status') == 'unpaid' ? 'background: #dc2626; color: #ffffff;' : 'background: #ffffff; color: #dc2626; border: 1px solid #fca5a5;' }}">
      Belum Bayar ({{ $unpaidCount }})
    </a>
    <a href="{{ route('customer.fines.index', ['status' => 'pending']) }}" class="btn btn-sm" style="border-radius: 20px; font-size: 12.5px; padding: 6px 16px; {{ request('status') == 'pending' ? 'background: #d97706; color: #ffffff;' : 'background: #ffffff; color: #d97706; border: 1px solid #fde68a;' }}">
      Menunggu Verifikasi ({{ $pendingCount }})
    </a>
    <a href="{{ route('customer.fines.index', ['status' => 'paid']) }}" class="btn btn-sm" style="border-radius: 20px; font-size: 12.5px; padding: 6px 16px; {{ request('status') == 'paid' ? 'background: #16a34a; color: #ffffff;' : 'background: #ffffff; color: #16a34a; border: 1px solid #86efac;' }}">
      Sudah Lunas
    </a>
  </div>

  <!-- Fine Cards List -->
  @forelse($fines as $fine)
    <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
      <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
          <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 2px;">
            Kode Pinjam: {{ $fine->borrowing->borrow_code ?? '-' }}
          </div>
          <div style="font-size: 15px; font-weight: 800; color: #0f172a;">
            {{ $fine->item->name ?? '-' }}
          </div>
        </div>
        <div style="text-align: right;">
          <span class="badge" style="background: {{ $fine->fine_payment_status == 'Lunas' ? '#dcfce7' : ($fine->fine_payment_status == 'Menunggu Verifikasi' ? '#fef3c7' : '#fee2e2') }}; color: {{ $fine->fine_payment_status == 'Lunas' ? '#15803d' : ($fine->fine_payment_status == 'Menunggu Verifikasi' ? '#92400e' : '#b91c1c') }}; font-size: 11.5px; padding: 4px 10px; margin-bottom: 4px; display: inline-block;">
            {{ $fine->fine_payment_status == 'Lunas' ? 'Lunas (' . ($fine->fine_payment_method ?? 'Cash') . ')' : ($fine->fine_payment_status == 'Menunggu Verifikasi' ? 'Menunggu Verifikasi Admin' : 'Belum Lunas') }}
          </span>
          <div style="font-size: 18px; font-weight: 800; color: #dc2626;">
            Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
          </div>
        </div>
      </div>

      <div style="padding: 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; font-size: 13px;">
          <div>
            <span style="color: #64748b;">Tanggal Pengembalian:</span>
            <div style="font-weight: 600; color: #0f172a;">{{ $fine->return_date->format('d M Y') }}</div>
          </div>
          <div>
            <span style="color: #64748b;">Kondisi Barang:</span>
            <div style="font-weight: 600; color: #0f172a;">{{ $fine->item_condition ?? 'Dikembalikan' }}</div>
          </div>
        </div>

        @if($fine->admin_notes)
          <div style="padding: 10px 14px; background: #f1f5f9; border-radius: 8px; font-size: 12.5px; color: #334155; margin-bottom: 16px;">
            <strong>Catatan Admin:</strong> {{ $fine->admin_notes }}
          </div>
        @endif

        @if($fine->fine_payment_status == 'Lunas')
          <div style="padding: 12px 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; color: #166534; font-size: 13px;">
            Pembayaran denda sebesar <strong>Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}</strong> telah diterima &amp; diverifikasi LUNAS oleh admin.
          </div>
        @elseif($fine->fine_payment_status == 'Menunggu Verifikasi')
          <div style="padding: 12px 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; color: #92400e; font-size: 13px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
              Konfirmasi pembayaran via <strong>{{ $fine->fine_payment_method }}</strong> sedang ditinjau admin.
            </div>
            @if($fine->fine_payment_proof)
              <button type="button" onclick="previewImageSoft('{{ asset('storage/' . $fine->fine_payment_proof) }}', 'Bukti Transfer Denda')" class="btn btn-ghost btn-sm" style="background: #ffffff; border: 1px solid #fde68a; color: #92400e; font-size: 12px;">
                Lihat Bukti Transfer
              </button>
            @endif
          </div>
        @else
          <!-- Form Bayar Denda (Transfer / Cash) -->
          <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; margin-top: 16px;">
            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; margin-bottom: 12px;">
              Bayar Denda Sekarang:
            </div>
            
            <form action="{{ route('customer.borrowings.pay-fine', $fine->borrowing) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <label id="opt-transfer-{{ $fine->id }}" style="border: 2px solid #3b82f6; background: #eff6ff; border-radius: 10px; padding: 12px; cursor: pointer; display: block;" onclick="switchFineCardMethod({{ $fine->id }}, 'Transfer')">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="fine_payment_method" value="Transfer" checked id="radio-transfer-{{ $fine->id }}"/>
                    <span style="font-weight: 700; font-size: 13px; color: #1e40af;">Transfer Bank / QRIS</span>
                  </div>
                </label>
                <label id="opt-cash-{{ $fine->id }}" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 10px; padding: 12px; cursor: pointer; display: block;" onclick="switchFineCardMethod({{ $fine->id }}, 'Cash')">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="fine_payment_method" value="Cash" id="radio-cash-{{ $fine->id }}"/>
                    <span style="font-weight: 700; font-size: 13px; color: #334155;">Bayar Cash (Gudang)</span>
                  </div>
                </label>
              </div>

              <!-- Box Transfer -->
              <div id="transfer-box-{{ $fine->id }}" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                <div style="font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Rekening Bank BCA: <strong>8830-9921-4412</strong> (a/n PT NDE)</div>
                <div class="form-group" style="margin: 0;">
                  <label class="form-label" style="font-size: 12px; font-weight: 600;">Upload Struk Bukti Transfer <span class="required">*</span></label>
                  <input type="file" name="fine_payment_proof" class="form-input" accept="image/jpeg,image/png,image/jpg,image/webp" id="proof-input-{{ $fine->id }}" required/>
                </div>
              </div>

              <!-- Box Cash -->
              <div id="cash-box-{{ $fine->id }}" style="display: none; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px; margin-bottom: 14px; color: #92400e; font-size: 12px;">
                Serahkan uang tunai Rp {{ number_format($fine->fine_amount, 0, ',', '.') }} ke kasir/petugas gudang saat pengembalian barang.
              </div>

              <div style="text-align: right;">
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 20px; font-weight: 700;">
                  Kirim Pembayaran Denda
                </button>
              </div>
            </form>
          </div>
        @endif
      </div>
    </div>
  @empty
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 60px 20px; text-align: center;">
      <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
        Tidak Ada Tagihan Denda
      </h3>
      <p style="color: #64748b; font-size: 13.5px; margin: 0;">
        Anda tidak memiliki tagihan denda keterlambatan atau kerusakan barang.
      </p>
    </div>
  @endforelse

  <div style="margin-top: 20px;">
    {{ $fines->links() }}
  </div>
</div>

<script>
  function switchFineCardMethod(fineId, method) {
    const tBox = document.getElementById('transfer-box-' + fineId);
    const cBox = document.getElementById('cash-box-' + fineId);
    const proofInput = document.getElementById('proof-input-' + fineId);

    const optT = document.getElementById('opt-transfer-' + fineId);
    const optC = document.getElementById('opt-cash-' + fineId);

    const rT = document.getElementById('radio-transfer-' + fineId);
    const rC = document.getElementById('radio-cash-' + fineId);

    if (method === 'Transfer') {
      rT.checked = true;
      tBox.style.display = 'block';
      cBox.style.display = 'none';
      if (proofInput) proofInput.required = true;

      optT.style.border = '2px solid #3b82f6';
      optT.style.background = '#eff6ff';
      optC.style.border = '2px solid #e2e8f0';
      optC.style.background = '#f8fafc';
    } else {
      rC.checked = true;
      tBox.style.display = 'none';
      cBox.style.display = 'block';
      if (proofInput) proofInput.required = false;

      optC.style.border = '2px solid #3b82f6';
      optC.style.background = '#eff6ff';
      optT.style.border = '2px solid #e2e8f0';
      optT.style.background = '#f8fafc';
    }
  }

  function previewImageSoft(src, title) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: title,
        imageUrl: src,
        imageAlt: title,
        confirmButtonText: 'Tutup',
        customClass: { popup: 'swal-soft-popup' }
      });
    } else {
      window.open(src, '_blank');
    }
  }
</script>
@endsection
