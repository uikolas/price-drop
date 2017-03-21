<?php

namespace App\Parse\Parsers;

use App\Parse\ParseObject;
use App\Parse\ParserInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SkytechParser implements ParserInterface
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

            $price = $crawler->filter('.num');

            $cleanPrice = explode('/', $price->text());
            $cleanPrice = trim($cleanPrice[0]);
            $cleanPrice = str_replace('€', '', $cleanPrice);

            $money = parseMoney($cleanPrice);

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
        return 'Skytech';
    }

    /**
     * @return string
     */
    public function getParserCode()
    {
        return 'skytech';
    }
}