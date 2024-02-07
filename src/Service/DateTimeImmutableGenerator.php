<?php
namespace App\Service;

use DateTimeImmutable;
use DateTimeZone;

class DateTimeImmutableGenerator
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
    }
}