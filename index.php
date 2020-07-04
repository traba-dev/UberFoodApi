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
  
  $app->get("/foods", function() use ($db,$app) {
    
    $result = $db -> query("SELECT food.id, food.name, description, price, food.status, img, categoryID, category_food.name AS catname from food INNER JOIN category_food ON food.categoryID = category_food.id");
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    if (!$result){
        $rows[] = "no hay datos";
        echo json_encode($rows);
    } else {
        echo json_encode($rows); 
    }   
        $db -> close();
      
  });

  $app->get("/getFoodsAdByCategory/:id",function($id) use($db,$app)
  {
     
        $result = $db -> query("SELECT * FROM food WHERE categoryID = ".$id."");
        
        $rows = array();
        while($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
    
        if (count($rows) == 0){
            $app->notFound();
        } else {
            echo json_encode($rows); 
        }   
            
      $db-> close();

  });

  $app->get("/getFoodById/:id",function($id) use($db,$app)
    {
    
        $result = $db -> query("SELECT * FROM food WHERE id = ".$id."");
    
        if ($result == null){
            $app->notFound();
        } else {
            echo json_encode($result->fetch_assoc()); 
        }   
            
        $db-> close();

    });

    $app->get("/deleteFood/:id",function($id) use($db,$app)
    {
       
          $result = $db -> query("DELETE FROM food WHERE id = ".$id."");
      
          if ($result) {
              $result = array("message" => "Registro Eliminado");
          }else {
              $result = array("message" => "Registro no Eliminado");
          }
  
          echo json_encode($result);
              
        $db-> close();
  
    });
  
    $app->post("/updateFood",function() use($db,$app)
    {
          $query = "UPDATE food SET "
          . "name ='{$app->request->post("name")}',"
          . "description ='{$app->request->post("description")}',"
          . "price = '{$app->request->post("price")}',"
          . "img = '{$app->request->post("img")}',"
          . "status = '{$app->request->post("status")}',"
          . "categoryID = '{$app->request->post("categoryID")}'"
          . " WHERE id={$app->request->post("id")}";
  
          $update = $db->query($query);
  
          if ($update) {
              $result = array("message" => "Registro Actualizo");
          }else {
              $result = array("message" => "Registro no actualizo");
          }
  
          echo json_encode($result);
    });
  
    $app->post("/addFood",function() use($db,$app)
    {
          $query = "INSERT INTO food (name,description,price,img,status,categoryID) VALUES ("
          . "'{$app->request->post("name")}',"
          . "'{$app->request->post("description")}',"
          . "'{$app->request->post("price")}',"
          . "'{$app->request->post("img")}',"
          . "'{$app->request->post("status")}',"
          . "'{$app->request->post("categoryID")}'"
          . ")";
  
          $insertar = $db->query($query);
  
          if ($insertar) {
          $result = array("message" => "Registro Insertado");
          }else {
          $result = array("message" => "Registro no insertó");
          }
  
          echo json_encode($result);
    });
  
    $app->post("/uploadImage",function($request,$response,$args) use($db,$app)
    {
  
          $result = array("message" => "Registro Insertado");
  
          echo json_encode($result);
    });

  
  $app->run();

?>