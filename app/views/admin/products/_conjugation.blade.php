<form class="form-search" data-ajax="true" action='{{ URL::action( 'SearchController@products' ) }}'>
    <input 
        type="text" name="search" value="{{ Input::get('search') }}"
        class="input-block-level search-query" data-submit-on-type='true'
        placeholder="Pesquisar"
    >
</form>
