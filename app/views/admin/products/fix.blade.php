@section ('content')
    <h2>
        Correção de produtos
    </h2>

    <div class='well'>
        <p>
            Os produtos a seguir não possuem valores validos para uma ou mais caracteristicas.
            Na tabela abaixo é possível ajustar os valores para as caracteristicas inválidas.
        </p>
        <p>
            Ao final do processo, pressione o botão salvar alterações, no final da pagina, para
            que as alterações sejam efetivadas.
        </p>
    </div>

    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>
                    LM
                </th>
                @foreach( $category->characteristics() as $char )
                    <th>
                        {{ $char->name }}
                    </th>
                @endforeach
                <th>
                    Ações
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $products as $product )
                {{
                    Form::open([
                        'url' => URL::action('Admin\ProductsController@update', ['id'=>$product->_id]),
                        'method' => 'PUT' 
                    ])
                }}
                    <?php $product->isValid(); ?>
                    <tr class='error'>
                        <td>
                            {{ Form::hidden('_id', $product->_id, ['class'=>'disabled input-small', 'readonly'=>'readyonly']) }}
                            <span class='padding-as-input'>{{ $product->_id }}</span>
                        </td>
                        @foreach( $category->characteristics() as $char )
                            <?php $value = array_get($product->details, clean_case($char->name)) ?>
                            <td>
                                @if ($product->errors->has($char->name) )
                                    {{ Form::text('pname', $value, ['class'=>'error input-mini']) }}
                                @else
                                    <span class='padding-as-input'>{{ $value }}</span>
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <button type="submit" class="btn btn-primary btn-marginless" id="submit-{{ $product->_id }}-fix">
                                <i class="icon-ok icon-white"></i>
                            </button>
                        </td>
                    </tr>
                {{ Form::close() }}
            @endforeach
        </tbody>
    </table>

    <div class='form-actions'>
        {{ HTML::action( 'Admin\CategoriesController@edit', 'Voltar', ['id'=>$category->_id], ['class'=>'btn'] ) }}
    </div>

@stop
