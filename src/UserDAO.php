<?php

namespace Soundify\DAO;

use Doctrine\DBAL\Connection;
use Soundify\Domain\User;

class SoundifyDAO
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }
    
}