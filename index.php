<?php
    require_once __DIR__ . '/vendor/autoload.php';
        
    session_start();

    $dbHost   = 'localhost';
    $dbUser   = 'root';
    $password = '';
    $dbName   = 'todos';

    $app     = new ToDo\App( $dbHost, $dbUser, $password, $dbName );
    $objects = $app->getObjects();
    
    extract( $objects );

    if( isset( $_SESSION['uri'] ) ){
        $uri = $_SESSION['uri'];
    } else {
        $uri = 'index.php';
    }    
    
    if ( isset( $_GET['insertTask'] ) ) {
        $insert->insertTask();
        $error = $insert->getError();
    }

    if ( isset( $_GET['updateTaskName'] ) ) {
        $id = $_GET['updateTaskName'];
        $update->updateTaskName($id);
        $error = $update->getError();
        if( empty( $error ) ) {
            header( "location:$uri" );
        }
    }

    if ( isset( $_GET['updateTaskStatus'] ) ) {
        $id = $_GET['updateTaskStatus'];
        $update->updateTaskStatus( $id );
        header( "location:$uri" );
    }
    
    if ( isset( $_GET['deleteById'] ) ) {
        $id = $_GET['deleteById'];
        $delete->deleteById( $id );
        header( "location:$uri" );
    }
    
    if ( isset( $_GET['deleteByStatus'] ) ) {
        $status = $_GET['deleteByStatus'];
        $delete->deleteByStatus( $status );
        header( "location:$uri" );
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">   
</head>
<body>
    <div class="container" style="">
        <div class="heading">todos</div>
    
        <div class="form">
            <form action="?insertTask" method="post">
                <input type="text" id="task" name="task" class="input" placeholder="What needs to be done?">
                <?php
                
                if ( $filter->countTask( 'All' ) > 0 ) {
                    print( '<span><i class="fa fa-chevron-circle-down icon"></i></span>' );
                }
                
                if( isset( $error ) ) {
                    printf( '<div class="alert alert-danger" style="text-align:center; top:0.5rem;">%s</div>', $error );
                }              
                ?>
            </form>
        </div>
        <div class="content">
        <?php                
            if ( isset( $_GET['filterTask'] ) ) {
                $status = $_GET['filterTask'];
                $filter->filterTask( $status );
            } else {
                $filter->filterTask( 'All' );
            }
        ?>
        </div>
    </div>
    <script type="text/javascript" src="Assets/js/bootstrap.min.js"></script>
</body>
</html>