<?php

namespace ToDo\App;

/**
 * Update class handler
 */
class Update extends Validation {
    private $task;
    private $sql;
    private $stmt;
    private $conn;
    private $row;
    private $status;
    private $id;

    /**
     * Class constructor
     *
     * @param object $conn
     */
    function __construct( $conn ) {
        $this->conn = $conn;
    }

    /**
     * Updates task name
     *
     * @param int $id
     * 
     * @return void
     */
    public function updateTaskName( $id ){   
        
        if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
            $this->id       = $id;
            $this->task     = $this->validate( $_POST["task"] );
            
            if ( ! empty( $this->task ) ) {
                $this->sql = "UPDATE tasks SET task = ? WHERE id = ?";
                $this->stmt = $this->conn->prepare( $this->sql );
                $this->stmt->bind_param( 'si', $this->task, $this->id );
                if ( $this->stmt->execute() ) {
                    $this->conn->close();
                }
            } else {
                $this->setError( "Update Error: Task cannot be empty" );
            }
        }
    }

    /**
     * Updates task status
     *
     * @param int $id
     * 
     * @return void
     */
    public function updateTaskStatus( $id ) {   
        $this->id   = $id;
        $this->sql  = "SELECT active FROM tasks WHERE id = ?";
        $this->stmt = $this->conn->prepare( $this->sql );
        $this->stmt->bind_param( 'i', $this->id );  
        
        if ( $this->stmt->execute() ) {
            $this->result = $this->stmt->get_result();
            
            if ( $this->row = $this->result->fetch_assoc() ) {
                $this->status = $this->row['active'];
            }
        }
        
        if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
            $this->status = 1-$this->status;
            $this->sql    = "UPDATE tasks SET active = ? WHERE id = ?";
            $this->stmt   = $this->conn->prepare( $this->sql );
            $this->stmt->bind_param( 'ii', $this->status, $this->id );
            
            if ( $this->stmt->execute() ) {
                $this->conn->close();
                header( "location:index.php" );
            }
        }
    }
}
?>