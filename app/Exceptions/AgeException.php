<?php

namespace App\Exceptions;

class AgeException extends \Exception
{
    protected $message = "Usuário precisa ser maior que 18 anos";

    public function __construct()
    {
        parent::__construct($this->message);
    }
}
