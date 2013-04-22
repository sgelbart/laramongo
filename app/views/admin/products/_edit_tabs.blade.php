<ul class="nav nav-tabs">
    <li class="active" data-tab-of="product-form">
        <a>Base</a>
    </li>
    <li data-tab-of="product-characteristcs">
        <a>Caracteristicas</a>
    </li>
    @if( $product instanceof ConjugatedProduct )
        <li data-tab-of="product-conjugation">
            <a>Conjugação</a>
        </li>
    @endif
</ul>
