@section ('content')

    <p>
        Seu caminho foi: 
        {{ HTML::to( '/', 'Home' ) }} >
        {{ HTML::action( 'CategoriesController@show', $category->name, ['id'=>$category->_id] ) }} >
        {{ $product->name }}
    </p>

    <div class='product_page'>
        <h1>{{ ucfirst($product->name) }}</h1>

        {{ HTML::image($product->imageUrl(1,600)) }}

        <p>{{ $product->description }}</p>


        <table class='table'>
            @foreach ($category->characteristics() as $charac)
                {{ snake_case($charac->name) }}
                @if ( is_array($product->details) && isset($product->details[snake_case($charac->name)]) )
                    <tr>
                        <td class='attr_header'>
                            {{ $charac->name }}
                        </td>
                        <td>
                            {{ 
                                $charac->displayLayout($product->details[snake_case($charac->name)])
                            }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>

    </div>

@stop
