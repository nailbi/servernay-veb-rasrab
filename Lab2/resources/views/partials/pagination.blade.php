{{-- Шаблон ссылок пагинации: «назад», номера страниц, «вперёд» --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Страницы">
        @if ($paginator->onFirstPage())
            <span class="page-link is-disabled">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev">‹</a>
        @endif

        @foreach ($elements as $element)
            {{-- Разделитель «...» --}}
            @if (is_string($element))
                <span class="page-link is-disabled">{{ $element }}</span>
            @endif

            {{-- Массив ссылок на страницы --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next">›</a>
        @else
            <span class="page-link is-disabled">›</span>
        @endif
    </nav>
    <p class="pagination-info">
        Показаны {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} из {{ $paginator->total() }}
    </p>
@endif
