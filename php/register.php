<?php 
require '../vendor/autoload.php';

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'root';
    $DATABASE_NAME = 'account';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if(mysqli_connect_errno()){
        exit("Failed to connect = ".mysqli_connect_error());
    }


    $client = new MongoDB\Client("mongodb+srv://dharundds:dharundds@cluster0.rzr9ju3.mongodb.net/?retryWrites=true&w=majority");

    $collection = $client->Accounts->users;



    if ( !isset($_POST['username'], $_POST['email'],$_POST['fname'],$_POST['age'],$_POST['dob'],$_POST['phone'],$_POST['password'],$_POST['cpassword']) ) {
        exit('Please fill all the fields!');
    }

    if($_POST['password']!==$_POST['cpassword']){
        exit("Password didn't match");
    }

    if($query = $con->prepare('select username from users where (username = ?)')){
        $query->bind_param('s', $_POST["username"]);
        $query->execute();
        $query->store_result();
        if($query->num_rows == 0){
          if($query = $con->prepare('insert into users (username, password) values (?, ?);')){
            $query->bind_param("ss", $_POST["username"], $_POST["password"]);
            $query->execute();
            if($query->store_result()){
              $query = $con->prepare('select id from users where (username = ?);');
              $query->bind_param("s", $_POST["username"]);
              $query->execute();
              $query->bind_result($id);
              $query->fetch();
              $cursor = $collection -> insertOne([
                "_id" => $id,
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "full_name" => $_POST['fname'],
                "age" => $_POST['age'],
                "dob" => $_POST['dob'],
                "phone" => $_POST['phone'],
              ]);
            }
            
            echo true;
          }
        } else {
          echo "username already exists";
        }
      }







?>