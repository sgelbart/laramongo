<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class ConjugatedProductTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
    }

    public function testShouldValidate()
    {
        $this->cleanCollection( 'products' );

        // A valid conjugated product
        $conjProduct = $this->aConjugatedProduct();
        $this->assertTrue($conjProduct->isValid());

        // A duplicated conjugated product (the same lm combination)
        // should not be valid
        $dupliConjProduct = f::instance( 'ConjugatedProduct' );
        $dupliConjProduct->conjugated = $conjProduct->conjugated;
        $this->assertFalse($dupliConjProduct->isValid());

        // Following the above, equal lm combinations should be
        // valid for the same _id (I.E: Update)
        $dupliConjProduct->_id = $conjProduct->_id;
        $this->assertTrue($dupliConjProduct->isValid());

        // Should not save a conjugated product with an invalid
        // LM
        $conjProduct = $this->aConjugatedProduct();
        $conjProduct->conjugated = array_merge($conjProduct->conjugated,['123123']);
        $this->assertFalse($conjProduct->isValid());

    }

    private function aConjugatedProduct()
    {
        for ($i=0; $i < 4; $i++) {
            $products[$i] = f::create( 'Product', ['_id'=>'A'.rand().$i] );
            $products[$i]->lm = (string)$products[$i]->_id;

            if($products[$i]->lm)
            {
                $lms[] = $products[$i]->lm;
            }
        }

        $conjProduct = f::instance( 'ConjugatedProduct' );
        $conjProduct->conjugated = $lms;
        $conjProduct->save();

        return $conjProduct;
    }
}
