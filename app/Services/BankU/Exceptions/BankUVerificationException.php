<?php

namespace App\Services\BankU\Exceptions;

use App\Services\BankU\DataTransferObjects\BankUResponse;

class BankUVerificationException extends BankUException
{
    public function __construct(public readonly BankUResponse $response)
    {
        parent::__construct($response->message);
    }
}
