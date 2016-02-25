<?php

namespace Soundify\Domain;

class Product 
{
    /**
     * Product id.
     *
     * @var integer
     */
    private $id;

    /**
     * Product name.
     *
     * @var string
     */
    private $name;

    /**
     * Product short description.
     *
     * @var string
     */
    private $short_desc;
    
    /**
     * Product long description.
     *
     * @var string
     */
    private $long_desc;
    
    /**
     * Product price.
     *
     * @var float
     */
    private $price;
    
    /**
     * Product image.
     *
     * @var string
     */
    private $image;
    
    /**
     * Product category.
     *
     * @var Category
     */
    private $category;

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

    public function getShortDescription() {
        return $this->short_desc;
    }

    public function setShortDescription($short_desc) {
        $this->short_desc = $short_desc;
    }
    
    public function getLongDescription() {
        return $this->long_desc;
    }

    public function setLongDescription($long_desc) {
        $this->long_desc = $long_desc;
    }
    
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }
    
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }
    
}