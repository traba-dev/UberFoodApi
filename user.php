<?php

header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

    require "config.php";
    require_once 'vendor/autoload.php';

    $app = new \Slim\Slim();


    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
      }

      $app->get("/getUsers", function() use ($db,$app){

            $result = $db->query("SELECT * FROM users");

            $rows = array();
            while($r = mysqli_fetch_assoc($result)) {
                $rows[] = $r;
            }

            if (count($rows) == 0) {
                $app->notFound();
            }else {
                echo json_encode($rows);
            }
            
            

            $db->close();
      });

    $app->get("/getUserById/:id",function($id) use($db,$app)
       { 
        $result = $db -> query("SELECT * FROM users WHERE U_Id = ".$id."");

        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }

        if (count($rows) == 0) {
           $app->notFound();
        }else {
            echo json_encode($rows);
        }
        $db->close();
       
    });
    $app->get("/getUsersByCredentials/:pass/:nickOrEmail",function($pass,$nickOrEmail) use($db,$app)
       { 
        $result = $db->query("SELECT * FROM users WHERE U_Pass = "."'".$pass."'"." AND (U_Email="."'".$nickOrEmail."'"." OR U_Nick="."'".$nickOrEmail."')");

        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }

        if (count($rows) == 0) {
            $app->notFound();
        }else {
            echo json_encode($rows);
        }
        $db->close();
       
    });

    $app->post("/addUser",function() use($db,$app)
    {
        $query = "INSERT INTO users (U_Name,U_Apellidos,U_Email,U_Nick,U_Pass,U_Status,U_Gender) VALUES ("
        . "'{$app->request->post("U_Name")}',"
        . "'{$app->request->post("U_Apellidos")}',"
        . "'{$app->request->post("U_Email")}',"
        . "'{$app->request->post("U_Nick")}',"
        . "'{$app->request->post("U_Pass")}',"
        . "'A',"
        . "'{$app->request->post("U_Gender")}'"
        . ")";

        $insertar = $db->query($query);

        if ($insertar) {
        $result = array("message" => "Registro Insertado");
        }else {
        $result = array("message" => "Registro no insertado");
        }

        echo json_encode($result);
    });

    $app->post("/changeStatusUser",function() use($db,$app)
    {
        
        $query = "UPDATE users SET "
        . "U_Status ='{$app->request->post("U_Status")}'"
        . " WHERE U_Id={$app->request->post("U_Id")}";

        $update = $db->query($query);

        if ($update) {
            $result = array("message" => "Registro Actualizado");
        }else {
            $result = array("message" => "Registro no Actualizado");
        }

        echo json_encode($result);
    });
    $app->post("/updateUser",function() use($db,$app)
    {
        
        $query = "UPDATE users SET "
        . "U_Name ='{$app->request->post("U_Name")}',"
        . "U_Apellidos ='{$app->request->post("U_Apellidos")}',"
        . "U_Email = '{$app->request->post("U_Email")}',"
        . "U_Nick = '{$app->request->post("U_Nick")}',"
        . "U_Pass = '{$app->request->post("U_Pass")}',"
        . "U_Gender = '{$app->request->post("U_Gender")}'"
        . " WHERE U_Id={$app->request->post("U_Id")}";

        $update = $db->query($query);

        if ($update) {
            $result = array("message" => "Registro Actualizado");
        }else {
            $result = array("message" => "Registro no Actualizado");
        }

        echo json_encode($result);
    });

  $app->run();

?>