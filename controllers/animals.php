<?php


$router->map("GET","/zvirata",function(){
    global $twig;
    echo $twig->render('shelter/animals.twig');
});
