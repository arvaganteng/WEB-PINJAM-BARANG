@if ($paginator->hasPages())
  @php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $blockSize = 5;
    $currentBlock = (int) floor(($currentPage - 1) / $blockSize);
    $startPage = ($currentBlock * $blockSize) + 1;
    $endPage = min($startPage + $blockSize - 1, $lastPage);
  @endphp
  <div class="custom-pagination">
    <div class="pagination-info">
      Menampilkan <span>{{ $paginator->firstItem() }}</span> &ndash; <span>{{ $paginator->lastItem() }}</span> dari <span>{{ $paginator->total() }}</span> data
    </div>
    <ul class="pagination-list">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
          <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" title="Halaman Sebelumnya">&lsaquo;</a>
        </li>
      @endif

      {{-- Fast Back to Previous Block --}}
      @if ($startPage > 1)
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->url($startPage - 1) }}" title="Ke Halaman {{ $startPage - $blockSize }} &ndash; {{ $startPage - 1 }}">&laquo;</a>
        </li>
      @endif

      {{-- Page Number Links (Max 5 items per block) --}}
      @for ($i = $startPage; $i <= $endPage; $i++)
        @if ($i == $currentPage)
          <li class="page-item active" aria-current="page">
            <span class="page-link">{{ $i }}</span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
          </li>
        @endif
      @endfor

      {{-- Fast Forward to Next Block --}}
      @if ($endPage < $lastPage)
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->url($endPage + 1) }}" title="Ke Halaman {{ $endPage + 1 }} &ndash; {{ min($endPage + $blockSize, $lastPage) }}">&raquo;</a>
        </li>
      @endif

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Selanjutnya" title="Halaman Selanjutnya">&rsaquo;</a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true" aria-label="Selanjutnya">
          <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>
      @endif
    </ul>
  </div>
@elseif($paginator->total() > 0)
  <div class="custom-pagination">
    <div class="pagination-info">
      Menampilkan <span>{{ $paginator->total() }}</span> data
    </div>
  </div>
@endif
