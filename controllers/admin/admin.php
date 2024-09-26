<?php

$router->map("GET","/login",function(){
    global $twig, $conn;

    if(isset($_SESSION["user_username"])){
        header("Location: /admin");
    }

    echo $twig->render('admin/login.twig');
});

$router->map("GET","/logout",function(){
    global $twig, $conn;

    if(isset($_SESSION["user_username"])){
        session_destroy();
    }

    header("Location: /login");
});

$router->map("POST","/login",function(){
    global $twig, $db;
    sleep(3);
    if(isset($_POST["username"])){
        if(isset($_POST["password"])){
            $user = $db->get("user","*",["username"=>$_POST["username"]]);

            if($user != null){
                if(password_verify($_POST["password"],$user["password"])){
                    $_SESSION["user_username"] = $user["username"];
                    //header("Location: /admin");
                    dump($user);
                }else{
                    header("Location: /login");
                }
            }else{
                header("Location: /login");
            }
        }
    }
});


$router->map('GET', '/admin', function() {
    global $twig, $db;
    admin_kick();

    echo $twig->render('admin/index.twig',[]);
});


?>