<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>Laramongo - Tudo para você reformar e decorar sua casa.</title>


        @if(Template::getName() == 'default' || !Template::getName())
            <link rel="stylesheet" href="/assets/css/templates/base/image-tagging.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/main.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/product_page.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/subcategories_page.css" >
            <link rel="stylesheet" href="/assets/css/templates/base/tiled_category.css" >
        @else
            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/common.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/image-tagging.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/main.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/menu.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/product-page.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/sidebar.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/subcategories_page.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/tiled_category.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/topbar.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/old_layout/css/variables.css" >

            <link rel="stylesheet" href="/assets/css/templates/responsive/thumbnail.css" >
            <link rel="stylesheet" href="/assets/css/templates/responsive/sugestions.css" >
            <link rel="stylesheet" href="/assets/css/templates/responsive/product-information.css" >
            <link rel="stylesheet" href="/assets/css/templates/responsive/family.css" >
            <link rel="stylesheet" href="/assets/css/templates/responsive/buttons.css" >
            <link rel="stylesheet" href="/assets/css/templates/responsive/base.css" >
        @endif
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
