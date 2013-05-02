<?php

use Mockery as m;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;
use Laramongo\StoresProductsIntegration\CsvParser;
use Laramongo\StoresProductsIntegration\StoreProduct;

class TestCsvParser extends Zizaco\TestCases\TestCase
{
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection('temp_storesProductsIntegration');
    }

    public function testShouldParseLine()
    {
        $parser = new CsvParser;

        $line = [
            'COD_FILIAL' => 27,
            'LM' => 8800001,
            'PRC_ACONSELHADO' => 9.9,
            'PRC_FND_SECAO' => 10.9,
            'PRC_PROMOCIONAL' => 7.0,
            'TOP' => 1,
            'EMBALAGEM' => 1.3,
            'UNIDADE' => 'M2',
        ];

        $this->assertTrue( $parser->parseLine($line) );

        $result = StoreProduct::first(['_id'=>8800001]);

        $this->assertNotNull($result);

        $jsonResult = $result->toArray();
        $should_be = json_decode(
            '{
                "_id": 8800001,
                "unit": "m2",
                "pack": 1.3,
                "stores": {
                    "sorocaba": {
                        "top": 1,
                        "promotional_price": 7.0,
                        "background_section": 10.9,
                        "recommended_retail_price": 9.9
                    }
                }
            }',
            true
        );

        $this->assertEquals($should_be, $jsonResult);
    }

    public function testShouldParseMultipleLines()
    {
        $parser = new CsvParser;

        // Parses the line of the previous method
        $this->testShouldParseLine();

        $line = [
            'COD_FILIAL' => 3,
            'LM' => 8800001,
            'PRC_ACONSELHADO' => 9.9,
            'PRC_FND_SECAO' => 12.9,
            'PRC_PROMOCIONAL' => 8.0,
            'TOP' => 2,
            'EMBALAGEM' => 1.3,
            'UNIDADE' => 'M2',
        ];

        $this->assertTrue( $parser->parseLine($line) );

        $result = StoreProduct::first(['_id'=>8800001]);

        $this->assertNotNull($result);

        $jsonResult = $result->toArray();

        // The StorePrice should contain values for both stores (sorocaba and campinas).
        $should_be = json_decode(
            '{
                "_id": 8800001,
                "unit": "m2",
                "pack": 1.3,
                "stores": {
                    "sorocaba": {
                        "top": 1,
                        "promotional_price": 7.0,
                        "background_section": 10.9,
                        "recommended_retail_price": 9.9
                    },
                    "campinas": {
                        "top": 2,
                        "promotional_price": 8.0,
                        "background_section": 12.9,
                        "recommended_retail_price": 9.9
                    }
                }
            }',
            true
        );

        $this->assertEquals($should_be, $jsonResult);
    }

    public function testParseFile()
    {
        $parser = new CsvParser;
        $this->assertTrue( $parser->parseFile('/tests/assets/full-price-file.txt') );

        // Check if the StoreProducts has been saved
        $this->assertNotNull(StoreProduct::first(['_id'=>8800001]));
        $this->assertNotNull(StoreProduct::first(['_id'=>8800002]));
        $this->assertNotNull(StoreProduct::first(['_id'=>8800003]));

        // The StoreProduct bellow doesn't exists. Should return false or null
        $this->assertTrue(! StoreProduct::first(['_id'=>8800004])); 
    }

    public function testShouldGetStoreNameById()
    {
        $parser = new CsvParser;

        $this->assertEquals('interlagos', $parser->getStoreNameById(1));
        $this->assertEquals('rib_preto', $parser->getStoreNameById(2));
        $this->assertEquals('campinas', $parser->getStoreNameById(3));
        $this->assertEquals('contagem', $parser->getStoreNameById(4));
        $this->assertEquals('raposo_tav', $parser->getStoreNameById(5));
        $this->assertEquals('rio_norte', $parser->getStoreNameById(7));
        $this->assertEquals('tiete', $parser->getStoreNameById(8));
        $this->assertEquals('curitiba', $parser->getStoreNameById(9));
        $this->assertEquals('s_caetano', $parser->getStoreNameById(10));
        $this->assertEquals('rio_barra', $parser->getStoreNameById(11));
        $this->assertEquals('morumbi', $parser->getStoreNameById(12));
        $this->assertEquals('brasilia', $parser->getStoreNameById(13));
        $this->assertEquals('sjcampos', $parser->getStoreNameById(15));
        $this->assertEquals('rjafet', $parser->getStoreNameById(16));
        $this->assertEquals('bangu', $parser->getStoreNameById(17));
        $this->assertEquals('goiana', $parser->getStoreNameById(18));
        $this->assertEquals('poa', $parser->getStoreNameById(19));
        $this->assertEquals('bh_sul', $parser->getStoreNameById(20));
        $this->assertEquals('niteroi', $parser->getStoreNameById(21));
        $this->assertEquals('anhanguera', $parser->getStoreNameById(22));
        $this->assertEquals('taguatinga', $parser->getStoreNameById(23));
        $this->assertEquals('centernort', $parser->getStoreNameById(24));
        $this->assertEquals('jacarepagu', $parser->getStoreNameById(26));
        $this->assertEquals('sorocaba', $parser->getStoreNameById(27));
        $this->assertEquals('brasilia_n', $parser->getStoreNameById(28));
        $this->assertEquals('uberlandia', $parser->getStoreNameById(29));
        $this->assertEquals('s_leopoldo', $parser->getStoreNameById(32));
        $this->assertEquals('londrina', $parser->getStoreNameById(33));
        $this->assertEquals('sj_r_preto', $parser->getStoreNameById(34));
        $this->assertEquals('curitibatu', $parser->getStoreNameById(35));
        $this->assertEquals('bh_norte', $parser->getStoreNameById(36));
    }
}
