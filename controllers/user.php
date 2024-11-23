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
    // $_POST = json_decode(file_get_contents('php://input'), true);

    // dump($_POST);

    if(isset($_POST["email"])){
        if(isset($_POST["password"])){
            $user = User::getByEmail($_POST["email"]);

            if($user != null){
                if(password_verify($_POST["password"],$user["heslo"])){
                    $_SESSION["user_email"] = $user["email"];
                    //TODO: Zde přidat roli
                    header("Location: /");
                    // dump($user);
                    // echo json_encode(array(
                    //     "status" => "success",
                    //     "message" => "Úspěšně přihlášen",
                    //     "redirect" => "/"
                    // ));
                }else{
                    header("Location: /login");
                    // echo json_encode(array(
                    //     "status" => "error",
                    //     "message" => "Špatné heslo",
                    //     "redirect" => "/login"
                    // ));
                }
            }else{
                header("Location: /login");
                // echo json_encode(array(
                //     "status" => "error",
                //     "message" => "Na tento email není vytvořen žádný účet",
                //     "redirect" => "/login"
                // ));
            }
        }
    }
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

    if($user == null){
        header("Location: /");
        exit();
    }

    echo $twig->render('shelter/user.twig',["user"=>$user]);
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

    // $_POST = json_decode(file_get_contents('php://input'), true);

    $isUser = User::getByEmail($_POST["email"]);

    if ( $isUser != null){
        // echo json_encode(array(
        //     "status" => "error",
        //     "message" => "Uživatel s tímto emailem již existuje"
        // ));
        // return;
        header("Location: /registrace");
    }
    
    $response = User::createUser($_POST["jmeno"],$_POST["prijmeni"],$_POST["email"],$_POST["password"]);

    if ($response == false){
        // echo json_encode(array(
        //     "status" => "error",
        //     "message" => "Nastala chyba při zápisu do databáze"
        // ));
        header("Location: /registrace");
    } else {
        $_SESSION["user_email"] = $_POST["email"];
        User::setRole(User::getByEmail($_POST["email"])["id"],4);
        // echo json_encode(array(
        //     "status" => "success",
        //     "message" => "Uživatel byl přidán do databáze",
        //     "redirect" => "/"
        // ));
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