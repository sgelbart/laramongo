<div class='facets-sidebar'>

    @if( count(Input::get('filters', array())) )
        <h3>Filtros</h3>

        @foreach( array_keys($facets) as $facetName )
            @if( array_key_exists(clean_case($facetName), Input::get('filters')) )
                <div class='chossen-filter'>

                    <a href="{{
                        URL::action(
                            'CategoriesController@show',
                            ['id'=>$category->_id, 'filters' => 
                                array_diff_key(
                                    Input::get('filters'),
                                    [clean_case($facetName) => true]
                                )
                            ]
                        ) 
                    }}">
                        &times;
                    </a>

                    <span class='filter-name'>
                        {{ $facetName }}
                    </span>
                    <span class='filter-value'>
                        {{ Input::get('filters')[clean_case($facetName)] }}
                    </span>
                </div>
            @endif
        @endforeach
    @endif

    @foreach( array_keys($facets) as $facetName )

        @if( isset($facets[$facetName]['terms']) && count($facets[$facetName]['terms']) > 1 )

            <h3 class='single-facet' href="#">{{ $facetName }}</h3>
            @foreach( $facets[$facetName]['terms'] as $option )
                <a href="{{
                        URL::action(
                            'CategoriesController@show',
                            ['id'=>$category->_id, 'filters' => 
                                array_merge(
                                    [ clean_case($facetName)=>$option['term'] ],
                                    Input::get('filters', array() )
                                )
                            ]
                        ) 
                    }}" class='option'>
                    {{ ucfirst($option['term']) }} 
                    <small>({{ $option['count'] }})</small>
                </a>
            @endforeach
        @endif

        @if( isset($facets[$facetName]['entries']) && count($facets[$facetName]['entries']) > 1 )

            <h3 class='single-facet' href="#">{{ $facetName }}</h3>
            @foreach( $facets[$facetName]['entries'] as $option )
                <a href="{{
                    URL::action(
                        'CategoriesController@show',
                        ['id'=>$category->_id, 'filters' => 
                            array_merge(
                                [ clean_case($facetName)=>$option['key'] ],
                                Input::get('filters', array() )
                            )
                        ]
                    ) 
                    }}" class='option'>
                    {{ ucfirst($option['key']) }}
                    <small>({{ $option['count'] }})</small>
                </a>
            @endforeach
        @endif
    @endforeach
</div>
