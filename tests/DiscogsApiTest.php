<?php

namespace Jolita\DiscogsApiWrapper\Test;

use Jolita\DiscogsApiWrapper\DiscogsApi;

class DiscogsApiTest extends \PHPUnit_Framework_TestCase
{
    protected $discogs;

    public function setUp()
    {
        parent::setUp();
        $this->discogs = (new DiscogsApi('', 'MyAmazingDiscogsApp/0.1'));

    }

    /** @test */
    public function it_can_get_artist_by_id()
    {
        $nameMustBe = 'The Persuader';
        $artist = $this->discogs->artist(1);

        $this->assertEquals($nameMustBe, $artist->name);
    }

    /** @test */
    public function it_can_get_label_by_id()
    {
        $nameMustBe = 'Planet E';
        $label = $this->discogs->label(1);

        $this->assertEquals($nameMustBe, $label->name);
    }

    /** @test */
    public function it_can_get_label_releases()
    {
        $titleMustBe = 'DJ-Kicks';
        $artistMustBe = 'Andrea Parker';
        $labelReleases = $this->discogs->labelReleases(1);
        $release = collect($labelReleases->releases)->first();

        $this->assertEquals($titleMustBe, $release->title);
        $this->assertEquals($artistMustBe, $release->artist);
    }

    /** @test */
    public function it_can_get_release_by_id()
    {
        $titleMustBe = 'Stockholm';
        $artistMustBe = 'The Persuader';
        $release = $this->discogs->release(1);

        $this->assertEquals($titleMustBe, $release->title);
        $this->assertEquals($artistMustBe, $release->artists[0]->name);
    }


}
