<?php

namespace ToDo\App;

/**
 * Filter handler class
 */
class Filter {

    private $sql;
    private $stmt;
    private $conn;
    private $result;
    private $status;
    private $task;
    private $id;
    private $count;
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
     * Counts the number of task
     *
     * @param string $status
     * 
     * @return int
     */
    public function countTask( $status ) {
        if ( $status == 'All' ) {
            $this->sql  = "SELECT * FROM tasks";
            $this->stmt = $this->conn->prepare( $this->sql );

        } else {
            $this->sql  = "SELECT * FROM tasks WHERE active = ?";
            $this->stmt = $this->conn->prepare( $this->sql );

            if ( $status == 'Complete' ) {

                $this->tempStatus = 0;
                $this->stmt->bind_param( 'i', $this->tempStatus );

            } elseif ( $status == 'Incomplete' ) {
                
                $this->tempStatus = 1;
                $this->stmt->bind_param( 'i', $this->tempStatus );

            }
        }

        if ( $this->stmt->execute() ) {

            $this->result = $this->stmt->get_result();
            return $this->result->num_rows;

        }
    }

    /**
     * Filters tasks
     *
     * @param string $flag
     * 
     * @return void
     */
    public function filterTask( $flag ) {
        $_SESSION['uri'] = $_SERVER['REQUEST_URI'];

        if ( $flag == 'All' ) {
            
            $this->sql  = "SELECT * FROM tasks";
            $this->stmt = $this->conn->prepare( $this->sql );

        } else {

            $this->sql  = "SELECT * FROM tasks WHERE active = ?";
            $this->stmt = $this->conn->prepare( $this->sql );

            if ( $flag == 'Complete' ) {

                $this->tempStatus = 0;
                $this->stmt->bind_param( 'i', $this->tempStatus );

            } elseif ( $flag == 'Incomplete' ) {

                $this->tempStatus = 1;
                $this->stmt->bind_param( 'i', $this->tempStatus );

            } else {
                print $this->conn->error;
            }
        }

        if ( $this->stmt->execute() ) {
            $this->result = $this->stmt->get_result();
            
            ?><ul id="list"><?php
                while ( $row = $this->result->fetch_assoc() ) {
                    
                    $this->id      = $row['id'];
                    $this->task    = $row['task'];
                    $this->status  = $row['active'];
                    $this->checked = '';
                    $this->class   = '';

                    if ( $this->status == 0 ) {
                        $this->class = 'line-through';
                        $this->checked  = 'checked';
                    }

                    ?>      
                    <li class="item">
                        <form method="post" action="?updateTaskStatus=<?php echo $this->id; ?>">
                            <label class="check-container">
                                <input type="checkbox" onchange="this.form.submit()" <?php echo $this->checked; ?>>
                                <span class="checkmark"></span>
                            </label>
                        </form>
                        <p class="text">
                            <form action="?updateTaskName=<?php echo $this->id; ?>" method="post">
                                <input type="text" id="task" name="task" class="input pb-2 <?php echo $this->class; ?>" value="<?php echo $this->task; ?>">
                            </form>
                        </p>
                        <i class="del" id="<?php echo $this->id; ?>">
                            <a href="?deleteById=<?php echo $this->id; ?>">X</a>
                        </i>
                    </li>
                    <?php           
                }
                ?> </ul> <?php
            }
    
        if ( $this->countTask( 'All' ) ) {
            $this->count = $this->countTask( 'Incomplete' );

            ?>
            <div class="info pl-3 pr-1 d-flex input align-items-baseline" style="background-color:white;">
                <div class="pt-3 pr-7"><?php echo "{$this->count} items left"; ?></div>
                <div class="filter pt-3 pb-3 pr-1" style="font-size:0.85rem; padding-left:11.50rem;">
                    <a class="btn btn-outline-secondary btn-sm" href="index.php">All</a>
                </div>
                <div class="filter pt-3 pb-3 pr-1" style="font-size:0.85rem;">
                    <a class="btn btn-outline-secondary btn-sm" href="?filterTask=Incomplete">Active</a>
                </div>
                <div class="filter pt-3 pb-3 pr-2" style="font-size:0.85rem;">
                    <a class="btn btn-outline-secondary btn-sm" href="?filterTask=Complete">Completed</a>
                </div>
                <?php if ( $this->countTask( 'Complete' ) > 0 ) { ?>
                    <div style="font-size:0.85rem; padding-left:12.20rem;">
                        <a class="btn btn-link btn-sm" style="color: rgb(121, 121, 121);" href="?deleteByStatus=Complete">Clear Completed</a>
                    </div>
                <?php } ?>
            </div>
        <?php
        }
        $this->conn->close();
    }
}