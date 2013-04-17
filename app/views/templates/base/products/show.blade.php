@section ('content')

    <p>
        Seu caminho foi: 
        {{ Html::link( '/', 'Home' ) }} >
        {{ Html::linkAction( 'CategoriesController@show', $category->name, ['id'=>$category->_id] ) }} >
        {{ $product->name }}
    </p>

    <div class='product_page'>
        <h1>{{ ucfirst($product->name) }}</h1>

        {{ Html::image($product->imageUrl(1,600)) }}

        <p>{{ $product->description }}</p>


        @include('products._product_characteristics')

    </div>

@stop
