<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Laramongo</title>
            {{ Basset::show('admin.css') }}
    </head>
    <body>

        {{-- Navigation bar at top of the page --}}
        <div class="navbar navbar-static-top">
            <div class="navbar-inner">
                <a class="brand" href="/">Laramongo</a>

                {{-- Navigation links --}}
                @if( Auth::user() )
                    <ul class="nav">

                        <li {{ (Request::is('admin/categor*')) ? 'class="active"' : '' }}>
                            {{ HTML::action( 'Admin\CategoriesController@index', 'Categorias' ) }}
                        </li>

                        <li {{ (Request::is('admin/product*')) ? 'class="active"' : '' }}>
                            {{ HTML::action( 'Admin\ProductsController@index', 'Produtos' ) }}
                        </li>

                        <li>
                            {{ HTML::action( 'UsersController@logout', 'Logout' ) }}
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

        <div class='maincontent'>
            @yield('content')
        </div>
        
        {{ Basset::show('js_bottom.js') }}
    </body>
</html>
