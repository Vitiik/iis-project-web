<?php

use Core\Manipulace;
use Core\User;

$router->map("POST","/prodej",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["vytvorit"])){
        $response = Manipulace::createProdej($_POST["jmeno_zakaznika"],$_POST["telefon_zakaznika"],$_POST["cena"],$_POST["cas"],$_POST["zvire_id"]);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        } else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Prodej byl přidán do databáze",
                "data"=> array(
                    "jmeno_zakaznika"=>$_POST["jmeno_zakaznika"],
                    "telefon_zakaznika" => $_POST["telefon_zakaznika"],
                    "cena" => $_POST["cena"],
                    "cas" => $_POST["cas"],
                    "zvire_id" => $_POST["zvire_id"]
                    )
            ));
        }
    }

});

$router->map("POST","/nalezeni",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["vytvorit"])){
        $response = Manipulace::createNalezeni($_POST["jmeno_nalezce"],$_POST["kontakt_na_nalezce"],$_POST["misto_nalezeni"],$_POST["cas"],$_POST["zvire_id"]);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        } else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Nalezení bylo přidáno do databáze",
                "data"=> array(
                    "jmeno_nalezce"=>$_POST["jmeno_nalezce"],
                    "kontakt_na_nalezce" => $_POST["kontakt_na_nalezce"],
                    "misto_nalezeni" => $_POST["misto_nalezeni"],
                    "cas" => $_POST["cas"],
                    "zvire_id" => $_POST["zvire_id"]
                    )
            ));
        }
    }

});

$router->map("POST","/umrti",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["vytvorit"])){
        $response = Manipulace::createUmrti($_POST["pricina"],$_POST["cas"],$_POST["zvire_id"]);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        } else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Úmrtí bylo přidáno do databáze",
                "data"=> array(
                    "pricina"=>$_POST["jmeno_zakaznika"],
                    "cas" => $_POST["cas"],
                    "zvire_id" => $_POST["zvire_id"]
                    )
            ));
        }
    }

});

$router->map("POST","/prohlidka",function(){
    global $twig;

    $_POST = json_decode(file_get_contents('php://input'), true);

    // dump($_POST);
    if (isset($_POST["vytvorit"])){
        if($_POST["pozadavek_id"] == -1) $_POST["pozadavek_id"] = NULL; 
        if(!isset($_POST["vakcina"])) $_POST["vakcina"] = NULL;
        if(!isset($_POST["vyska"])) $_POST["vyska"] = NULL;
        if(!isset($_POST["delka"])) $_POST["delka"] = NULL;
        if(!isset($_POST["hmotnost"])) $_POST["hmotnost"] = NULL;

        $zverolekar_id = User::getLoggedInUser()["id"];

        $response = Manipulace::createProhlidka($_POST["zdravotni_stav"],$_POST["vakcina"],$_POST["vyska"],$_POST["delka"],$_POST["hmotnost"],$_POST["pozadavek_id"],$_POST["zvire_id"],$zverolekar_id);

        if ($response == false){
            echo json_encode(array(
                "status" => "error",
                "message" => "Nastala chyba při zápisu do databáze"
            ));
        } else {
            echo json_encode(array(
                "status" => "success",
                "message" => "Prohlídka byla přidána do databáze",
                "data"=> array(
                    "zdravotni_stav"=>$_POST["zdravotni_stav"],
                    "vakcina" => $_POST["vakcina"],
                    "vyska" => $_POST["vyska"],
                    "delka" => $_POST["delka"],
                    "hmotnost" => $_POST["hmotnost"],
                    "cas" => $_POST["cas"],
                    "zvire_id" => $_POST["zvire_id"],
                    "zverolekar_id" => $zverolekar_id
                    )
            ));
        }
    }

});
