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

    if (isset($_POST["vytvorit"])){
        Manipulace::createProhlidka($_POST["zdravotni_stav"],$_POST["vakcina"],$_POST["vyska"],$_POST["delka"],$_POST["hmotnost"],$_POST["cas"],$_POST["pozadavek_id"],$_POST["zvire_id"]);
    }

});
