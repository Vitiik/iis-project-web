<?php

use Core\User;

$router->map("GET","/login",function(){
    global $twig, $conn;

    if(isset($_SESSION["user_email"])){
        header("Location: /admin");
    }

    echo $twig->render('admin/login.twig');
});

$router->map("GET","/logout",function(){
    global $twig, $conn;

    if(isset($_SESSION["user_email"])){
        session_destroy();
    }

    header("Location: /login");
});

$router->map("POST","/login",function(){
    global $twig, $db;
    sleep(3);
    if(isset($_POST["email"])){
        if(isset($_POST["password"])){
            $user = User::getByEmail($_POST["email"]);

            if($user != null){
                if(password_verify($_POST["password"],$user["heslo"])){
                    $_SESSION["user_email"] = $user["email"];
                    //TODO: Zde přidat roli
                    header("Location: /");
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