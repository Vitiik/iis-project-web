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

$router->map('GET', '/admin', function() {
    global $twig, $db;
    admin_kick();

    echo $twig->render('shelter/index.twig',[]);
});

$router->map("GET","/registrace",function(){
    global $twig;

    echo $twig->render('admin/register.twig');
});

$router->map("GET","/muj-profil",function(){
    global $twig;

    $user = User::getLoggedInUser();
    $historieRezervaci = User::getAllRezervaceById($user["id"]);

    if($user == null){
        header("Location: /");
        exit();
    }

    echo $twig->render('shelter/user.twig',["user"=>$user,"historieRezervaci"=>$historieRezervaci]);
});

$router->map("POST","/login",function(){
    global $twig, $db;
    sleep(1);

    if(isset($_POST["email"])){
        if(isset($_POST["password"])){
            $user = User::getByEmail($_POST["email"]);

            if($user != null){
                if(password_verify($_POST["password"],$user["heslo"])){
                    $_SESSION["user_email"] = $user["email"];
                    header("Location: /");
                }else{
                    header("Location: /login");
                }
            }else{
                header("Location: /login");
            }
        }
    }
});

$router->map("POST","/setRole",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = User::setRole($_POST["uzivatel_id"],$_POST["role_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Role byla úspěšně nastavena"
        ));
    }
    
});

$router->map("POST","/deleteRole",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = User::deleteRole($_POST["uzivatel_id"],$_POST["role_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Role byla úspěšně smazána"
        ));
    }
    
});

$router->map("POST","/overitUzivatele",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = User::overitUser($_POST["uzivatel_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Uživatel byl úspěšně ověřen"
        ));
    }
    
});

$router->map("POST","/deleteUser",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = User::deleteUser($_POST["uzivatel_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při práci s databází"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Uživatel byl úspěšně smazán"
        ));
    }
    
});

$router->map("POST","/create-user",function(){
    global $twig;

    $isUser = User::getByEmail($_POST["email"]);

    if ( $isUser != null){
        header("Location: /registrace");
    }
    
    $response = User::createUser($_POST["jmeno"],$_POST["prijmeni"],$_POST["email"],$_POST["password"]);

    if ($response == false){
        header("Location: /registrace");
    } else {
        $_SESSION["user_email"] = $_POST["email"];
        User::setRole(User::getByEmail($_POST["email"])["id"],4);
        header("Location: /");
    } 
});

$router->map("POST","/changePassword",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    $loggedInUser = User::getByEmail($_SESSION["user_email"]);

    if($loggedInUser == null) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Nepřihlášený uživatel nemůže měnit heslo"
        )); 
        return;
    }

    if(password_verify($_POST["stare_heslo"],$loggedInUser["heslo"])){
    
        if($_POST["nove_heslo"] != $_POST["nove_heslo_znovu"]){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nová hesla se neshodují"
            )); 
            return;
        }

        $response = User::changePassword($loggedInUser["id"],$_POST["nove_heslo"]);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        } else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Heslo bylo změněno"
            ));
        }
    }else{
        echo json_encode(array(
            "status" => "error",
            "message" => "Nesprávné původní heslo"
        )); 
    }
});