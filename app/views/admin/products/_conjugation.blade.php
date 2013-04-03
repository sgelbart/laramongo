<h3>Composto por:</h3>
<p>O conjugado atual é composto pelos produtos a seguir.</p>
<table class="table table-bordered table-striped">
    @foreach ($product->products() as $conjProduct)
        <tr>
            <td>
                {{ $conjProduct->_id }}
            </td>
            <td>
                {{ $conjProduct->name }}
            </td>
            <td>
                <div class="btn-group">
                    <a class='btn' href='{{ URL::action('Admin\ProductsController@show', ['id'=>$conjProduct->_id]) }}'>
                        <i class='icon-share-alt'></i>
                    </a>
                    <a href="" class='btn btn-danger'>
                        <i class='icon-remove icon-white'></i>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
</table>


<h3>Adicionar produto:</h3>
<p>Use a barra de busca abaixo para adicionar um produto a composição.</p>
<form class="form-search" data-ajax="true" action='{{ URL::action( 'SearchController@products' ) }}'>
    <input 
        type="text" name="search" value="{{ Input::get('search') }}"
        class="input-block-level search-query" data-submit-on-type='true'
        placeholder="Pesquisar"
    >
</form>

<div id='product-index'></div>
