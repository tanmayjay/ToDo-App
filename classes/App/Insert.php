<?php

namespace ToDo\App;

/**
 * Insert class handler
 */
class Insert extends Validation {
    
    private $task;
    private $sql;
    private $stmt;
    private $conn;

    /**
     * Class constructor
     *
     * @param object $conn
     */
    function __construct( $conn ) {
        $this->conn = $conn;
    }

    /**
     * Inserts tasks
     *
     * @return void
     */
    public function insertTask() {

        if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
            $this->task = $this->validate( $_POST["task"] );
            
            if ( ! empty( $this->task ) ) {
                $this->sql  = "INSERT INTO tasks( task ) VALUES ( ? )";
                $this->stmt = $this->conn->prepare( $this->sql );
                $this->stmt->bind_param( 's', $this->task );
                
                if ( $this->stmt->execute() ) {
                    $this->conn->close();
                    header( "location:index.php" );
                }

            } else {
                $this->setError( "Input Error: Task cannot be empty" );
            }                      
        } 
    }   
}