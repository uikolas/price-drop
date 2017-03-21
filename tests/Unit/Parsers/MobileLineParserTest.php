<?php

namespace Tests\Parsers\Unit;

use App\Parse\Parsers\MobileLineParser;
use App\Parse\ParseObject;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Money\Money;
use Symfony\Component\DomCrawler\Crawler;

class MobileLineParserTest extends \PHPUnit_Framework_TestCase
{
    public function testPriceParse()
    {
        $content = file_get_contents(__DIR__.'\Fixtures\Mobili\Mobili.html');

        $mock = new MockHandler([
            new Response(
                200,
                [],
                $content
            )
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $parser = new MobileLineParser($client);

        $parse = $parser->parse('url');

        $this->assertInstanceOf(ParseObject::class, $parse);
        $this->assertEquals('32900', $parse->getPrice()->getAmount());
    }

    public function testZeroPriceParse()
    {
        $content = file_get_contents(__DIR__.'\Fixtures\Mobili\Mobili-no-price.html');

        $mock = new MockHandler([
            new Response(
                200,
                [],
                $content
            )
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $parser = new MobileLineParser($client);

        $parse = $parser->parse('url');

        $this->assertNull($parse->getPrice());
    }
}
