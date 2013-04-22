@section ('content')

    <p>
        Seu caminho foi:
        {{ Html::link( '/', 'Home' ) }} >
        {{ $category->name }}
    </p>

    <div class='tiled_category'>
        <h1>{{ ucfirst($category->name) }}</h1>

        <div class='img_frame'>
            <div class='img' style='background-image: url({{ $category->imageUrl() }});'>
                <div class='cat_desc'>
                    <strong>{{ ucfirst($category->name) }}</strong>
                    <p>{{ ucfirst($category->description) }}</p>
                </div>
            </div>
        </div>

        <h2>Resultados</h2>

        <div class='products'>
            @include('templates.base.categories._products')

            <div data-nextpage="2" >
                {{ Html::image('assets/img/loading.gif') }}
                Carregando mais resultados...
            </div>

        </div>

    </div>

@stop
