<?php

namespace App\Parse;

use App\Exceptions\ParserNotFoundException;

class ParserFactory
{
    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    /**
     * ParserFactory constructor.
     * @param $parsers
     */
    public function __construct($parsers)
    {
        $this->parsers = $parsers;
    }

    /**
     * @param $retailerCode
     * @return ParserInterface
     * @throws ParserNotFoundException
     */
    public function createFromCode($retailerCode)
    {
        $parser = $this->findParser($retailerCode);

        if (!$parser) {
            throw new ParserNotFoundException('No registered parser found');
        }

        return $parser;
    }

    /**
     * @param $retailerCode
     * @return ParserInterface|null
     */
    private function findParser($retailerCode)
    {
        $parser = null;

        foreach ($this->parsers as $taggedParser) {
            if ($taggedParser->getParserCode() === $retailerCode) {
                $parser = $taggedParser;
                break;
            }
        }

        return $parser;
    }
}
