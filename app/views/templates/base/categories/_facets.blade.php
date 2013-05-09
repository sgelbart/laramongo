<div class='facets-sidebar'>
    @foreach( array_keys($facets) as $facetName )
        <h3 class='single-facet' href="#">{{ $facetName }}</h3>

        @if( isset($facets[$facetName]['terms']) )
            @foreach( $facets[$facetName]['terms'] as $option )
                <a href="#" class='option'>{{ ucfirst($option['term']) }} <small>({{ $option['count'] }})</small> </a>
            @endforeach
        @endif

        @if( isset($facets[$facetName]['entries']) )
            @foreach( $facets[$facetName]['entries'] as $option )
                <a href="#" class='option'>{{ ucfirst($option['key']) }} <small>({{ $option['count'] }})</small> </a>
            @endforeach
        @endif
    @endforeach


    <pre>
    {{ print_r($facets, true) }}
    </pre>
</div>
