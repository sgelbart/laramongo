@section ('content')
    <h2>
        Correção de produtos
    </h2>

    @foreach ( $products as $product )
        {{ $product->name }}
    @endforeach

@stop
