<?php
    $price = $product->getPrice();
?>

@if( $price['base_price'] > 0 && $price['promotional_price'] <= ($price['base_price']*0.95) )
    <div>
        de
        <span class='base_price crossed'>
        R$ {{ str_replace('.', ',', number_format($price['base_price'],2)) }}
        </span>
        por:
    </div>
    <span class='promotional_price'>
    R$ {{ str_replace('.', ',', number_format($price['promotional_price'],2)) }}
    </span>
@elseif( $price['base_price'] > 0 && $price['promotional_price'] >= $price['base_price'] )
    <span class='base_price'>
    R$ {{ str_replace('.', ',', number_format($price['base_price'],2)) }}
    </span>
@elseif( $price['promotional_price'] > 0 && $price['promotional_price'] <= $price['base_price'] )
    <span class='base_price'>
    R$ {{ str_replace('.', ',', number_format($price['promotional_price'],2)) }}
    </span>
@endif
