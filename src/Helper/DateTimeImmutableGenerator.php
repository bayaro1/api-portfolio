<?php
namespace App\Helper;

use DateTimeImmutable;
use DateTimeZone;

class DateTimeImmutableGenerator
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
    }
}