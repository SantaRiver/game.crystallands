<?php

namespace App\Interfaces;

interface AtomicClientInterface
{
    /**
     * Call a method on the Atomic client.
     *
     * @param string $method
     * @param object $params
     * @return array
     */
    public function call($method, $params): array;

    /**
     * Format request params.
     *
     * @return string
     */
    public static function requestFormatter($method, $params): string;
}
