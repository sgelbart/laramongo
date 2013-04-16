<img src="{{ $instance->imageUrl() }}"/>

<div class="technical-features">
  <p>
    <b>LM:</b>
    {{ $instance->_id }}
  </p>

  <p>
    <b>Categoria:</b>
        {{ $instance->category()->name }}
  </p>
</div>
