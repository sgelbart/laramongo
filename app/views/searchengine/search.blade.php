@section('content')
    @if($products)
        <h1>Produtos</h1>
        @foreach($products as $product)
            <h3>
                <a href="{{URL::Action('ProductsController@show', ['id' => $product->_id])}}">
                    {{ $product->name }}
                </a>
            </h3>
        @endforeach
        <hr>
    @endif

    @if($categories)
        <h1>Categorias</h1>
        @foreach($categories as $category)
            <h3>
                <a href="{{URL::Action('CategoriesController@show', ['id' => $category->_id])}}">
                    {{ $category->name }}
                </a>
            </h3>
        @endforeach
        <hr>
    @endif

    @if($contents)
        <h1>Conteúdos</h1>
        @foreach($contents as $content)
            <h3>
                <a href="{{URL::Action('ContentsController@show', ['slug' => $content->slug ])}}">
                        {{ $content->name }}
                </a>
            </h3>
        @endforeach
        <hr>
    @endif
@stop
