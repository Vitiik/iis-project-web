<?php

use Core\User;

$router->map("GET","/login",function(){
    global $twig;

    if(isset($_SESSION["user_email"])){
        header("Location: /admin");
    }

    echo $twig->render('admin/login.twig');
});

$router->map("GET","/logout",function(){
    global $twig;

    if(isset($_SESSION["user_email"])){
        session_destroy();
    }

    header("Location: /");
});

$router->map("POST","/login",function(){
    global $twig, $db;
    sleep(1);
    $_POST = json_decode(file_get_contents('php://input'), true);

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

$router->map("GET","/muj-profil",function(){
    global $twig;

    $user = User::getLoggedInUser();

    if($user == null){
        header("Location: /");
        exit();
    }

    echo $twig->render('shelter/user.twig',["user"=>$user]);
});

$router->map("POST","/create-user",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["vytvorit"])){
        // $response = Manipulace::createUmrti($_POST["pricina"],$_POST["cas"],$_POST["zvire_id"]);

        // if ($response == false){
        //     echo json_encode(array(
        //         "status" => "error",
        //         "message" => "Nastala chyba při zápisu do databáze"
        //     ));
        // } else {
        //     echo json_encode(array(
        //         "status" => "success",
        //         "message" => "Úmrtí bylo přidáno do databáze",
        //         "data"=> array(
        //             "pricina"=>$_POST["jmeno_zakaznika"],
        //             "cas" => $_POST["cas"],
        //             "zvire_id" => $_POST["zvire_id"]
        //             )
        //     ));
        // }
    }
});

$router->map("POST","/change-password",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["zmenit"])){
        // $response = Manipulace::createUmrti($_POST["pricina"],$_POST["cas"],$_POST["zvire_id"]);

        // if ($response == false){
        //     echo json_encode(array(
        //         "status" => "error",
        //         "message" => "Nastala chyba při zápisu do databáze"
        //     ));
        // } else {
        //     echo json_encode(array(
        //         "status" => "success",
        //         "message" => "Úmrtí bylo přidáno do databáze",
        //         "data"=> array(
        //             "pricina"=>$_POST["jmeno_zakaznika"],
        //             "cas" => $_POST["cas"],
        //             "zvire_id" => $_POST["zvire_id"]
        //             )
        //     ));
        // }
    }
});