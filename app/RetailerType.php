<?php

declare(strict_types=1);

namespace App;

enum RetailerType: string
{
    case SKYTECH = 'skytech';
    case MOBILI = 'mobili';
    case AMAZON = 'amazon';
    case G2A = 'g2a';
    case ENEBA = 'eneba';
    case DUMMYJSON = 'dummyjson';
}
