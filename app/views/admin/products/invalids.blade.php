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
    @if ( $products->count() )

        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>
                        LM
                    </th>
                    @foreach( $category->characteristics() as $char )
                        <th>
                            {{ $char->name }}
                            @if ( $char->getAttribute('layout-pos') )
                                ({{ $char->getAttribute('layout-pos') }})
                            @endif
                        </th>
                    @endforeach
                    <th>
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $products as $product )
                    <?php $product->isValid(); ?>
                    <tr class='error' id='row-{{ $product->_id }}-fix'>
                        {{
                            Form::open([
                                'url' => URL::action('Admin\ProductsController@fix', ['id'=>$product->_id]),
                                'method' => 'PUT',
                                'data-ajax' => 'true'
                            ])
                        }}
                            <td>
                                {{ Form::hidden('_id', $product->_id, ['class'=>'disabled input-small', 'readonly'=>'readyonly']) }}
                                <span class='padding-as-input'>{{ $product->_id }}</span>
                            </td>
                            @foreach( $category->characteristics() as $char )
                                <?php $value = array_get($product->details, clean_case($char->name)) ?>
                                <td>
                                    @if ($product->errors && $product->errors->has($char->name) )
                                        @if ($char->type == 'option')
                                            {{ Form::select(clean_case($char->name), array_merge(array_combine($char->values, $char->values),[$value=>$value]), $value, ['class'=>'error input-small'] ) }}
                                        @else
                                            {{ Form::text(clean_case($char->name), $value, ['class'=>'error input-mini']) }}
                                        @endif
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
                        {{ Form::close() }}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class='well'>
            <i class='icon-ok'></i>
            Não existem produtos inválidos nessa categoria.
        </div>
    @endif

    <div class='form-actions'>
        {{ Html::linkAction( 'Admin\CategoriesController@edit', 'Voltar', ['id'=>$category->_id], ['class'=>'btn'] ) }}
    </div>

@stop
