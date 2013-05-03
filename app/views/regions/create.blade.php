{{ Form::open( array( 'url' => '/regions/store' ) ) }}

    {{ Form::label('region', 'Selecione uma região:') }}
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
