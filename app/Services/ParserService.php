<?php

namespace App\Services;

use App\Parse\ParseObject;
use App\Parse\ParserFactory;

class ParserService
{
    /**
     * @var ParserFactory
     */
    private $parserFactory;

    /**
     * ParserService constructor.
     * @param ParserFactory $parserFactory
     */
    public function __construct(ParserFactory $parserFactory)
    {
        $this->parserFactory = $parserFactory;
    }

    /**
     * @param $url
     * @param $retailerCode
     * @return ParseObject
     */
    public function parseProductRetailer($url, $retailerCode)
    {
        $parser = $this->parserFactory->createFromCode($retailerCode);

        $parse = $parser->parse($url);

        return $parse;
    }
}
