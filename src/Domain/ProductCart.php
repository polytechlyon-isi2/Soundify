<?php

namespace Soundify\Domain;

class ProductCart 
{
    /**
     * Cart product.
     *
     * @var Product
     */
    private $product;

    /**
     * Cart user.
     *
     * @var User
     */
    private $user;
    
    /**
     * Cart count product.
     *
     * @var integer
     */
    private $count;

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
    
    public function __toString()
    {
        return $this->product;
    }
    
    public function getCount() {
        return $this->count;
    }
    
     public function setCount($count) {
        $this->count = $count;
    }
}