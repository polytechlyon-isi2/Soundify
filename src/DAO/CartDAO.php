<?php

namespace Soundify\DAO;

use Soundify\Domain\ProductCart;
use Soundify\DAO\UserDAO;
use Soundify\DAO\ProductDAO;

class CartDAO extends DAO
{
    /**
     * @var \Soundify\DAO\UserDAO
     */
    private $userDAO;

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * @var \Soundify\DAO\ProductDAO
     */
    private $productDAO;

    public function setProductDAO(ProductDAO $productDAO) {
        $this->productDAO = $productDAO;
    }
    
    /**
     * Return a cart for user.
     *
     * @return array A list of all product.
     */
    public function findAllByUser($userId) {
        $user = $this->userDAO->find($userId);

        // The product won't be retrieved during domain objet construction
        $sql = "select * from cart where cart_user=?";
        $result = $this->getDb()->fetchAll($sql, array($userId));

        // Convert query result to an array of domain objects
        $cart = array();
        foreach ($result as $row) {
            $productId = $row['cart_product'];
            $product = $this->productDAO->find($productId);

            $productCart = $this->buildDomainObject($row);
            $productCart->setUser($user);
            $productCart->setProduct($product);
            $cart[$productId] = $productCart;
        }
        return $cart;
    }


    public function getCountByUser($userId)
    {
        $user = $this->userDAO->find($userId);

        // The product won't be retrieved during domain objet construction
        $sql = "select count(*) from cart where cart_user=?";
        $row = $this->getDb()->fetchAssoc($sql, array($userId));

        return $row['count(*)'];
    }

    /**
     * Returns a product cart matching the supplied product et user.
     *
     * @param integer $userId
     * @param integer $productId
     *
     * @return \Soundify\Domain\ProductCart
     */
    public function find($productId,$userId) {
        $sql = "select * from cart where cart_product=? and cart_user=?";
        $row = $this->getDb()->fetchAssoc($sql, array($productId,$userId));

        if ($row)
            return $this->buildDomainObject($row);
        else
            return null;
    }

    /**
     * Creates a Cart object based on a DB row.
     *
     * @param array $row The DB row containing Category data.
     * @return \Soundify\Domain\ProductCart
     */
    protected function buildDomainObject($row) {
        $productCart = new ProductCart();
        $productCart->setCount($row['cart_count']);

        if (array_key_exists('cart_product', $row)) {
            // Find and set the associated product
            $productId = $row['cart_product'];
            $product = $this->productDAO->find($productId);
            $productCart->setProduct($product);
        }
        if (array_key_exists('cart_user', $row)) {
            // Find and set the associated user
            $userId = $row['cart_user'];
            $user = $this->userDAO->find($userId);
            $productCart->setUser($user);
        }  

        return $productCart;
    }

    private function existingProductInCart($productCart)
    {
        $productFind = $this->find($productCart->getProduct()->getId(),$productCart->getUser()->getId());
        if($productFind){
            if($productFind->getProduct()==$productCart->getProduct() && $productFind->getUser()==$productCart->getUser())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Saves an product into the database.
     *
     * @param \Soundify\Domain\ProductCart product The product to save in cart
     */
    public function save(ProductCart $productCart) {
        if ($this->existingProductInCart($productCart)==true) {
            //$productCart->setCount($this->find($productCart->getProduct()->getId(),$productCart->getUser()->getId())->getCount());
            $productCartData = array(
                'cart_count' => $productCart->getCount(),
            );
            // The product has already been saved : update it
            $this->getDb()->update('cart', $productCartData, array('cart_product' => $productCart->getProduct()->getId(),'cart_user' => $productCart->getUser()->getId()));
        } else {
            $productCartData = array(
                'cart_product' => $productCart->getProduct()->getId(),
                'cart_user' => $productCart->getUser()->getId(),
                'cart_count' => $productCart->getCount(),
            );
            // The product has never been saved : insert it
            $this->getDb()->insert('cart', $productCartData);
        }
    }


    /**
     * Removes a product in cart from the database.
     *
     * @param integer $userId The user id.
    * @param integer $productId The product id.
     */
    public function delete($userId,$productId) {
        // Delete a product
        $this->getDb()->delete('cart', array('cart_user' => $userId,'cart_product' => $productId));
    }

    /**
     * Removes all product in cart from the database.
     *
     * @param integer $userId The user id.
     */
    public function deleteAll($userId) {
        $this->getDb()->delete('cart', array('cart_user' => $userId));
    }
    
    /**
     * Removes all product in cart from the database.
     *
     * @param integer $userId The user id.
     */
    public function deleteAllByProduct($productId) {
        $this->getDb()->delete('cart', array('cart_product' => $productId));
    }

}