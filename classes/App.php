<?php

namespace ToDo;

/**
 * App handler class
 */
class App {

    private $insert;
    private $update;
    private $delete;
    private $filter;

    /**
     * Class constructor
     */
    function __construct( $host, $user, $password, $db ) {
        $config = new Database\Config( $host, $user, $password, $db );
        $conn   = $config->setConnection();

        $this->insert = new App\Insert( $conn );
        $this->filter = new App\Filter( $conn );
        $this->update = new App\Update( $conn );
        $this->delete = new App\Delete( $conn );
    }

    /**
     * Retrieves the created objects
     *
     * @return void
     */
    public function getObjects() {

        $objects = array(
            'insert' => $this->insert,
            'update' => $this->update,
            'delete' => $this->delete,
            'filter' => $this->filter
        );

        return $objects;
    }
}