<?php

abstract class AbstractSignupData
{
    protected static function text(array $source, string $key): string
    {
        return trim((string) ($source[$key] ?? ''));
    }

    protected static function raw(array $source, string $key): string
    {
        return (string) ($source[$key] ?? '');
    }
}
