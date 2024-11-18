<?php

namespace App\Exceptions;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            $message != "" ? $message : 'Entity not found!', 
            $code != 0 ? $code : 404, 
            $previous
        );
    }
}
