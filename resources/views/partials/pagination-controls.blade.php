@if($paginator->hasPages())
    <nav class="pagination-nav" aria-label="Product pagination">
        <a href="{{ $paginator->previousPageUrl() ?? '#' }}"
           class="pagination-btn {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
           @if($paginator->onFirstPage()) aria-disabled="true" tabindex="-1" @endif>
            Previous
        </a>

        <span class="pagination-status">
            Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        <a href="{{ $paginator->nextPageUrl() ?? '#' }}"
           class="pagination-btn {{ $paginator->hasMorePages() ? '' : 'disabled' }}"
           @if(!$paginator->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>
            Next
        </a>
    </nav>
@endif
