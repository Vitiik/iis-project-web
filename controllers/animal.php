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

    // echo "Zvíře:";
    //dump($zvire);

    // echo "Fotky:";
    //dump($obrazky);

    // echo "Očkování:";
    //dump(Animal::getOckovaniById($id));

    // echo "Hmotnost:";
    //dump($hmotnost);

    // echo "Měření:";
    // dump(Animal::getAllMereniById($id));

    // dump($zvire_je_volne);
    // dump($manipulace);
    
    $user = User::getLoggedInUser();

    // dump($manipulace);


    echo $twig->render('shelter/animal.twig',["zvire"=>$zvire, "obrazky"=>$obrazky, "hmotnost"=>$hmotnost, "user"=>$user, "zvire_je_volne"=>$zvire_je_volne, "manipulace"=>$manipulace]);
});

$router->map("POST","/createAnimal",function(){

    $_POST = json_decode(file_get_contents('php://input'), true);

    $response = Animal::createAnimal($_POST["jmeno"],$_POST["zivocisny_druh"],$_POST["plemeno"],$_POST["pohlavi"],$_POST["datum_narozeni"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        $zvire_id = $response;

        $response = Manipulace::createNalezeni($_POST["jmeno_nalezce"],$_POST["kontakt_na_nalezce"],$_POST["misto_nalezeni"],$_POST["cas"],$zvire_id);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        }else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Zvíře bylo přidáno do databáze",
                "data"=> array(
                    "id"=>$zvire_id,
                    "jmeno"=>$_POST["jmeno"],
                    "zivocisny_druh" => $_POST["zivocisny_druh"],
                    "plemeno" => $_POST["plemeno"],
                    "pohlavi" => $_POST["pohlavi"],
                    "datum_narozeni" => $_POST["datum_narozeni"],
                    "popis" => $_POST["popis"],
                    "jmeno_nalezce" => $_POST["jmeno_nalezce"],
                    "kontakt_na_nalezce" => $_POST["kontakt_na_nalezce"],
                    "misto_nalezeni" => $_POST["misto_nalezeni"],
                    "cas" => $_POST["cas"]
                    )
            ));
        }
    }
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

$router->map("POST","/acceptReservation",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);

    $user = User::getLoggedInUser();
    
    $response = Animal::acceptReservation($_POST["rezervace_id"],$user["id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Rezervace byla úspěčně shválena"
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

$router->map("POST","/zvireZapujceno",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = Animal::zvireZapujceno($_POST["rezervace_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Zvíře bylo zapůjčeno"
        ));
    }
});

$router->map("POST","/zvireVraceno",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);
    
    $response = Animal::zvireVraceno($_POST["rezervace_id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Zvíře bylo vráceno"
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

