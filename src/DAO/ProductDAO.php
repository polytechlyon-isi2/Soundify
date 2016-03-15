<?php

namespace Soundify\DAO;

use Soundify\Domain\Product;

class ProductDAO extends DAO
{
    
    /**
     * @var \Soundify\DAO\CategoryDAO
     */
    private $categoryDAO;

    public function setCategoryDAO(CategoryDAO $categoryDAO) {
        $this->categoryDAO = $categoryDAO;
    }
    
     /**
     * Return a list of all products.
     *
     * @return array A list of all products.
     */
    public function findAll() {
        $sql = "select * from product order by product_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $products = array();
        foreach ($result as $row) {
            $productId = $row['product_id'];
            $products[$productId] = $this->buildDomainObject($row);
        }
        return $products;
    }
    
     /**
     * Return a list of all product, sorted by category.
     *
     * @return array A list of all product.
     */
    public function findAllByCategory($categoryId) {
        // The associated article is retrieved only once
        $category = $this->categoryDAO->find($categoryId);

        // The product won't be retrieved during domain objet construction
        $sql = "select * from product where product_category=?";
        $result = $this->getDb()->fetchAll($sql, array($categoryId));

        // Convert query result to an array of domain objects
        $products = array();
        foreach ($result as $row) {
            $productId = $row['product_id'];
            $product = $this->buildDomainObject($row);
            // The associated category is defined for the constructed product
            $product->setCategory($category);
            $products[$productId] = $product;
        }
        return $products;
    }

    /**
     * Creates a Product object based on a DB row.
     *
     * @param array $row The DB row containing Category data.
     * @return \Soundify\Domain\Product
     */
    protected function buildDomainObject($row) {
        $product = new Product();
        $product->setId($row['product_id']);
        $product->setName($row['product_name']);
        $product->setShortDescription($row['product_short_desc']);
        $product->setLongDescription($row['product_long_desc']);
        $product->setPrice($row['product_price']);
        $product->setImage($row['product_image']);

        if (array_key_exists('product_category', $row)) {
            // Find and set the associated article
            $categoryId = $row['product_category'];
            $category = $this->categoryDAO->find($categoryId);
            $product->setCategory($category);
        }
        
        return $product;
    }
    
    /**
     * Returns a product matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Soundify\Domain\Product|throws an exception if no matching product is found
     */
    public function find($id) {
        $sql = "select * from product where product_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No product matching id " . $id);
    }
    
     /**
     * Saves an product into the database.
     *
     * @param \Soundify\Domain\Product product The product to save
     */
    public function save(Product $product) {
        $productData = array(
            'product_name' => $product->getName(),
            'product_short_desc' => $product->getShortDescription(),
            'product_long_desc' => $product->getLongDescription(),
            'product_price' => $product->getPrice(),
            'product_category' => $product->getCategory(),
            'product_image' => $product->getImage(),
            );

        if ($product->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('product', $productData, array('product_id' => $product->getId()));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('product', $productData);
            // Get the id of the newly created product and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $product->setId($id);
        }
    }

    /**
     * Removes an product from the database.
     *
     * @param integer $id The product id.
     */
    public function delete($id) {
        // Delete the product
        $this->getDb()->delete('product', array('product_id' => $id));
    }
}