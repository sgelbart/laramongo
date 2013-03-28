@if ($total_pages > 1)
    <div class="pagination pagination-centered">
        <ul>
            @if ($page > 1)
                <li><a href="?page={{ $page-1 }}&search={{ Input::get('search') }}&deactivated={{ Input::get('deactivated') }}">
                    {{ Lang::get('pagination.previous') }}
                </a></li>
            @else
                <li class="disabled"><a>
                    {{ Lang::get('pagination.previous') }}
                </a></li>
            @endif
            @for ($i = 1; $i <= $total_pages; $i++)
                <li{{ ($i == $page) ? ' class="active" ' : '' }}>
                    <a href="?page={{ $i }}&search={{ Input::get('search') }}&deactivated={{ Input::get('deactivated') }}">{{ $i }}</a>
                </li>
            @endfor
            @if ($i-1 != $page)
                <li><a href="?page={{ $page+1 }}&search={{ Input::get('search') }}&deactivated={{ Input::get('deactivated') }}">
                    {{ Lang::get('pagination.next') }}
                </a></li>
            @else
                <li class="disabled"><a>
                    {{ Lang::get('pagination.next') }}
                </a></li>
            @endif
        </ul>
    </div>
@endif
