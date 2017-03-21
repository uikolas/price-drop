<?php

namespace App\Parse;

use Money\Money;
use Symfony\Component\DomCrawler\Crawler;

interface ParserInterface
{
    /**
     * @param string $url
     * @return ParseObject
     */
    public function parse($url);

    /**
     * @return string
     */
    public function getParserName();

    /**
     * @return string
     */
    public function getParserCode();
}
