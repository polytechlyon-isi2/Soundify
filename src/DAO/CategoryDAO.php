<?php

namespace Soundify\DAO;

use Soundify\Domain\Category;

class CategoryDAO extends DAO
{
    /*
    private $productDAO;

    public function setProductDAO(ProductDAO $productDAO) {
        $this->productDAO = $productDAO;
    }
*/
    public function findAll() {
        $sql = "select * from category order by category_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $categories = array();
        foreach ($result as $row) {
            $categoryId = $row['category_id'];
            $categories[$categoryId] = $this->buildDomainObject($row);
        }
        return $categories;
    }

    /**
     * Creates a Category object based on a DB row.
     *
     * @return \Soundify\Domain\Category
     */
    protected function buildDomainObject($row) {
        $category = new Category();
        $category->setId($row['category_id']);
        $category->setName($row['category_name']);

        return $category;
    }

    /**
     * Returns a category matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Soundify\Domain\Category|throws an exception if no matching category is found
     */
    public function find($id) {
        $sql = "select * from category where category_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No category matching id " . $id);
    }

    /**
     * Saves a category into the database.
     *
     * @param \Soundify\Domain\Category product The product to save
     */
    public function save(Category $category) {
        $categoryData = array(
            'category_name' => $category->getName(),
        );

        if ($category->getId()) {
            // The article has already been saved : update it
            $this->getDb()->update('category', $categoryData, array('category_id' => $category->getId()));
        } else {
            // The article has never been saved : insert it
            $this->getDb()->insert('category', $categoryData);
            // Get the id of the newly created cateogry and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $category->setId($id);
        }
    }

    /**
     * Removes a category from the database.
     *
     * @param integer $id The category id.
     */
    public function delete($id) {
        /*// Delete the products if exist
        $products = $productDAO->findAllByCategory($id);
        foreach ($product as $products) {
            $productDAO->delete($product->getId());
        }*/
        // Delete the category
        $this->getDb()->delete('category', array('category_id' => $id));
    }
}