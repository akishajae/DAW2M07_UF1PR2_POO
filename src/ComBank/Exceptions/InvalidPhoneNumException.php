<?php namespace ComBank\Exceptions;

class InvalidPhoneNumException extends BaseExceptions
{
    protected $errorCode = 100;
    protected $errorLabel = 'InvalidPhoneNumException';
}
