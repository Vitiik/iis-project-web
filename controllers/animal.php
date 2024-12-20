<?php

use Core\Animal;
use Core\User;
use Core\Manipulace;

$router->map("GET","/zvire/[i:id]",function($id){
    global $twig;

    $zvire = Animal::getAnimalById($id);
    $obrazky = Animal::getImagesById($id);
    $hmotnost = Animal::getHmotnostById($id);
    $zvire_je_volne = Animal::getZvireJeVolneById($id);
    $manipulace = Animal::getManipulaceById($id);
    $pozadavkyNaProhlidku = Animal::getAllZadostiNaProhlidkuById($id);
    $user = User::getLoggedInUser();

    echo $twig->render('shelter/animal.twig',["zvire"=>$zvire, "obrazky"=>$obrazky, "hmotnost"=>$hmotnost, "user"=>$user, "zvire_je_volne"=>$zvire_je_volne, "manipulace"=>$manipulace, "pozadavkyNaProhlidku"=>$pozadavkyNaProhlidku]);
});

$router->map("POST","/editAnimal",function(){

    $_POST = json_decode(file_get_contents('php://input'), true);

    $response = Animal::editAnimal($_POST);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Zvíře bylo aktualizováno v databáze",
            "data"=> $_POST
        ));
    }

});

$router->map("POST","/reserveAnimal",function(){

    $_POST = json_decode(file_get_contents('php://input'), true);

    $user = User::getLoggedInUser();

    foreach($_POST["id"] as $id){
        $response = Animal::reserveAnimal($id,$_POST["zvire_id"],$user["id"]);
    }

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Rezervace proběhla úspěšně",
            "data" => $_POST["id"]
        ));
    }
});

$router->map("POST","/addReservationTimes",function(){

    $_POST = json_decode(file_get_contents('php://input'), true);

    $user = User::getLoggedInUser();

    $response = Animal::createRozvrhProRezervovani($_POST["cas_zacatku"],$_POST["cas_konce"],$_POST["zvire_id"],$user["id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Úspěšně přidán čas pro registraci"
        ));
    }
});

$router->map("POST","/deleteReservationTime",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = Animal::deleteReservationTime($_POST["rezervace_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Čas pro rezervaci byl úspěčně smazána"
        ));
    }
});

$router->map("POST","/vytvoritPozadavekNaProhlidku",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);

    $user = User::getLoggedInUser();
    
    $response = Animal::createPozadavekNaProhlidku($_POST["zamereni"],$user["id"],$_POST["zvire_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Požadavek na prohlídku byl úspěčně vytvořen"
        ));
    }
});

