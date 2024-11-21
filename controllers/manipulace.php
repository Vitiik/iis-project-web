<?php

use Core\Manipulace;

$router->map("POST","/prodej",function(){
    global $twig;

    if (isset($_POST["vytvorit"])){
        Manipulace::createProdej($_POST["jmeno_zakaznika"],$_POST["telefon_zakaznika"],$_POST["cena"],$_POST["cas"],$_POST["zvire_id"]);
    }

});

$router->map("POST","/nalezeni",function(){
    global $twig;

    if (isset($_POST["vytvorit"])){
        Manipulace::createNalezeni($_POST["jmeno_nalezce"],$_POST["kontakt_na_nalezce"],$_POST["misto_nalezeni"],$_POST["cas"],$_POST["zvire_id"]);
    }

});

$router->map("POST","/umrti",function(){
    global $twig;

    if (isset($_POST["vytvorit"])){
        Manipulace::createUmrti($_POST["pricina"],$_POST["cas"],$_POST["zvire_id"]);
    }

});

$router->map("POST","/prohlidka",function(){
    global $twig;
    dump($_POST);
    if (isset($_POST["vytvorit"])){
        if($_POST["pozdavek_id"] == -1) $_POST["pozdavek_id"] = NULL; 
        if(!isset($_POST["vakcina"])) $_POST["vakcina"] = NULL;
        if(!isset($_POST["vyska"])) $_POST["vyska"] = NULL;
        if(!isset($_POST["delka"])) $_POST["delka"] = NULL;
        if(!isset($_POST["hmotnost"])) $_POST["hmotnost"] = NULL;

        Manipulace::createProhlidka($_POST["zdravotni_stav"],$_POST["vakcina"],$_POST["vyska"],$_POST["delka"],$_POST["hmotnost"],$_POST["cas"],$_POST["pozadavek_id"],$_POST["zvire_id"]);
    }

});
