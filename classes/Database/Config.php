<?php

namespace ToDo\Database;

/**
 * Database configuration class
 */
class Config {
    private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbName;
    public $conn;

    /**
     * Class constructor
     *
     * @param string $hostName
     * @param string $username
     * @param string $password
     * @param string $dbName
     */
    function __construct( $hostName, $username, $password, $dbName = '' ) {
        $this->dbHost     = $hostName;
        $this->dbUser     = $username;
        $this->dbPassword = $password;
        $this->dbName     = $dbName;
    }

    /**
     * Sets the connection
     *
     * @return void
     */
    public function setConnection() {
        $this->conn = new \mysqli( $this->dbHost, $this->dbUser, $this->dbPassword, '' );

        $dbExists = $this->conn->query( "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->dbName'" );
        
        if ( ! $dbExists->num_rows ) {
            $dbCreation = $this->conn->query( "CREATE DATABASE IF NOT EXISTS $this->dbName" );
        }

        $this->conn->select_db( $this->dbName );
        
        if ( isset( $dbCreation ) ) {
            new DB( $this->conn );
        }

        if ( $this->conn->connect_error) {
            return die( "Connection failed: " . $this->conn->connect_error );
        }
            
        return $this->conn;
    }
}
