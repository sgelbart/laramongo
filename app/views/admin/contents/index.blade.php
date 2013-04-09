@section ('content')
    <h2>
        Conteúdo
    </h2>

    <div class="navbar">
        <div class="navbar-inner">
            <div class="btn-group pull-right">
                <a href='{{ URL::action( 'Admin\ProductsController@import' ) }}' class='btn btn-primary'>
                    Novo Conteúdo
                </a>
                <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ URL::action( 'Admin\ProductsController@import' ) }}"><i class="icon-th-list"></i> Importar produtos</a></li>
                    <li><a href="{{ URL::action( 'Admin\ProductsController@import', ['conjugated'=>true] ) }}"><i class="icon-th"></i> Importar conjugados</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-arrow-down"></i> Exportar produtos</a></li>
                </ul>
            </div>

            <form class="form-search navbar-form pull-left" data-ajax="true" data-quicksearch-url='{{ URL::action( 'Admin\ProductsController@index' ) }}'>
                <input 
                    type="text" name="search" value="{{ Input::get('search') }}"
                    class="input-medium search-query" data-submit-on-type='true'
                    placeholder="Pesquisar"
                >
                &nbsp;
                <label class="checkbox">
                    <input type="hidden" name="deactivated" value="false">
                    <input
                        type="checkbox" name="deactivated" value="true" 
                        {{ (Input::get('deactivated') == 'true') ? 'checked="checked"' : '' }}
                        data-submit-on-click='true'
                    >
                    Todos
                </label>
                {{-- <button type="submit" class="btn">Buscar</button> --}}
            </form>
        </div>
    </div>

    <div id='content-index'>
        @include ('admin.contents._list')
    </div>
@stop
