<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Laramongo - Tudo para você reformar e decorar sua casa.</title>

        <link rel="stylesheet" href="/assets/css/region_selection.css" >

</head>
<body>

    @include('layouts.website._tag_manager')

    <div class='region-selector'>
        {{ Form::open( array( 'url' => '/regions/store' ) ) }}

            {{
                Form::label('region',
                    'Para conferir nossas ofertas, disponibilidade de '.
                    'produtos e preços, informe abaixo a sua região.'
                ) 
            }}
            {{
                Form::select('region', array(
                        'grande_sao_paulo'      => 'Grande São Paulo',
                        'rio_de_janeiro'        => 'Rio de Janeiro',
                        'campinas'              => 'Campinas',
                        'grande_porto_alegre'   => 'Grande Porto Alegre',
                        'rib_preto'             => 'Ribeirão Preto',
                        'sorocaba'              => 'Sorocaba',
                        'curitiba'              => 'Curitiba',
                        'uberlandia'            => 'Uberlandia',
                        'brasilia'              => 'Brasilia',
                        'sao_jose_dos_campos'   => 'São Jose dos Campos',
                        'sao_jose_do_rio_preto' => 'São Jose do Rio Preto',
                        'goiania'               => 'Goiania',
                        'belo_horizonte'        => 'Belo Horizonte',
                        'londrina'              => 'Londrina'
                    )
                )
            }}

            {{ Form::submit('Selecionar') }}
        {{ Form::close() }}
    </div>

</body>
</html>
