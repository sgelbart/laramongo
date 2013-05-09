@section('content')
    <h1>Produtos</h1>

    @if($products)
        @foreach($products as $product)
            <h3>
                <a href="{{URL::Action('ProductsController@show', ['id' => $product->_id])}}">
                    {{ $product->name }}
                </a>
            </h3>
        @endforeach
    @else
        Não foram encontrados produtos.
    @endif

    <hr>

    <h1>Categorias</h1>

    @if($categories)
        @foreach($categories as $category)
            <h3>
                <a href="{{URL::Action('CategoriesController@show', ['id' => $category->_id])}}">
                    {{ $category->name }}
                </a>
            </h3>
        @endforeach
    @else
        Não foram encontrados categorias.
    @endif

    <hr>

    <h1>Conteúdos</h1>
    @if($contents)
        @foreach($contents as $content)
            <h3>
                <a href="{{URL::Action('ContentsController@show', ['slug' => $content->slug ])}}">
                        {{ $content->name }}
                </a>
            </h3>
        @endforeach
    @else
        Não foram encontrados conteúdos.
    @endif

    <hr>
@stop
