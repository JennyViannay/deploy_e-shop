<?php

namespace App\Model;

class SizeManager extends AbstractManager
{
    const TABLE = 'size';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}