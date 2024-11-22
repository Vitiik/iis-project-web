<?php

use Core\Animal;
use Core\User;

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
        echo json_encode(array(
            "status" => "success",
            "message" => "Zvíře bylo přidáno do databáze",
            "data"=> array(
                "jmeno"=>$_POST["jmeno"],
                "zivocisny_druh" => $_POST["zivocisny_druh"],
                "plemeno" => $_POST["plemeno"],
                "pohlavi" => $_POST["pohlavi"],
                "datum_narozeni" => $_POST["datum_narozeni"]
                )
        ));
    }

});