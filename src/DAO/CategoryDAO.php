<?php

namespace Soundify\DAO;

use Soundify\Domain\Category;

class CategoryDAO extends DAO
{
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
}