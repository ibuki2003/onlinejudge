@if ($paginator->hasPages())
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
            <li class="page-item"><a class="page-link" href="{{$paginator->url(1)}}">1</a></li>
            
            {{--Before current--}}
            @php
                $i=0;
                while ( 1<<($i+1) < $paginator->currentPage())$i++;
            @endphp
            @for (; $i>=0; $i--)
                @if($paginator->currentPage()-(1<<$i)!=1)
                    <li class="page-item"><a class="page-link" href="{{$paginator->url($paginator->currentPage()-(1<<$i))}}">{{$paginator->currentPage()-(1<<$i)}}</a></li>
                @endif
            @endfor
        @endif
        {{--current--}}
        <li class="page-item active" aria-current="page"><span class="page-link">{{ $paginator->currentPage() }}</span></li>
        
        {{--After current--}}
        @for($i=0;$paginator->currentPage()+(1<<$i)<$paginator->lastPage();$i++)
            <li class="page-item"><a class="page-link" href="{{$paginator->url($paginator->currentPage()+(1<<$i))}}">{{$paginator->currentPage()+(1<<$i)}}</a></li>
        @endfor

        @if($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{$paginator->url($paginator->lastPage())}}">{{$paginator->lastPage()}}</a></li>
            {{-- Next Page Link --}}
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
