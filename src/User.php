<?php

namespace Soundify\Domain;

class User 
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User name.
     *
     * @var string
     */
    private $name;

    /**
     * User firstname.
     *
     * @var string
     */
    private $firstname;
    
    /**
     * User address.
     *
     * @var string
     */
    private $address;
    
    /**
     * User zip code.
     *
     * @var string
     */
    private $zipcode;
    
    /**
     * User email.
     *
     * @var string
     */
    private $email;
    
    /**
     * User password.
     *
     * @var string
     */
    private $password;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }
    
    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }
    
    public function getZipCode() {
        return $this->zipcode;
    }

    public function setZipCode($zipcode) {
        $this->zipcode = $zipcode;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}