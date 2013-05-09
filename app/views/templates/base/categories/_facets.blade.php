<div class='facets-sidebar'>
    @foreach( array_keys($facets) as $facetName )
        <h3 class='single-facet' href="#">{{ $facetName }}</h3>

        @foreach( $facets[$facetName]['terms'] as $option )
            <a href="#" class='option'>{{ ucfirst($option['term']) }} <small>({{ $option['count'] }})</small> </a>
        @endforeach
    @endforeach
</div>
