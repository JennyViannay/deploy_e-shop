<?php

namespace App\Model;

class BrandManager extends AbstractManager
{
    const TABLE = 'brand';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
