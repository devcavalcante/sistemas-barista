<?php

namespace App\Exceptions;

class NotFoundException extends \Exception
{
    /**
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
