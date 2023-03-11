<?php
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379); 
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', 'tcp://127.0.0.1:6379');
    session_start();
    
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'root';
    $DATABASE_NAME = 'account';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if(mysqli_connect_errno()){
        exit("Failed to connect = ".mysqli_connect_error());
    }



    if ( !isset($_POST['username'], $_POST['password']) ) {
        exit('Please fill both the username and password fields!');
    }

    if($smt = $con->prepare('SELECT id,password FROM users where username = ?')){
        $smt->bind_param('s',$_POST['username']);
        $smt->execute();
        $smt->store_result();
        if ($smt->num_rows > 0) {
            $smt->bind_result($id,$password);
            $smt->fetch();
            if ($_POST['password']===$password) {
                $_SESSION['username']=$_POST['username'];  
                // $redis->set('name',$_POST['username']);
                $redis->set('session:'.session_id(), serialize($_SESSION));
                $redis->set($_POST['username'],$id);
                $data = array("name"=>$_POST['username'],"session"=>session_id());
                $json_data = json_encode($data);
                header('Content-Type','application/json');
                echo $json_data;
            } else {
                // Incorrect password
                echo 'Incorrect username and/or password!';
            }
        } else {
            // Incorrect username
            echo 'Incorrect username and/or password!';
        }
        $smt->close();
    }



?>