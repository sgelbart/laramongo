@section ('content')
    <h2>
        Conteúdo
    </h2>

    <div class="navbar">
        <div class="navbar-inner">
            <div class="btn-group pull-right">
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#" id='btn-create-new-content'>
                    Novo Conteúdo
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ URL::action( 'Admin\ContentsController@createArticle' ) }}" id='btn-create-new-article'>
                            <i class="icon-file"></i> Novo artigo
                        </a>
                    </li>

                    <li>
                        <a href="{{ URL::action( 'Admin\ContentsController@createImage' ) }}" id='btn-create-new-image'>
                            <i class="icon-picture"></i> Nova imagem
                        </a>
                    </li>

                    <li>
                        <a href="{{ URL::action( 'Admin\ContentsController@createVideo' ) }}" id='btn-create-new-video'>
                            <i class="icon-film"></i> Novo vídeo embedado
                        </a>
                    </li>

                    <li>
                        <a href="{{ URL::action( 'Admin\ContentsController@createShop' ) }}" id='btn-create-new-video'>
                            <i class=" icon-shopping-cart"></i> Nova loja
                        </a>
                    </li>
                </ul>
            </div>

            <form class="form-search navbar-form pull-left" data-ajax="true" data-quicksearch-url='{{ URL::action( 'Admin\ContentsController@index' ) }}'>
                <input
                    type="text" name="search" value="{{ Input::get('search') }}"
                    class="input-medium search-query" data-submit-on-type='true'
                    placeholder="Pesquisar"
                >
                {{-- <button type="submit" class="btn">Buscar</button> --}}
            </form>
        </div>
    </div>

    <div id='content-index'>
        @include ('admin.contents._list')
    </div>
@stop
