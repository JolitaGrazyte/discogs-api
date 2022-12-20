<?php

namespace Jolita\DiscogsApi\Test;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Jolita\DiscogsApi\DiscogsApi;
use Jolita\DiscogsApi\Exceptions\DiscogsApiException;
use Jolita\DiscogsApi\SearchParameters;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Mockery;

class DiscogsApiTest extends TestCase
{
    protected string $token;
    protected string $userAgent;
    protected Client $client;
    protected DiscogsApi $discogs;


    public function setUp(): void
    {
        parent::setUp();
        $this->client = Mockery::mock(Client::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_get_artist_by_id(): void
    {
        $nameMustBe = 'The Persuader';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['name' => $nameMustBe]));

        $this->discogs = new DiscogsApi($this->client);
        $output = $this->discogs->artist('1')->name;

        $this->assertEquals($nameMustBe, $output);
    }

    /** @test */
    public function it_can_get_label_by_id(): void
    {
        $nameMustBe = 'Planet E';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['name' => $nameMustBe]));

        $this->discogs = new DiscogsApi($this->client);
        $output = $this->discogs->label('1')->name;

        $this->assertEquals($nameMustBe, $output);
    }

    /** @test */
    public function it_can_get_label_releases(): void
    {
        $titleMustBe = 'DJ-Kicks';
        $artistMustBe = 'Andrea Parker';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->times(2)
            ->andReturn(json_encode(['title' => $titleMustBe, 'artist' => $artistMustBe]));

        $this->discogs = new DiscogsApi($this->client);
        $artist = $this->discogs->labelReleases('1')->artist;
        $title = $this->discogs->labelReleases('1')->title;

        $this->assertEquals($titleMustBe, $title);
        $this->assertEquals($artistMustBe, $artist);
    }

    /** @test */
    public function it_can_get_release_by_id(): void
    {
        $titleMustBe = 'Stockholm';
        $artistMustBe = 'The Persuader';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->times(2)
            ->andReturn(json_encode(['title' => $titleMustBe, 'artist' => $artistMustBe]));

        $this->discogs = new DiscogsApi($this->client);
        $artist = $this->discogs->release('1')->artist;
        $title = $this->discogs->release('1')->title;

        $this->assertEquals($titleMustBe, $title);
        $this->assertEquals($artistMustBe, $artist);
    }

    /** @test */
    public function it_can_get_inventory(): void
    {
        $usernameMustBe = 'wgwstore';

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['listings' => ['seller' => ['username' => $usernameMustBe]]]));

        $this->discogs = new DiscogsApi($this->client, '12345');
        $output = $this->discogs->getMyInventory('wgwstore')->listings->seller->username;

        $this->assertEquals($usernameMustBe, $output);
    }

    /** @test */
    public function it_can_get_orders(): void
    {
        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->times(2)
            ->andReturn(json_encode(['pagination' => ['items' => 1234], 'orders' => ['items' => ['1234', '2', '3']]]));

        $this->discogs = new DiscogsApi($this->client, '12345');
        $itemsTotal = $this->discogs->getMyOrders()->pagination->items;
        $orderItems = $this->discogs->getMyOrders()->orders->items;

        $this->assertEquals($itemsTotal, 1234);
        $this->assertEquals($orderItems, ['1234', '2', '3']);
    }

    /** @test */
    public function it_search_discogs_database(): void
    {
        $expectedStyles = ['Downtempo', 'Trip Hop'];

        $this->client
            ->shouldReceive('get->getBody->getContents')
            ->once()
            ->andReturn(json_encode(['results' => ['style' => $expectedStyles]]));

        $this->discogs = new DiscogsApi($this->client, '12345');
        $searchParameters = SearchParameters::make()->setType('release')->setFormat('LP')->setYear(1996);
        $output = $this->discogs->search('MoWax', $searchParameters)->results->style;

        $this->assertEquals($expectedStyles, $output);
    }

    /** @test */
    public function it_throws_exception_if_no_token_provided(): void
    {
        $this->client
            ->shouldReceive('get->getBody->getContents');

        $this->discogs = new DiscogsApi($this->client);
        $output = $this->expectException(DiscogsApiException::class);
        $this->discogs->search('keyword');

        $this->assertTrue($output);
    }

    /** @test */
    public function it_can_post_order_status_to_discogs(): void
    {
        $this->client
            ->shouldReceive('post')->times(2)->andReturn((object) ['reasonPhrase' => 'OK', 'statusCode' => 200]);
        $this->discogs = new DiscogsApi($this->client, '12345', 'fakeAgent');
        $reasonPhrase = $this->discogs->changeOrderStatus('123', 'Cancelled (Item Unavailable)')->reasonPhrase;
        $statusCode = $this->discogs->changeOrderStatus('123', 'Cancelled (Item Unavailable)')->statusCode;

        $this->assertEquals($reasonPhrase, 'OK');
        $this->assertEquals($statusCode, 200);
    }

    /** @test */
    public function it_can_post_shipping_to_discogs(): void
    {
        $this->client
            ->shouldReceive('post')->times(2)->andReturn((object) ['reasonPhrase' => 'OK', 'statusCode' => 200]);
        $this->discogs = new DiscogsApi($this->client, '12345', 'fakeAgent');
        $reasonPhrase = $this->discogs->addShipping('123', '12.60')->reasonPhrase;
        $statusCode = $this->discogs->addShipping('123', '12.60')->statusCode;

        $this->assertEquals($reasonPhrase, 'OK');
        $this->assertEquals($statusCode, 200);
    }

    /** @test */
    public function it_can_delete_listing_from_inventory(): void
    {
        $this->client
            ->shouldReceive('delete')->times(2)->andReturn((object) ['reasonPhrase' => 'OK', 'statusCode' => 200]);
        $this->discogs = new DiscogsApi($this->client, '12345', 'fakeAgent');
        $reasonPhrase = $this->discogs->deleteListing('123')->reasonPhrase;
        $statusCode = $this->discogs->deleteListing('123')->statusCode;

        $this->assertEquals($reasonPhrase, 'OK');
        $this->assertEquals($statusCode, 200);
    }

    /** @test */
    public function it_can_request_inventory_export(): void
    {
        $this->client
            ->shouldReceive('post')->times(2)->andReturn((object) ['reasonPhrase' => 'OK', 'statusCode' => 200]);
        $this->discogs = new DiscogsApi($this->client, '12345', 'fakeAgent');
        $response = $this->discogs->requestInventoryExport();

        $reasonPhrase = $this->discogs->requestInventoryExport()->reasonPhrase;
        $statusCode = $this->discogs->requestInventoryExport()->statusCode;

        $this->assertEquals($reasonPhrase, 'OK');
        $this->assertEquals($statusCode, 200);
    }
}
