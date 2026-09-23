@extends('layouts.admin')

@section('title', 'Data Kategori')

@section('content')
<div class="page-header">
  <div>
    <div class="page-title">Data Kategori Barang</div>
    <div class="page-subtitle">Kelola kategori pengelompokan aset inventaris</div>
  </div>
  <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Kategori
  </button>
</div>

<div class="card">
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Kategori</th>
          <th>Jumlah Barang</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td style="font-weight:600;color:var(--navy)">KAT-{{ str_pad($cat->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td style="font-weight:600">{{ $cat->name }}</td>
            <td><span style="font-weight:700;color:var(--blue)">{{ $cat->items_count }}</span> barang</td>
            <td style="color:var(--gray-500);font-size:13px">{{ $cat->description ?? '-' }}</td>
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
                  <!-- UPDATE / EDIT -->
                  <button type="button" class="action-dropdown-item" onclick="openEditModal({{ json_encode($cat) }})">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--yellow)"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>Edit Kategori</span>
                  </button>

                  <div class="action-dropdown-divider"></div>

                  <!-- DELETE / HAPUS -->
                  <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirmSoft(event, 'Hapus kategori \'{{ addslashes($cat->name) }}\' beserta data terkait?', 'Hapus Kategori?', 'warning', 'Ya, Hapus', '#ef4444')" style="margin:0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-dropdown-item danger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                      <span>Hapus Kategori</span>
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center;color:var(--gray-500);padding:24px">Belum ada kategori barang.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding: 16px;">
    {{ $categories->links() }}
  </div>
</div>

@push('scripts')
<script>
  function openCreateModal() {
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Tambah Kategori Baru</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="{{ route('admin.categories.store') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Kategori <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" placeholder="Contoh: Elektronik" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-input form-textarea" placeholder="Deskripsi singkat..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Kategori</button>
        </div>
      </form>
    `);
  }

  function openEditModal(cat) {
    openModal(`
      <div class="modal-header">
        <div class="modal-title">Edit Kategori: ${cat.name}</div>
        <button class="modal-close" onclick="closeModal()">✕</button>
      </div>
      <form action="/admin/categories/${cat.id}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="_method" value="PUT"/>
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Kategori <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" value="${cat.name}" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-input form-textarea">${cat.description || ''}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
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
      if (spaceBelow < 150 && rect.top > 150) {
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
