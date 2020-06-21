<?php

namespace ToDo\App;

/**
 * Validation handler class
 */
Class Validation {
    private $err;
    private $task;
    
    /**
     * Sanitizes input
     *
     * @param string $data
     * 
     * @return string
     */
    private function sanitizeInput( $data ) {
        $data = trim( $data );
        $data = stripslashes( $data );
        $data = htmlspecialchars( $data );
        return $data;
    }

    /**
     * Sets input error
     *
     * @param string $err
     * 
     * @return void
     */
    protected function setError( $err ) {
        $this->err = $err;
    }

    /**
     * Retrieves the error
     *
     * @return string
     */
    public function getError() {
        return $this->err;
    }

    /**
     * Validates inputs
     *
     * @param string $inputTask
     * 
     * @return string
     */
    public function validate( $inputTask ) {                
        
        if ( ! empty( $inputTask ) ) {
            $this->task = $this->sanitizeInput( $inputTask );
        }

        return $this->task;
    }
}