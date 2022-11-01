<?php

namespace App\Twig;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('convertDate', [$this, 'convertDate']),
        ];
    }

    public function convertDate(DateTimeImmutable $date):string
    {   
        if($date->format('d-m-y') === date('d-m-y')){
            return 'Heute '. $date->format("H:i").' h';
        }
        return $date->format('d.m.y H:i').' h';
    }
}
