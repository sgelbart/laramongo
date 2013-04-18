<div id="tagged-product-popover_{{ $product->_id }}" class="tagged-product-popover">
    <img src="{{ $product->imageUrl() }}">
    <b class='name'>{{ $product->name }}</b>
    <b>Categoria:</b> {{ $product->category()->name }}
</div>
