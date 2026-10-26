@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = method_exists($paginator, 'lastPage') ? $paginator->lastPage() : 1;
        $total = method_exists($paginator, 'total') ? $paginator->total() : 0;
        $firstItem = method_exists($paginator, 'firstItem') ? ($paginator->firstItem() ?? 0) : 0;
        $lastItem = method_exists($paginator, 'lastItem') ? ($paginator->lastItem() ?? 0) : 0;

        // Rút gọn trang theo kiểu < 1, 2 ... (trang cuối) >
        if ($lastPage <= 4) {
            $pages = range(1, $lastPage);
        } elseif ($currentPage <= 2) {
            $pages = [1, 2, '...', $lastPage];
        } elseif ($currentPage == 3) {
            $pages = [1, 2, 3, '...', $lastPage];
        } elseif ($currentPage >= $lastPage - 1) {
            $pages = [1, '...', $lastPage - 1, $lastPage];
        } else {
            $pages = [1, '...', $currentPage, '...', $lastPage];
        }
    @endphp

    <nav class="admin-pagination" role="navigation" aria-label="Pagination Navigation">
        @if ($total > 0)
            <div class="pagination-info">
                {{ $firstItem }}-{{ $lastItem }} / {{ number_format($total) }}
            </div>
        @endif

        <ul class="pagination-list">
            {{-- Nút < (Previous) --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" title="Trang trước">
                    <span class="page-link nav-btn"><i class="ti ti-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item" title="Trang trước">
                    <a class="page-link nav-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Các số trang rút gọn: 1, 2 ... trang cuối --}}
            @foreach ($pages as $p)
                @if ($p === '...')
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link dots">&hellip;</span>
                    </li>
                @elseif ($p == $currentPage)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $p }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($p) }}">{{ $p }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Nút > (Next) --}}
            @if ($paginator->hasMorePages())
                <li class="page-item" title="Trang tiếp">
                    <a class="page-link nav-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang tiếp">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" title="Trang tiếp">
                    <span class="page-link nav-btn"><i class="ti ti-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@elseif($paginator->total() > 0)
    <div class="admin-pagination single-page">
        <div class="pagination-info">
            Tổng số: <strong>{{ number_format($paginator->total()) }}</strong> bản ghi
        </div>
    </div>
@endif
