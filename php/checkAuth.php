<?php 
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379); 
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', 'tcp://127.0.0.1:6379');
    session_start();
    $jsonData = file_get_contents('php://input');
    $sess_data = unserialize($redis->get('session:'.session_id()));
    $obj = json_decode($jsonData);
    
    if(session_id()===$obj->session_id){

        if(isset($sess_data['username'])){
            echo true;
        }else{
            echo false;
        }
    }else{
        echo false;
    }

?>