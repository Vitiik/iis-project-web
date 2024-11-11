<?php

use Core\Animal;

$router->map("GET","/zvire/[i:id]",function($id){
    global $twig;

    $zvire = Animal::getAnimalById($id);
    $obrazky = Animal::getImagesById($id);
    $hmotnost = Animal::getHmotnostById($id);

    // echo "Zvíře:";
    // dump($zvire);

    // echo "Fotky:";
    // dump($obrazky);

    // echo "Očkování:";
    // dump(Animal::getOckovaniById($id));

    // echo "Hmotnost:";
    // dump($hmotnost);

    // echo "Měření:";
    // dump(Animal::getAllMereniById($id));

    echo $twig->render('shelter/animal.twig',["zvire"=>$zvire, "obrazky"=>$obrazky, "hmotnost"=>$hmotnost]);
});

$router->map("POST","/createAnimal",function(){

    Animal::createAnimal($_POST["jmeno"],$_POST["zivocisny_druh"],$_POST["plemeno"],$_POST["pohlavi"],$_POST["datum_narozeni"]);

});