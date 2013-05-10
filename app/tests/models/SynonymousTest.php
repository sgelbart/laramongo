<?php

use Mockery as m;
use Laramongo\SearchEngine\ElasticSearchEngine;

class SynonymousTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection('synonyms');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testShouldValidSynonymous()
    {
        $synonymous = testSynonymousProvider::instance('simple_valid_sym');

        $this->assertTrue($synonymous->isValid());
    }

    public function testShouldnotValidSynonymousWithOutWord()
    {
        $synonymous = testSynonymousProvider::instance('simple_valid_sym');
        $synonymous->word = null;

        $this->assertFalse($synonymous->isValid());
    }

    public function testShouldnotValidSynonymousWithOutRelatedWords()
    {
        $synonymous = testSynonymousProvider::instance('simple_valid_sym');
        $synonymous->related_word = null;

        $this->assertFalse($synonymous->isValid());
    }
}
