<?php

namespace Jolita\DiscogsApiWrapper\Test;

use Jolita\DiscogsApiWrapper\DiscogsApi;

class DiscogsApiTest extends \PHPUnit_Framework_TestCase
{
    protected $discogs;

    public function setUp()
    {
        parent::setUp();
        $this->discogs = (new DiscogsApi());
    }

    /** @test */
    public function it_can_get_artist_by_id()
    {
        $nameMustBe = 'The Persuader';
        $artist = $this->discogs->get('artist', 1);

        $this->assertEquals($nameMustBe, $artist->name);
    }

    /** @test */
    public function it_can_get_label_by_id()
    {
        $nameMustBe = 'Planet E';
        $label = $this->discogs->get('label', 1);

        $this->assertEquals($nameMustBe, $label->name);
    }

}
