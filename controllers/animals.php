<?php

use Core\Animal;
use Core\User;
use Core\Manipulace;

$router->map("GET","/zvirata",function(){
    global $twig;

    $zvirata = Animal::getAllAnimals();
    $ockovani = Animal::getDateOfLastOckovaniForAllAnimals();
    $zadosti = Animal::rezervaceNaSchvaleni();
    $user = User::getLoggedInUser();
    $usersList = User::getAllUsers();
    $rolesList = User::getAllRoles();

    // Add date of last očkování to every animal
    foreach ($zvirata as &$zvire) {
        $nalezeno = 0;
        foreach ($ockovani as $ock) {
            if ($zvire['id'] == $ock['zvire_id']) {
                $zvire['posledni_ockovani'] = $ock['cas'];
                $nalezeno = 1;
                break;
            }
        }
        if ($nalezeno == 0){
            $zvire['posledni_ockovani'] = NULL;
        }
        if ($zvire['url_mala'] == null) {
            $zvire['url_mala'] = "/media/images/no-image.jpg";
        }
        $zvire["umrti"] = Animal::getUmrtiById($zvire['id']);
    }

    echo $twig->render('shelter/animals.twig',["zvirata"=>$zvirata,"user"=>$user,"zadosti"=>$zadosti, "usersList"=>$usersList, "rolesList"=>$rolesList]);
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

$router->map("POST","/declineReservation",function(){
    $_POST = json_decode(file_get_contents('php://input'), true);

    $user = User::getLoggedInUser();
    
    $response = Animal::declineReservation($_POST["rezervace_id"],$user["id"]);

    if ($response == false){
        echo json_encode(array(
            "status" => "error",
            "message" => "Nastala chyba při zápisu do databáze"
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Rezervace byla zamítnuta"
        ));
    }
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
