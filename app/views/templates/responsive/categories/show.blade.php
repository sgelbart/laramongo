@section('content')
    <p>
        Seu caminho foi:
        {{ Html::link( '/', 'Home' ) }} >
        {{ $category->name }}
    </p>

    <div class="family">
      <h2>Family of {{ ucfirst($category->name) }}</h2>
        <div class='photo'>
          <img src="{{ $category->imageUrl() }}">
        </div>

        <div class="description">
          <h3>{{ ucfirst($category->name) }}</h3>
          {{ $category->description }}
      </div>
    </div>


    <ul class="thumbnails" id="products-images">
        @foreach ($products as $product)
          <li class='responsive'>
            <div class="thumbnail" >
                <img src="{{ $product->imageUrl() }}" alt="">

              <div class="caption">
                <p><b>Nome:</b> {{ $product->name }}</p>
                <p><b>Descrição:</b> {{ substr($product->description, 0, 120) . ' ...' }}</p>

                <a href="/product/{{$product->_id}}" class="button button-block">Veja sobre o produto</a>
              </div>
            </div>
          </li>
        @endforeach
    </ul>
@stop
