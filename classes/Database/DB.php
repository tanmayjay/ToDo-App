<?php

namespace ToDo\Database;

/**
 * Database handler class
 */
class DB {

    private $filename = 'assets/database/todo.sql';
    private $conn;
    private $tempLine = '';
    private $lines;

    /**
     * Class constructor
     */
    function __construct( $conn ) {
        $this->conn = $conn;
        $this->create();
    }

    /**
     * Creates database
     *
     * @return void
     */
    private function create() {
        $this->lines = file( $this->filename );

        foreach ( $this->lines as $line ) {
            
            if ( substr( $line, 0, 2 ) == '--' || $line == '') {
                continue;
            }

            $this->tempLine .= $line;
        
            if ( substr( trim( $line ), -1, 1 ) == ';' ) {
                
                if ( $this->conn->query( $this->tempLine ) ) {
                    print( 'Successfully executed query \'<strong>' . $this->tempLine . '\'</strong><br /><br />' );
                }
                else {
                    print( 'Error performing query \'<strong>' . $this->tempLine . '\'</strong>: ' . $this->conn->error . '<br /><br />' );
                }

                $this->tempLine = '';
            }
        }
        echo "Tables imported successfully<br/><br/>";
        printf('<a class="btn btn-info" href="%s">Get Started</a>', $_SERVER['REQUEST_URI']);
    }  
}