<?php

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

if (!function_exists('formatMoney')) {
    /**
     * @param Money|null $money
     * @return string
     */
    function formatMoney(Money $money = null)
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        $value = $money ? $moneyFormatter->format($money) . ' ' . $money->getCurrency()->getCode() : '---';

        return $value;
    }
}

if (!function_exists('parseMoney')) {
    /**
     * @param $price
     * @return Money
     */
    function parseMoney($price)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($price, 'EUR');

        return $money;
    }
}