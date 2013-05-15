<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Laramongo</title>
        <link rel="stylesheet" href="/assets/css/admin/bootstrap.css">
        <link rel="stylesheet" href="/assets/css/admin/chosen.css">
        <link rel="stylesheet" href="/assets/css/admin/image-tagging.css">
        <link rel="stylesheet" href="/assets/css/admin/jquery-ui.css">
        <link rel="stylesheet" href="/assets/css/admin/main.css">
        <link rel="stylesheet" href="/assets/css/admin/taginput.css">
    </head>
    <body>

        {{-- Navigation bar at top of the page --}}
        <div class="navbar navbar-static-top">
            <div class="navbar-inner">
                <a class="brand" href="/">Laramongo</a>

                {{-- Navigation links --}}
                @if( true ) {{-- Auth::user --}}
                    <ul class="nav">

                        <li {{ (Request::is('admin/categor*')) ? 'class="active"' : '' }}>
                            {{ Html::linkAction( 'Admin\CategoriesController@index', 'Categorias' ) }}
                        </li>

                        <li {{ (Request::is('admin/product*')) ? 'class="active"' : '' }}>
                            {{ Html::linkAction( 'Admin\ProductsController@index', 'Produtos' ) }}
                        </li>

                        <li {{ (Request::is('admin/content*')) ? 'class="active"' : '' }}>
                            {{ Html::linkAction( 'Admin\ContentsController@index', 'Conteúdo' ) }}
                        </li>

                        <li {{ (Request::is('admin/synonymous*')) ? 'class="active"' : '' }} >

                        </li>
                            <ul class="nav">
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  Busca
                                  <b class="caret"></b>
                                </a>

                                <ul class="dropdown-menu">
                                  <li>
                                    <a href="{{ URL::action('Admin\SynonymsController@index') }}">
                                        Sinônimos
                                    </a>
                                  </li>

                                  <li>
                                    <a href="{{ URL::action('Admin\WarningsController@index') }}">
                                        Palavras não encontradas
                                    </a>
                                  </li>
                                </ul>
                              </li>
                            </ul>
                        <li>
                            {{ Html::linkAction( 'UsersController@logout', 'Logout' ) }}
                        </li>
                    </ul>
                @endif
            </div>
        </div>

        @if (Session::get('flash'))
            <div class='alert alert-info flash'>
                {{ Session::get('flash') }}
            </div>
        @endif

        @if (Session::get('flash_error'))
            <div class='alert alert-error flash'>
                @if (is_array(Session::get('flash_error')))
                    <b>{{ Session::get('flash_error')[0] }}</b>
                    {{ (isset(Session::get('flash_error')[1]) ? Session::get('flash_error')[1] : '' ) }}
                @else
                    {{ Session::get('flash_error') }}
                @endif
            </div>
        @endif

        <div class='maincontent'>
            @yield('content')
        </div>

        {{ Basset::show('js_global.js') }}
        {{ Basset::show('js_bottom.js') }}
    </body>
</html>
