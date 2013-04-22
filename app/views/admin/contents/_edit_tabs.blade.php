<ul class="nav nav-tabs">
    <li class="active" data-tab-of="content-form">
        <a>Base</a>
    </li>
    <li data-tab-of="content-relations">
        <a>Relacionamento</a>
    </li>
    @if ($content instanceOf ImageContent )
        <li data-tab-of="content-image-tagging">
            <a>Marcar Imagem</a>
        </li>
    @endif
</ul>
