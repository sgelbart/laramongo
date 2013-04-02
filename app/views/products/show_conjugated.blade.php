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

        <h2>Composto por</h2>
        @foreach($conjProducts as $product)

            <h3>{{ Html::linkAction('ProductsController@show', $product->name, ['id'=>$product->_id] ) }}</h3>

            <?php $category = $product->category(); ?>
            @include('products._product_characteristics')

        @endforeach

    </div>

@stop
