<?php

namespace Tests\Parsers\Unit;

use App\Parse\ParseObject;
use App\Parse\Parsers\SkytechParser;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class SkytechParserTest extends \PHPUnit_Framework_TestCase
{
    public function testPriceParse()
    {
        $content = file_get_contents(__DIR__.'\Fixtures\Skytech\Skytech.html');

        $mock = new MockHandler([
            new Response(
                200,
                [],
                $content
            )
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $parser = new SkytechParser($client);

        $parse = $parser->parse('url');

        $this->assertInstanceOf(ParseObject::class, $parse);
        $this->assertEquals('54639', $parse->getPrice()->getAmount());
    }

    public function testZeroPriceParse()
    {
        $content = file_get_contents(__DIR__.'\Fixtures\Skytech\Skytech-no-price.html');

        $mock = new MockHandler([
            new Response(
                404,
                [],
                $content
            )
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $parser = new SkytechParser($client);

        $parse = $parser->parse('url');

        $this->assertNull($parse->getPrice());
    }
}
