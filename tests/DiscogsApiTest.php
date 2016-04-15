<?php

namespace Jolita\DiscogsApiWrapper\Test;

use Jolita\DiscogsApiWrapper\DiscogsInventory;
use Jolita\DiscogsApiWrapper\DiscogsSearch;
use Jolita\DiscogsApiWrapper\Discogs;
use Jolita\DiscogsApiWrapper\DiscogsOrders;
use Jolita\DiscogsApiWrapper\SearchParameters;
use Jolita\DiscogsApiWrapper\Exceptions\DiscogsApiException;


class DiscogsApiTest extends \PHPUnit_Framework_TestCase
{
    protected $token;
    protected $mock;

    /** @test */
    public function it_can_get_artist_by_id()
    {
        $nameMustBe = 'The Persuader';
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\Discogs');
        $this->mock->expects($this->once())->method('artist')
            ->will($this->returnValue(json_encode(['name' => $nameMustBe])));

        $this->assertEquals($nameMustBe, json_decode($this->mock->artist("1"))->name);

    }

    /** @test */
    public function it_can_get_label_by_id()
    {
        $nameMustBe = 'Planet E';
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\Discogs');
        $this->mock->expects($this->once())->method('label')
            ->will($this->returnValue(json_encode(['name' => $nameMustBe])));;

        $this->assertEquals($nameMustBe, json_decode($this->mock->label("1"))->name);
    }

    /** @test */
    public function it_can_get_label_releases()
    {
        $titleMustBe = 'DJ-Kicks';
        $artistMustBe = 'Andrea Parker';
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\Discogs');
        $this->mock->expects($this->any())->method('labelReleases')
            ->will($this->returnValue(json_encode(['title' => $titleMustBe,'artist' => $artistMustBe])));

        $this->assertEquals($titleMustBe, json_decode($this->mock->labelReleases(1))->title);
        $this->assertEquals($artistMustBe, json_decode($this->mock->labelReleases(1))->artist);
    }

    /** @test */
    public function it_can_get_release_by_id()
    {
        $titleMustBe = 'Stockholm';
        $artistMustBe = 'The Persuader';

        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\Discogs');
        $this->mock->expects($this->any())->method('release')
            ->will($this->returnValue(json_encode(['title' => $titleMustBe,'artist' => $artistMustBe])));

        $this->assertEquals($titleMustBe, json_decode($this->mock->release(1))->title);
        $this->assertEquals($artistMustBe, json_decode($this->mock->release(1))->artist);
    }

    /** @test */
    public function it_can_get_inventory()
    {
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\DiscogsInventory', ['getMyInventory'], ['12345', '']);

        $this->mock->expects($this->once())->method('getMyInventory');

        $this->mock->getMyInventory('wgwstore');
    }

    /** @test */
    public function it_can_get_orders()
    {
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\DiscogsOrders', ['getMyOrders'], ['12345', 'MyApp']);

        $this->mock->expects($this->once())->method('getMyOrders');

        $this->mock->getMyOrders();
    }

    /** @test */
    public function it_search_discogs_database()
    {
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\DiscogsSearch', ['search'], ['12345', 'MyApp']);

        $this->mock->expects($this->once())->method('search');

        $searchParameters = new SearchParameters();

        $searchParameters->type('release')->format('LP')->year('1996');

        $this->mock->search('MoWax', $searchParameters);
    }

    /** @test */
    public function it_throws_exception_if_no_token_provided()
    {
        $this->mock = $this->getMock('Jolita\DiscogsApiWrapper\DiscogsSearch', ['search'], [null, 'MyApp']);

        $this->mock->expects($this->once())->method('search');

        $this->mock->method('search')->will($this->throwException(DiscogsApiException::tokenRequiredException()));

        $this->mock->search("something");
    }

}
