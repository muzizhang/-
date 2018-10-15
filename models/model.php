<?php
namespace models;

class model
{
    protected $_pdo;

    public function __construct()
    {
        $this->_pdo = \libs\DB::make();
    }
}