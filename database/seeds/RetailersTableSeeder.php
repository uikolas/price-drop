<?php

use App\Parse\ParserInterface;
use Illuminate\Database\Seeder;

class RetailersTableSeeder extends Seeder
{
    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    /**
     * RetailersTableSeeder constructor.
     * @param $parsers
     */
    public function __construct($parsers)
    {
        $this->parsers = $parsers;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->parsers as $parser) {
            $alreadyExists = \DB::table('retailers')->where('retailer_code', $parser->getParserCode())->exists();

            if (!$alreadyExists) {
                \DB::table('retailers')->insert([
                    'name'          => $parser->getParserName(),
                    'retailer_code' => $parser->getParserCode(),
                ]);
            }
        }
    }
}
