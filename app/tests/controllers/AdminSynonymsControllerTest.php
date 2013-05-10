<?php

class AdminSynonymsControllerTest extends ControllerTestCase
{
    use TestHelper;

    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'synonyms' );
    }

    public function testShouldGetListOfSynonymous()
    {
        $this->requestAction('GET', 'Admin\SynonymsController@index');
        $this->assertRequestOk();
    }

    public function testShouldCreateSynonymous()
    {
        $this->requestAction('GET', 'Admin\SynonymsController@create');
        $this->assertRequestOk();
    }

    public function testShouldStoreSynonymous()
    {
        $input = testSynonymousProvider::attributesFor('simple_valid_sym');

        $this->withInput($input)->requestAction('POST', 'Admin\SynonymsController@store');
        $this->assertRedirection(URL::action('Admin\SynonymsController@index'));
    }

    public function testShouldNotStoreSynonymousIfHasError()
    {
        $input = testSynonymousProvider::attributesFor('simple_valid_sym', ['_id' => null ]);

        $this->withInput($input)->requestAction('POST', 'Admin\SynonymsController@store');
        $this->assertRedirection(URL::action('Admin\SynonymsController@index'));
    }

    public function testShouldEditSynonymous()
    {
        $input = testSynonymousProvider::saved('simple_valid_sym');
        $this->requestAction('GET', 'Admin\SynonymsController@edit', ['id' => $input->_id]);
        $this->assertRequestOk();
    }

    public function testShouldUpdateSynonymous()
    {
        $sym = testSynonymousProvider::saved('simple_valid_sym');
        $input = testSynonymousProvider::attributesFor('simple_valid_sym');

        $this->withInput($input)->requestAction('PUT', 'Admin\SynonymsController@update', [ 'id' => $sym->_id ] );
        $this->assertRedirection(URL::action('Admin\SynonymsController@index'));
    }

    public function testShouldNotUpdateSynonymousIfHasError()
    {
        $sym = testSynonymousProvider::saved('simple_valid_sym');
        $input = testSynonymousProvider::attributesFor('simple_valid_sym');

        $input['word'] = null;
        $input['related_word'] = null;

        $this->withInput($input)->requestAction('PUT', 'Admin\SynonymsController@update', [ 'id' => $sym->_id ]);
        $this->assertRedirection(URL::action('Admin\SynonymsController@edit', ['id' => $sym->_id]));
    }

    public function testShouldDestroySynonymous()
    {
        $sym = testSynonymousProvider::saved('simple_valid_sym');

        $this->requestAction('DELETE', 'Admin\SynonymsController@destroy', [ 'id' => $sym->_id ]);
        $this->assertRedirection(URL::action('Admin\SynonymsController@index'));
    }
}
