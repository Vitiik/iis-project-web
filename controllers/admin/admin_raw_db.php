<?php

$router->map("GET","/admin/raw_db/[:table_name]",function($table_name){
    global $twig, $db;
    admin_kick();

    $data = $db->select($table_name,"*");
    echo $twig->render('admin/raw_db.twig',["data"=>$data,"table_name"=>$table_name]);
});

$router->map("POST","/admin/raw_db/[:table_name]/edit",function($table_name){
    global $twig, $db;
    admin_kick();

    $_POST = json_decode(file_get_contents('php://input'), true);

    foreach ($_POST as $key => $field) {
        if($field === "NULL"){
            $_POST[$key] = null;
        }
    }

    $db->update($table_name,$_POST,["id"=>$_POST["id"]]);

    header("Location: /admin/raw_db/".$table_name);
});

$router->map("POST","/admin/raw_db/[:table_name]/delete",function($table_name){
    global $twig, $db;
    admin_kick();

    $_POST = json_decode(file_get_contents('php://input'), true);

    $db->delete($table_name,["id"=>$_POST["id"]]);

    header("Location: /admin/raw_db/".$table_name);
});

$router->map("POST","/admin/raw_db/[:table_name]/add",function($table_name){
    global $twig, $db;
    admin_kick();

    $_POST = json_decode(file_get_contents('php://input'), true);

    foreach ($_POST as $key => $field) {
        if($field === "NULL"){
            $_POST[$key] = null;
        }
    }

    $db->insert($table_name,$_POST);

    header("Location: /admin/raw_db/".$table_name);
});

?>