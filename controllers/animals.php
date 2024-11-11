<?php

use Core\Animal;

$router->map("GET","/zvirata",function(){
    global $twig;

    $zvirata = Animal::getAllAnimals();
    $ockovani = Animal::getDateOfLastOckovaniForAllAnimals();

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
    }

    // dump($zvirata);

    echo $twig->render('shelter/animals.twig',["zvirata"=>$zvirata]);
});
