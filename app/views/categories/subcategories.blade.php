@section ('content')

    <p>
        Seu caminho foi: 
        {{ Html::to( '/', 'Home' ) }} >
        {{ $category->name }}
    </p>

    <div class='tiled_subcategories'>
        <h1>{{ ucfirst($category->name) }}</h1>

        <div class='img_frame'>
            <div class='img' style='background-image: url({{ $category->imageUrl() }});'>
                <div class='cat_desc'>
                    <strong>{{ ucfirst($category->name) }}</strong>
                    <p>{{ ucfirst($category->description) }}</p>
                </div>
            </div>
        </div>

        <h2>Sub-categorias</h2>

        <div class='subcategories'>
            @foreach( $subCategories as $subCategory )
                <a href='{{ URL::action('CategoriesController@show', ['id'=>$subCategory->_id]) }}'>
                    <div class='subcategory-tile'>
                        <div class='img' style='background-image: url({{ $subCategory->imageUrl() }});'>
                            <div class='cat_desc'>
                                <strong>{{ ucfirst($subCategory->name) }}</strong>
                                <p>{{ ucfirst($subCategory->description) }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>

@stop
