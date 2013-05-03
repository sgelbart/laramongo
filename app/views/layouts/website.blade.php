<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>Laramongo - Tudo para você reformar e decorar sua casa.</title>


            <link rel="stylesheet" href="/assets/css/templates/base/image-tagging.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/main.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/product_page.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/subcategories_page.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/tiled_category.css" >

    </head>
    <body>

        @include('layouts.website._tag_manager')

        @include('layouts.website._header')

        <div class='content'>
            <div class='sidebar'>
                @include('layouts.website._sidebar')
            </div>

            <div class='main'>
                @include('layouts.website._horizontalbar')

                @yield('content')
            </div>

            <div class='footer'>
                Copyright © {{ date('Y') }} Laramongo, todos os direitos Reservados
            </div>

        </div>

        {{ Basset::show('js_global.js') }}
        {{ Basset::show('js_bottom.js') }}

    </body>
</html>
