<?php

namespace Core;
use Exception;

class UploadException extends Exception
{
    public function __construct(string $message = '', int $code = 1) {
        parent::__construct($message, $code);
        $this->message = "$message";
        $this->code = $code;
    }
}