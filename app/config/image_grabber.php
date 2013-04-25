<?php

return array(
    'origin_url' => array(
        'product' => 'http://www.leroymerlin.com.br/resources/images/products/product/{lm}_{angle}_{size}.jpg',
        'chave_entrada' => 'http://www.leroymerlin.com.br/resources/images/chaveEntrada/chave-entrada-{lm}.jpg'
    ),
    'destination_url' => array(
        'product' => 'public/uploads/img/{collection}/{name_product}_{lm}_{angle}_{size}.jpg',
        'chave_entrada' => 'public/uploads/img/categories/chave_entrada_{lm}_{name_chave_entrada}.jpg'
    ),
    'proxy' => 'http://10.56.22.70',
    'proxy_port' => '8080',
    'user' => 'central\lalves:leroymerlin1',
    'image' => array(
        'sizes' => array('150', '300', '600'),
        'angles' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10')
    )
);
