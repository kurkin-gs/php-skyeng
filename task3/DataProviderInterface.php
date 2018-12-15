<?php

namespace src;

interface DataProviderInterface
{
    /**
     * @param array $request
     * @return array
     */
    public function get(array $request): array;
}