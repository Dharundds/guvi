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
          $cursor = $collection->find([
               '_id' => intval($id),
          ]);
          // Print documents
          foreach ($cursor as $document) {
               $data = array([
                    "fname"=>$document['full_name'],
                    "email"=>$document['email'],
                    "username"=>$document['username'],
                    "dob"=>$document['dob'],
                    "age"=>$document['age'],
                    "phone"=>$document['phone']
               ]);
          }

          $json_data = json_encode($data);
          header('Content-Type','application/json');
          echo $json_data;


     }else{
          echo "Please log in";
     }
    
?>