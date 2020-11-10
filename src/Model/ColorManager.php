<?php

namespace App\Model;

class ColorManager extends AbstractManager
{
    const TABLE = 'color';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}