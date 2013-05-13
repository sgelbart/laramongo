@if($products && !Request::Ajax())
    <h1>Produtos</h1>
    @foreach($products as $product)
        <div class='searched-item'>
            <h3>
                <a href="{{URL::Action('ProductsController@show', ['id' => $product->_id])}}">
                    {{ $product->name }}
                </a>
            </h3>
        </div>
    @endforeach
@endif

@if($categories)
    <h1>Categorias</h1>
    @foreach($categories as $category)
        <div class='searched-item'>
            <img src="{{$category->imageUrl()}}" alt="">
            <h3>
                <a href="{{URL::Action('CategoriesController@show', ['id' => $category->_id])}}">
                    {{ $category->name }}
                </a>
            </h3>
        </div>
    @endforeach
@endif

@if($contents)
    <h1>Conteúdos</h1>
    @foreach($contents as $content)
        <div class='searched-item'>
            <h3>
                <img src="http://logonoid.com/images/leroy-merlin-logo.png" alt="">
                <a href="{{URL::Action('ContentsController@show', ['slug' => $content->slug ])}}">
                        {{ $content->name }}
                </a>
            </h3>
        </div>
    @endforeach
@endif
