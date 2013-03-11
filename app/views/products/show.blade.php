@section ('content')

    <p>
        Seu caminho foi: 
        {{ HTML::to( '/', 'Home' ) }} >
        {{ HTML::action( 'CategoriesController@show', $category->name, ['id'=>$category->id] ) }} >
        {{ $product->name }}
    </p>

    <div class='product_page'>
        <h1>{{ ucfirst($product->name) }}</h1>

        {{ HTML::image($product->imageUrl(1,600)) }}

        <p>{{ $product->description }}</p>

        <table class='table'>
            @foreach ($product->attributes as $attr => $val)
                <tr>
                    <td class='attr_header'>
                        {{ $attr }}
                    </td>
                    <td>
                        {{ $val }}
                    </td>
                </tr>
            @endforeach
        </table>

    </div>

@stop
