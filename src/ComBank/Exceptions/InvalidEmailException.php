<?php namespace ComBank\Exceptions;

class InvalidEmailException extends BaseExceptions
{
    protected $errorCode = 100;
    protected $errorLabel = 'InvalidEmailException';
}
