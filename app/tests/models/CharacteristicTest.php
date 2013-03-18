<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class CharacteristicTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    public function testShouldNotSaveSinceIsEmbeddedOnly()
    {
        $charac = f::instance( 'Characteristic' );
        $this->assertTrue( $charac->isValid() );

        // Should fail because Characteristic have no
        // colletion. This model should be embedded into
        // Categories.
        $this->assertFalse( $charac->save() );
    }

    /**
     * Should validate characteristic
     *
     */
    public function testShouldValidateCharacteristic()
    {
        $charac = new Characteristic;

        $charac->name = 'Material';

        // Should return false since the type is not set
        $this->assertFalse( $charac->isValid() );

        $charac->type = 'int';

        // Should return true since the tipe is set
        $this->assertTrue( $charac->isValid() );

        $charac->type = 'option';

        // Should return false since the 'option' type
        // needs values array
        $this->assertFalse( $charac->isValid() );

        $charac->values = ['Madeira','Metal','Vidro'];

        // Should return true since the tipe is set
        $this->assertTrue( $charac->isValid() );
    }

    public function testShouldDisplayLayout()
    {
        $charac = f::instance( 'Characteristic' );
        $value = "<span class='muted'>&ltvalor&gt</span>";

        // Without any layout
        $this->assertEquals( $value, $charac->displayLayout() );

        // With appended 'A'
        $charac->setAttribute('layout-pos', 'A');
        $this->assertEquals( $value.' A', $charac->displayLayout() );

        // With appended 'A' and prepended 'B'
        $charac->setAttribute('layout-pre', 'B');
        $this->assertEquals( 'B '.$value.' A', $charac->displayLayout() );

        // With prepended 'B' only
        $charac->setAttribute('layout-pos', '');
        $this->assertEquals( 'B '.$value, $charac->displayLayout() );
    }

    public function testSetValuesShouldExplodeString()
    {
        $charac = f::instance( 'Characteristic' );

        $messed_string = 'Madeira,Metal  , Vidro, Bacon';
        $should_become = ['Madeira','Metal','Vidro','Bacon'];

        $charac->values = $messed_string;
        $this->assertEquals($should_become, $charac->values);
    }

    public function testShouldGetValuesStr()
    {
        $charac = f::instance( 'Characteristic' );

        $this->assertEquals('Qualquer', $charac->getValuesStr());

        $charac->type = 'option';
        $charac->values = 'Madeira,Metal  , Vidro, Bacon';

        $this->assertEquals('Madeira, Metal, Vidro, Bacon', $charac->getValuesStr());
    }

    public function testGetTypeStr()
    {
        $charac = f::instance( 'Characteristic' );

        $charac->type = 'int';
        $this->assertEquals('Numero', $charac->getTypeStr());

        $charac->type = 'float';
        $this->assertEquals('Numero decimal', $charac->getTypeStr());

        $charac->type = 'option';
        $this->assertEquals('Opções', $charac->getTypeStr());

        $charac->type = 'string';
        $this->assertEquals('Livre', $charac->getTypeStr());
    }
}
