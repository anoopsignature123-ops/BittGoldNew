@if ($paginator->hasPages())
    <nav class="pagination-gold" aria-label="Pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button type="button" disabled class="disabled">â¹</button>
        @else
            <button type="button" onclick="window.location='{{ $paginator->previousPageUrl() }}'">â¹</button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <button type="button" disabled class="dots">{{ $element }}</button>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button type="button" class="active" disabled>{{ $page }}</button>
                    @else
                        <button type="button" onclick="window.location='{{ $url }}'">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button type="button" onclick="window.location='{{ $paginator->nextPageUrl() }}'">âº</button>
        @else
            <button type="button" disabled class="disabled">âº</button>
        @endif
    </nav>
@endif
