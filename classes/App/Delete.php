<?php

namespace ToDo\App;

/**
 * Delete handler class
 */
class Delete {
    private $sql;
    private $stmt;
    private $db;
    private $conn;
    private $tempStatus;
    
    /**
     * Class constructor
     *
     * @param object $conn
     */
    function __construct( $conn ) {
        $this->conn = $conn;
    }

    /**
     * Deletes tasks
     *
     * @param int $id
     * 
     * @return void
     */
    public function deleteById( $id ) {            
        $this->sql  = "DELETE FROM tasks WHERE id = ?";
        $this->stmt = $this->conn->prepare( $this->sql );
        $this->stmt->bind_param( 'i', $id );
        
        if ( $this->stmt->execute() ) {
            $this->conn->close();
        }
    }

    /**
     * Deletes tasks
     *
     * @param string $status
     * 
     * @return void
     */
    public function deleteByStatus( $status ) {   
        $this->sql  = "DELETE FROM tasks WHERE active = ?";
        $this->stmt = $this->conn->prepare($this->sql);
        
        if ( $status == 'Complete' ) {
            $this->tempStatus = 0;
            $this->stmt->bind_param( 'i', $this->tempStatus );
        } elseif( $status == 'Incomplete' ) {
            $this->tempStatus = 1;
            $this->stmt->bind_param( 'i', $this->tempStatus );
        }

        if ( $this->stmt->execute() ) {
            $this->conn->close();               
        }
    }
}