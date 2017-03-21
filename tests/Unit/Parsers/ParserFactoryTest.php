<?php

namespace Tests\Unit\Parsers;

use App\Exceptions\ParserNotFoundException;
use App\Parse\Parsers\MobileLineParser;
use App\Parse\ParserFactory;
use GuzzleHttp\Client;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateParser()
    {
        $code = 'mobile_line';

        $parser = new ParserFactory([new MobileLineParser(new Client())]);

        $create = $parser->createFromCode($code);

        $this->assertInstanceOf(MobileLineParser::class, $create);
    }

    public function testCreateParserException()
    {
        $this->expectException(ParserNotFoundException::class);

        $code = 'bad';

        $parser = new ParserFactory([new MobileLineParser(new Client())]);

        $parser->createFromCode($code);
    }
}
