<?php 


    require '../vendor/autoload.php';
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379); 
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', 'tcp://127.0.0.1:6379');
    session_start();

    $client = new MongoDB\Client("mongodb+srv://dharundds:dharundds@cluster0.rzr9ju3.mongodb.net/?retryWrites=true&w=majority");

    $collection = $client->Accounts->users;
    
    $sess_data = unserialize($redis->get('session:'.session_id()));
    if(isset($sess_data['username'])){
         $id = $redis->get($sess_data['username']);
         $cursor = $collection->updateOne(
            ['_id' => intval($id)],
            ['$set'=>[
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "full_name" => $_POST['fname'],
                "age" => $_POST['age'],
                "dob" => $_POST['dob'],
                "phone" => $_POST['phone'],
            ]]
        );
        echo "Success";
    } else {
        echo "some error";
      }


?>
