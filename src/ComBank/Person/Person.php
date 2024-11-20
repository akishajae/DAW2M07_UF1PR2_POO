<?php

namespace ComBank\Person;

use ComBank\Support\Traits\ApiTrait;

class Person {
    // Trait
    use ApiTrait;

    //Properties
    private $name;
    private $idCard;
    private $email;

    // Constructor
    public function __construct(string $name, int $idCard, string $email) {
        $this->name = $name;
        $this->idCard = $idCard;
        
        $this->validateEmail($email);
        $this->email = $email;
    }

    // Getters & setters

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of idCard
     */ 
    public function getIdCard()
    {
        return $this->idCard;
    }
    
    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $this->validateEmail($email);

        return $this;
    }
}