@if ($paginator->lastPage() > 1)
    <ul class="pagination">
        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="Previous"
               @if($paginator->currentPage() == 1)
                       onclick="return false; "
               @endif
            >
                <span aria-hidden="true">«</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    @if($paginator->currentPage() == $i)
                        <a class="page-link btn btn-default" href="{{ $paginator->url($i) }}"><b>{{$i}}</b></a>
                        @else
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{$i}}</a>
                        @endif
            </li>
        @endfor
        <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}" aria-label="Next"
                   @if($paginator->currentPage() == $paginator->lastPage())
                   onclick="return false; "
                        @endif
                >
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
    </ul>
@endif