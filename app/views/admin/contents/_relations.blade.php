<h3>{{ l('content.related_to') }}</h3>
<p>{{ l('content.related_to_explaination') }}</p>

<table class="table table-bordered table-striped">
    @foreach ($content->products() as $relatedProd)
        <tr>
            <td>
                {{ $relatedProd->_id }}
            </td>
            <td>
                {{ $relatedProd->name }}
            </td>
            <td>
                <div class="btn-group">
                    <a class='btn' href='{{ URL::action('Admin\ProductsController@show', ['id'=>$relatedProd->_id]) }}'>
                        <i class='icon-share-alt'></i>
                    </a>
                    <a
                        class='btn btn-danger' data-method="DELETE"
                        href='{{ URL::action('Admin\ContentsController@removeProduct', ['id'=>$content->_id, 'product_id'=>$relatedProd->_id]) }}'
                    >
                        <i class='icon-remove icon-white'></i>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
</table>

<hr>

<h3>{{ l('content.add_product') }}</h3>
<p>{{ l('content.add_product_explaination') }}</p>
<form class="form-search" data-ajax="true" action='{{ URL::action( 'SearchController@products', ['view'=>'relate_products'] ) }}'>
    <input 
        type="text" name="search" value="{{ Input::get('search') }}"
        class="input-block-level search-query" data-submit-on-type='true'
        placeholder="Pesquisar"
    >
    {{ Form::hidden('aditional_id', $content->_id) }}
</form>

<div id='product-index' data-conjugatedId='{{ $content->_id }}'></div>
