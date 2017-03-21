<?php

namespace App\Parse\Parsers;

use App\Parse\ParseObject;
use App\Parse\ParserInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class MobileLineParser implements ParserInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return ParseObject
     */
    public function parse($url)
    {
        try {
            $response = $this->client->get($url);

            $contents = $response->getBody()->getContents();

            $crawler = new Crawler($contents);

            $price = $crawler->filter('.ltl');

            $cleanPrice = $price->text();

            $cleanPrice = substr($cleanPrice, 0, -5);
            $cleanPrice = str_replace(',', '.', $cleanPrice);

            if ($cleanPrice === '0.00') {
                $money = null;
            } else {
                $money = parseMoney($cleanPrice);
            }

            return new ParseObject($money);
        } catch (\Exception $exception) {
            return new ParseObject(null);
        }
    }

    /**
     * @return string
     */
    public function getParserName()
    {
        return 'Mobili Linija';
    }

    /**
     * @return string
     */
    public function getParserCode()
    {
        return 'mobile_line';
    }
}